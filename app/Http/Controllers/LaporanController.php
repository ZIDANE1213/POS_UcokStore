<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function laporanPenjualan()
    {
        $penjualans = Penjualan::with('detailPenjualan.barang') // jika relasi dengan barang ada
            ->orderBy('created_at', 'desc')
            ->get();

        // return $penjualans;

        return view('admin.laporan_Penjualan.index', compact('penjualans'));
    }
    public function exportPdf()
    {
        $penjualans = Penjualan::with('detailPenjualan.barang')->get();

        $pdf = Pdf::loadView('admin.laporan_Penjualan.penjualan_pdf', compact('penjualans'))
                  ->setPaper('A4', 'landscape');

        return $pdf->download('laporan_penjualan.pdf');
    }
}
