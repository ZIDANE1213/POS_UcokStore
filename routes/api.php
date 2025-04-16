<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::get('/grafik-transaksi', function () {
    // Ambil data penjualan per tanggal
    $penjualan = Penjualan::select(
            DB::raw('DATE(tgl_faktur) as tanggal'),
            DB::raw('COUNT(*) as jumlah_transaksi'),
            DB::raw('SUM(total_bayar) as total_pendapatan')
        )
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'ASC')
        ->get();

    // Ambil data pembelian per tanggal
    $pembelian = Pembelian::select(
            DB::raw('DATE(tanggal_masuk) as tanggal'),
            DB::raw('COUNT(*) as jumlah_transaksi'),
            DB::raw('SUM(total) as total_pengeluaran')
        )
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'ASC')
        ->get();

    return response()->json([
        'penjualan' => $penjualan,
        'pembelian' => $pembelian
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
