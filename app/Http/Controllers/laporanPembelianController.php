<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPembelianController extends Controller
{
    public function laporanPembelian()
    {
        $pembelians = Pembelian::with('detailPembelian.barang')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.laporan_Pembelian.index', compact('pembelians'));
    }

    public function exportPdf()
    {
        $pembelians = Pembelian::with('detailPembelian.barang')->get();

        $pdf = Pdf::loadView('admin.laporan_Pembelian.pembelian_pdf', compact('pembelians'))
                  ->setPaper('A4', 'landscape');

        return $pdf->download('laporan_pembelian.pdf');
    }
}
