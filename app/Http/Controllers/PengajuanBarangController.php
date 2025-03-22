<?php

namespace App\Http\Controllers;

use App\Models\PengajuanBarang;
use Illuminate\Http\Request;

class PengajuanBarangController extends Controller
{
     // Tampilkan form pengajuan barang
     public function create()
     {
         return view('pengajuan.create');
     }
 
     // Simpan pengajuan barang
     public function store(Request $request)
     {
         $request->validate([
             'nama_barang' => 'required|string|max:255',
             'jumlah' => 'required|integer|min:1',
             'keterangan' => 'nullable|string',
         ]);
 
         PengajuanBarang::create([
             'user_id' => auth()->id(),
             'nama_barang' => $request->nama_barang,
             'jumlah' => $request->jumlah,
             'keterangan' => $request->keterangan,
             'status' => 'pending',
         ]);
 
         return redirect()->route('pengajuan.member')->with('success', 'Pengajuan barang berhasil dikirim.');
     }
 
     // Tampilkan daftar pengajuan barang oleh member
     public function index()
     {
         $pengajuan = PengajuanBarang::where('user_id', auth()->id())->get();
         return view('pengajuan.index', compact('pengajuan'));
     }
 
     // Hapus pengajuan barang
     public function destroy($id)
     {
         $pengajuan = PengajuanBarang::where('id', $id)
                                     ->where('user_id', auth()->id())
                                     ->firstOrFail();
 
         $pengajuan->delete();
 
         return redirect()->route('pengajuan.index')->with('success', 'Pengajuan barang berhasil dihapus.');
     }
    public function approve($id)
{
    $pengajuan = PengajuanBarang::findOrFail($id);
    $pengajuan->update(['status' => 'approved']);

    return redirect()->back()->with('success', 'Pengajuan barang telah disetujui.');
}

public function reject($id)
{
    $pengajuan = PengajuanBarang::findOrFail($id);
    $pengajuan->update(['status' => 'rejected']);

    return redirect()->back()->with('error', 'Pengajuan barang telah ditolak.');
}
public function indexMember()
{
    $pengajuan = PengajuanBarang::where('user_id', auth()->id())->get();
    return view('pengajuan.index', compact('pengajuan'));
}

   
}

