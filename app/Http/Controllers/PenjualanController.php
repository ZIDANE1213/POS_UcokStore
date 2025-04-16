<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Menyimpan data penjualan
     */
    public function store(Request $request)
    {
        // Validasi awal
        $request->validate([
            'tgl_faktur' => 'required|date',
            'barang' => 'required|string', // Wajib JSON
        ]);

        // Decode JSON barang
        $barangs = json_decode($request->barang, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($barangs)) {
            return back()->withErrors(['barang' => 'Format data barang tidak valid.'])->withInput();
        }

        // Validasi tiap barang
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

        foreach ($barangs as $barangData) {
            $barang = Barang::findOrFail($barangData['id']);
            $subTotal = $barangData['harga_jual'] * $barangData['jumlah'];
            $totalPenjualan += $subTotal;

            // Simpan detail
            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'barang_id' => $barang->id,
                'harga_jual' => $barangData['harga_jual'],
                'jumlah' => $barangData['jumlah'],
                'sub_total' => $subTotal,
            ]);

            // Kurangi stok
            $barang->decrement('stok', $barangData['jumlah']);
        }

        // Update total bayar
        $penjualan->update([
            'total_bayar' => $totalPenjualan,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil disimpan!');
    }
}
