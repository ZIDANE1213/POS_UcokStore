<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar transaksi pembelian
     */
    public function index()
    {
        $pembelian = Pembelian::with('pemasok', 'user')->get();
        return view('admin.pembelian.index', compact('pembelian'));
    }

    /**
     * Menampilkan form tambah pembelian
     */
    public function create()
    {
        $pemasoks = Pemasok::all();
        $barangs = Barang::all();
        return view('admin.pembelian.create', compact('pemasoks', 'barangs'));
    }

    /**
     * Menyimpan data pembelian
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'pemasok_id' => 'required|exists:pemasok,id',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.harga_beli' => 'required|numeric|min:1',
            'barang.*.jumlah' => 'required|integer|min:1',
        ]);

        // Generate kode_masuk otomatis
        $kodeMasuk = 'PMB' . str_pad(Pembelian::count() + 1, 5, '0', STR_PAD_LEFT);

        // Simpan transaksi pembelian
        $pembelian = Pembelian::create([
            'kode_masuk' => $kodeMasuk,
            'tanggal_masuk' => $request->tanggal_masuk,
            'total' => 0,
            'pemasok_id' => $request->pemasok_id,
            'user_id' => Auth::id(),
        ]);

        $totalPembelian = 0;

        // Simpan detail pembelian dan update harga jual barang
        foreach ($request->barang as $barangData) {
            $barang = Barang::findOrFail($barangData['id']);
            $subTotal = $barangData['harga_beli'] * $barangData['jumlah'];
            $totalPembelian += $subTotal;

            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barang->id,
                'harga_beli' => $barangData['harga_beli'],
                'jumlah' => $barangData['jumlah'],
                'sub_total' => $subTotal,
            ]);

            // Update harga jual barang (20% lebih tinggi dari harga beli)
            $barang->update([
                'harga_beli' => $barangData['harga_beli'],
                'harga_jual' => $barangData['harga_beli'] * 1.2, // Tambah 20%
                'stok' => $barang->stok + $barangData['jumlah'],
            ]);
        }

        // Update total transaksi pembelian
        $pembelian->update(['total' => $totalPembelian]);

        return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil disimpan!');
    }

    public function show(Pembelian $pembelian)
    {
        return view('admin.pembelian.show', compact('pembelian'));
    }
}
