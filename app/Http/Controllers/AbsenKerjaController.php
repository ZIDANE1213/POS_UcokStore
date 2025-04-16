<?php

namespace App\Http\Controllers;

use App\Models\AbsenKerja;
use Barryvdh\DomPDF\Facade\Pdf; // Ganti ini
// atau
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsenExport;
use App\Imports\AbsenImport;


class AbsenKerjaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AbsenKerja::with('user')->latest()->get();

            return DataTables::of($data)
                ->addColumn('aksi', function ($row) {
                    $btn = '<button class="btn btn-warning btn-sm edit-btn" onclick="editData(' . $row->id . ')">Edit</button>';
                    $btn .= ' <button class="btn btn-danger btn-sm" onclick="hapusData(' . $row->id . ')">Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['aksi', 'status_masuk', 'waktu_akhir_kerja'])
                ->make(true);
        }

        $users = User::all();
        return view('admin.absen.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status_masuk' => 'required|in:masuk,sakit,cuti',
            'tanggal_masuk' => 'required|date',
        ]);

        $data = [
            'user_id' => $request->user_id,
            'status_masuk' => $request->status_masuk,
            'tanggal_masuk' => $request->tanggal_masuk,
            'waktu_mulai_kerja' => $request->status_masuk == 'masuk' ? now()->format('H:i:s') : null,
            'waktu_akhir_kerja' => in_array($request->status_masuk, ['sakit', 'cuti']) ? '00:00:00' : null
        ];

        AbsenKerja::create($data);
        return response()->json(['success' => true]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:absen_kerjas,id',
            'status_masuk' => 'required|in:masuk,sakit,cuti',
            'tanggal_masuk' => 'required|date',
        ]);

        $absen = AbsenKerja::findOrFail($request->id);

        $data = [
            'status_masuk' => $request->status_masuk,
            'tanggal_masuk' => $request->tanggal_masuk,
            'waktu_akhir_kerja' => in_array($request->status_masuk, ['sakit', 'cuti']) ? '00:00:00' : $absen->waktu_akhir_kerja
        ];

        $absen->update($data);
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

    public function exportExcel()
    {
        return Excel::download(new AbsenExport, 'absen-kerja.xlsx');
    }

    public function exportPdf()
    {
        $data = AbsenKerja::with('user')->latest()->get();
        $pdf = PDF::loadView('admin.absen.export_pdf', compact('data'));
        return $pdf->download('absen-kerja.pdf');
    }

    public function importExcel(Request $request)
    {
        // Validasi file yang di-upload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Menggunakan class AbsenImport untuk mengimpor file
        try {
            Excel::import(new AbsenImport, $request->file('file'));
            return redirect()->route('absen.index')->with('success', 'Data absensi berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('absen.index')->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }
    public function get($id)
    {
        return AbsenKerja::findOrFail($id);
    }
}
