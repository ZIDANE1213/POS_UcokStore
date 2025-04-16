<?php

namespace App\Http\Controllers;

use App\Models\AbsenKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
// use App\Http\Controllers\Absen;
use App\Models\Absen;


class AbsenKerjaController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Absen::with('user')->latest()->get();


        return DataTables::of($data)
            ->addColumn('aksi', function($row) {
                return '<button class="btn btn-info">Edit</button>';
            })
            ->make(true);
    }

    return view('admin.absen.index'); // <- Pastikan view-nya sesuai
}


    public function store(Request $request)
    {
        AbsenKerja::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $absen = AbsenKerja::findOrFail($request->id);
        $absen->update($request->all());
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        AbsenKerja::findOrFail($request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function ubahStatus(Request $request)
    {
        $absen = AbsenKerja::findOrFail($request->id);
        $absen->status_masuk = $request->status;
        if (in_array($request->status, ['sakit', 'cuti'])) {
            $absen->waktu_akhir_kerja = '00:00:00';
        }
        $absen->save();
        return response()->json(['success' => true]);
    }

    public function selesaiKerja(Request $request)
    {
        $absen = AbsenKerja::findOrFail($request->id);
        $absen->waktu_akhir_kerja = now()->format('H:i:s');
        $absen->save();
        return response()->json(['success' => true]);
    }
}
