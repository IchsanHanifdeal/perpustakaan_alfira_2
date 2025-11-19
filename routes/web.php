<?php

use App\Models\Pengunjung;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PengembalianController;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/buku', [BukuController::class, 'index'])->name('buku');
    Route::post('/dashboard/buku/store', [BukuController::class, 'store'])->name('buku.store');
    Route::put('/dashboard/buku/{id}/update', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/dashboard/buku/{id}/delete', [BukuController::class, 'destroy'])->name('buku.delete');

    Route::get('/dashboard/pengunjung', [PengunjungController::class, 'index'])->name('pengunjung');
    Route::post('/dashboard/pengunjung/store', [PengunjungController::class, 'store'])->name('pengunjung.store');
    Route::put('/dashboard/pengunjung/{id}/update', [PengunjungController::class, 'update'])->name('pengunjung.update');
    Route::delete('/dashboard/pengunjung/{id}/delete', [PengunjungController::class, 'destroy'])->name('pengunjung.delete');

    Route::get('/dashboard/kunjungan', [PengunjungController::class, 'kunjungan'])->name('kunjungan');
    Route::post('/dashboard/kunjungan/store', [PengunjungController::class, 'kunjungan_store'])->name('kunjungan.store');

    Route::get('/dashboard/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::post('/dashboard/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/dashboard/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian');
    Route::post('/dashboard/pengembalian/store', [PengembalianController::class, 'store'])->name('pengembalian.store');
});
