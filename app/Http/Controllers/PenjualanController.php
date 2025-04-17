<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PenjualanController extends Controller
{
    public function show($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);

        return view('admin.penjualan.show', compact('penjualan'));
    }


    /**
     * Menampilkan daftar transaksi penjualan
     */
    public function index()
    {
        $penjualan = Penjualan::with('pelanggan', 'user')->get();
        return view('admin.penjualan.index', compact('penjualan'));
    }

    /**
     * Menampilkan form tambah penjualan
     */
    public function create()
    {
        $barangs = Barang::all();
        return view('admin.penjualan.create', compact('barangs'));
    }



    public function store(Request $request)
    {
        // ==================== VALIDASI INPUT ====================
        $request->validate([
            'tgl_faktur' => 'required|date',
            'barang' => 'required|string', // Wajib JSON
        ]);

        // Decode JSON barang
        $barangs = json_decode($request->barang, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($barangs)) {
            return back()->withErrors(['barang' => 'Format data barang tidak valid.'])->withInput();
        }

        // Validasi tiap item barang
        foreach ($barangs as $index => $barang) {
            if (!isset($barang['id'], $barang['harga_jual'], $barang['jumlah'])) {
                return back()->withErrors([
                    'barang' => "Data barang ke-{$index} tidak lengkap (id, harga_jual, jumlah wajib)."
                ])->withInput();
            }

            if (!is_numeric($barang['harga_jual']) || $barang['harga_jual'] < 1) {
                return back()->withErrors([
                    'barang' => "Harga jual barang ke-{$index} tidak valid."
                ])->withInput();
            }

            if (!is_numeric($barang['jumlah']) || $barang['jumlah'] < 1) {
                return back()->withErrors([
                    'barang' => "Jumlah barang ke-{$index} tidak valid."
                ])->withInput();
            }

            if (!Barang::find($barang['id'])) {
                return back()->withErrors([
                    'barang' => "Barang dengan ID {$barang['id']} tidak ditemukan."
                ])->withInput();
            }
        }

        // ==================== PROSES TRANSAKSI ====================
        // Generate no faktur
        $noFaktur = 'FTR' . str_pad(Penjualan::count() + 1, 5, '0', STR_PAD_LEFT);

        // Simpan header penjualan
        $penjualan = Penjualan::create([
            'no_faktur' => $noFaktur,
            'tgl_faktur' => $request->tgl_faktur,
            'total_bayar' => 0,
            'pelanggan_id' => $request->pelanggan_id ?? null,
            'user_id' => Auth::id(),
            'metode_pembayaran' => 'cash',
            'status_pembayaran' => 'lunas',
        ]);

        $totalPenjualan = 0;

        // Simpan detail barang
        foreach ($barangs as $barangData) {
            $barang = Barang::findOrFail($barangData['id']);
            $subTotal = $barangData['harga_jual'] * $barangData['jumlah'];
            $totalPenjualan += $subTotal;

            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'barang_id' => $barang->id,
                'harga_jual' => $barangData['harga_jual'],
                'jumlah' => $barangData['jumlah'],
                'sub_total' => $subTotal,
            ]);

            $barang->decrement('stok', $barangData['jumlah']);
        }

        // Update total bayar
        $penjualan->update(['total_bayar' => $totalPenjualan]);

        // ==================== CETAK FAKTUR ====================
        try {
            $connector = new WindowsPrintConnector("POS-58");

            $printer = new Printer($connector);

            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
            $printer->text("TOKO ANDA\n");
            $printer->selectPrintMode();
            $printer->text("Jl. Contoh No. 123\nTelp: 0812-3456-7890\n");
            $printer->text("================================\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            // Info Transaksi
            // $printer->text("No. Faktur : " . $penjualan->no_faktur . "\n");
            // $printer->text("Tanggal    : " . $penjualan->tgl_faktur . "\n");
            // $printer->text("Kasir      : " . $penjualan->user->name . "\n");
            // $printer->text("Pelanggan  : " . ($penjualan->pelanggan->nama ?? "Umum") . "\n");
            // $printer->text("================================\n");

            // // Detail Barang
            // $printer->text("BARANG           QTY   HARGA   SUBTOTAL\n");
            // $printer->text("----------------------------------------\n");
            // foreach ($penjualan->details as $detail) {
            //     $namaBarang = substr($detail->barang->nama, 0, 15);
            //     $printer->text(sprintf(
            //         "%-15s %3s %7s %10s\n",
            //         $namaBarang,
            //         $detail->jumlah,
            //         number_format($detail->harga_jual, 0, ',', '.'),
            //         number_format($detail->sub_total, 0, ',', '.')
            //     ));
            // }

            // // Footer
            // $printer->text("================================\n");
            // $printer->setJustification(Printer::JUSTIFY_RIGHT);
            // $printer->text("TOTAL: " . number_format($penjualan->total_bayar, 0, ',', '.') . "\n");
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->text("Terima kasih atas kunjungannya!\n");
            $printer->pulse();
            $printer->cut();
            $printer->close();
        } catch (\Exception $e) {
            Log::error("Gagal mencetak faktur: " . $e->getMessage());
            return redirect()->route('penjualan.index')
                ->with('warning', 'Transaksi berhasil tapi gagal cetak struk!');
        }

        return redirect()->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dan struk tercetak!');
    }
}
