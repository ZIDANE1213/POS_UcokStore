<?php

use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\laporanPembelianController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PengajuanBarangController;
use App\Models\Penjualan;
use App\Models\Transaction;
use App\Http\Controllers\AbsenKerjaController;




// Route default
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/laporan/penjualan', [LaporanController::class, 'laporanPenjualan'])->name('laporan.penjualan');
});




// API untuk grafik transaksi
Route::get('/api/transactions', function () {
    $Penjualan = Penjualan::selectRaw('DATE(created_at) as date, COUNT(id) as count, SUM(amount) as revenue')
        ->groupBy('date')
        ->get();

    return response()->json($Penjualan);
});

// Auth
Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Pengajuan Barang
Route::get('/pengajuan', [PengajuanBarangController::class, 'index'])->name('pengajuan.index');
Route::get('/pengajuan/create', [PengajuanBarangController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PengajuanBarangController::class, 'store'])->name('pengajuan.store');
Route::delete('/pengajuan', [PengajuanBarangController::class, 'destroy'])->name('pengajuan.destroy');
Route::get('/member/pengajuan', [PengajuanBarangController::class, 'create'])->name('member.pengajuan.create');
Route::post('/member/pengajuan', [PengajuanBarangController::class, 'store'])->name('member.pengajuan.store');
Route::get('/pengajuan-saya', [PengajuanBarangController::class, 'indexMember'])->name('pengajuan.member');
Route::post('/pengajuan/store', [PengajuanBarangController::class, 'store'])->name('pengajuan.store');
Route::delete('/pengajuan/{id}/delete', [PengajuanBarangController::class, 'destroy'])->name('pengajuan.destroy');
Route::patch('/pengajuan/{id}/approve', [PengajuanBarangController::class, 'approve'])->name('pengajuan.approve');
Route::patch('/pengajuan/{id}/reject', [PengajuanBarangController::class, 'reject'])->name('pengajuan.reject');

// Middleware Role: Member
Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('dashboard-member', [MemberController::class, 'index'])->name('dashboard-member');
    // Fitur tambahan untuk member bisa ditambahkan di sini
});

// Middleware Role: Admin & Member
Route::middleware(['auth', 'role:admin,member'])->group(function () {
    // Kosong, bisa diisi fitur bersama admin dan member
});

// Middleware Role: Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('dashboard.admin');

    // Kategori
    Route::resource('kategori', KategoriController::class);
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}/update', [KategoriController::class, 'update'])->name('kategori.update');


    // Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}/update', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}/delete', [BarangController::class, 'destroy'])->name('barang.destroy');

    // Pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::get('/laporan/pembelian', [laporanPembelianController::class, 'laporanPembelian'])->name('laporan_Pembelian.index');
    Route::get('/laporan/pembelian/pdf', [laporanPembelianController::class, 'exportPdf'])->name('laporan.pembelian.pdf');


    // Penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/laporan/penjualan', [LaporanController::class, 'laporanPenjualan'])->name('laporan.index');
    Route::get('/laporan/penjualan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.Penjualan.pdf');
    Route::resource('penjualan', PenjualanController::class);

       // absensi
       Route::get('/absen', [AbsenKerjaController::class, 'index'])->name('absen.index');
       Route::post('/absen/store', [AbsenKerjaController::class, 'store'])->name('absen.store');
       Route::post('/absen/update', [AbsenKerjaController::class, 'update'])->name('absen.update');
       Route::post('/absen/delete', [AbsenKerjaController::class, 'delete'])->name('absen.delete');
       Route::post('/absen/ubah-status', [AbsenKerjaController::class, 'ubahStatus'])->name('absen.ubahStatus');
       Route::post('/absen/selesai-kerja', [AbsenKerjaController::class, 'selesaiKerja'])->name('absen.selesaiKerja');
       

}); // ← Tambahkan ini untuk menutup middleware(['auth', 'role:admin'])


// Middleware Role: Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/dashboard-kasir', [KasirController::class, 'index'])->name('dashboard.kasir');
});

// Middleware Role: Manajer
Route::middleware(['auth', 'role:manajer'])->group(function () {
    Route::get('/dashboard-manajer', [ManajerController::class, 'index'])->name('dashboard.manajer');
});

// Route Unauthorized
Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
});
