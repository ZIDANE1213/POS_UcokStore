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
<<<<<<< HEAD


Route::get('/', function () {
    return view('auth.login');
=======
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PengajuanBarangController;
use App\Models\Penjualan;
use App\Models\Transaction;


Route::get('/api/transactions', function () {
    $Penjualan = Penjualan::selectRaw('DATE(created_at) as date, COUNT(id) as count, SUM(amount) as revenue')
        ->groupBy('date')
        ->get();

    return response()->json($Penjualan);
>>>>>>> e5d46b5 (Menambahkan project baru)
});

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
<<<<<<< HEAD


// Role -> Admin
=======
Route::get('/pengajuan', [PengajuanBarangController::class, 'index'])->name('pengajuan.index');
Route::get('/pengajuan/create', [PengajuanBarangController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PengajuanBarangController::class, 'store'])->name('pengajuan.store');
Route::delete('/pengajuan', [PengajuanBarangController::class, 'destroy'])->name('pengajuan.destroy');
Route::get('/member/pengajuan', [PengajuanBarangController::class, 'create'])->name('member.pengajuan.create');
Route::post('/member/pengajuan', [PengajuanBarangController::class, 'store'])->name('member.pengajuan.store');
Route::get('/pengajuan-saya', [PengajuanBarangController::class, 'indexMember'])->name('pengajuan.member');
Route::get('/pengajuan/create', [PengajuanBarangController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan/store', [PengajuanBarangController::class, 'store'])->name('pengajuan.store');
    Route::delete('/pengajuan/{id}/delete', [PengajuanBarangController::class, 'destroy'])->name('pengajuan.destroy');
    Route::patch('/pengajuan/{id}/approve', [PengajuanBarangController::class, 'approve'])->name('pengajuan.approve');
    Route::patch('/pengajuan/{id}/reject', [PengajuanBarangController::class, 'reject'])->name('pengajuan.reject');
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'role:member'])->group(function () {
    // Dashboard Member
    Route::get('dashboard-member', [MemberController::class, 'index'])->name('dashboard-member');
   
    // Fitur Pengajuan Barang
    
});
//role member
Route::middleware(['auth', 'role:admin,member'])->group(function () {
    
});



// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');  

// Role -> Admin


>>>>>>> e5d46b5 (Menambahkan project baru)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('dashboard.admin');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}/update', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}/delete', [KategoriController::class, 'destroy'])->name('kategori.destroy');

<<<<<<< HEAD

=======
>>>>>>> e5d46b5 (Menambahkan project baru)
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}/update', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}/delete', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');

    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
<<<<<<< HEAD
=======

    

Route::middleware(['auth'])->group(function () {
    
>>>>>>> e5d46b5 (Menambahkan project baru)
});

// Role -> Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/dashboard-kasir', [KasirController::class, 'index'])->name('dashboard.kasir');
});

// Role -> Manajer
Route::middleware(['auth', 'role:manajer'])->group(function () {
    Route::get('/dashboard-manajer', [ManajerController::class, 'index'])->name('dashboard.manajer');
});


Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
<<<<<<< HEAD
=======
});
>>>>>>> e5d46b5 (Menambahkan project baru)
});