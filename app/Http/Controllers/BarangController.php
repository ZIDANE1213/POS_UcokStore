<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang.
     */
    public function index()
    {
        $barang = Barang::with('kategori', 'user')->get();
        return view('admin.barang.index', compact('barang'));
    }

    /**
     * Menampilkan form tambah barang.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.barang.create', compact('kategori'));
    }

    /**
     * Menyimpan data barang ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_barang' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Generate kode barang otomatis berdasarkan jumlah barang yang ada
        $lastBarang = Barang::orderBy('id', 'desc')->first();
$lastNumber = $lastBarang ? ((int)substr($lastBarang->kode_barang, 3)) : 0;
$newCode = 'BRG' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);


        // Upload gambar jika ada
        $gambarPath = $request->hasFile('gambar') ? $request->file('gambar')->store('barang', 'public') : null;

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        Barang::create([
            'kode_barang' => $newCode,
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'harga_beli' => 0,
            'harga_jual' => 0,
            'stok' => 0,
            'stok_minimal' => 5,
            'gambar' => $gambarPath,
            'ditarik' => 1,
            'user_id' => Auth::id(),
        ]);





        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit barang.
     */
    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    /**
     * Memperbarui data barang.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_barang' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'stok_minimal' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Hapus gambar lama jika ada yang baru diunggah
        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $gambarPath = $request->file('gambar')->store('barang', 'public');
        } else {
            $gambarPath = $barang->gambar;
        }

        $barang->update([
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'harga_jual' => $request->harga_jual,
            'stok_minimal' => $request->stok_minimal,
            'gambar' => $gambarPath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Menghapus barang dari database.
     */
    public function destroy(Barang $barang)
    {
        // Hapus gambar jika ada
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
