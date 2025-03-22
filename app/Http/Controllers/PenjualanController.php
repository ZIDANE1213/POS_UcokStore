<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
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
        $request->validate([
            'tgl_faktur' => 'required|date',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.harga_jual' => 'required|numeric|min:1',
            'barang.*.jumlah' => 'required|integer|min:1',
        ]);

        // Generate no_faktur otomatis
        $noFaktur = 'FTR' . str_pad(Penjualan::count() + 1, 5, '0', STR_PAD_LEFT);

        // Simpan transaksi penjualan
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

        // Simpan detail penjualan dan update stok barang
        foreach ($request->barang as $barangData) {
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

            // Kurangi stok barang
            $barang->update([
                'stok' => $barang->stok - $barangData['jumlah'],
            ]);
        }

        // Update total transaksi penjualan
        $penjualan->update(['total_bayar' => $totalPenjualan]);

        return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil disimpan!');
    }

    public function show(Penjualan $penjualan)
    {
        return view('admin.penjualan.show', compact('penjualan'));
    }
}