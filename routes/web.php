<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root Redirect
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Must be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fitur Export, Import, dan Template Buku
    Route::get('/buku/export-import', [BukuController::class, 'exportImport'])->name('buku.export_import');
    Route::get('/buku/export/excel', [BukuController::class, 'exportExcel'])->name('buku.export.excel');
    Route::get('/buku/export/pdf', [BukuController::class, 'exportPdf'])->name('buku.export.pdf');
    Route::get('/buku/template-import', [BukuController::class, 'downloadTemplate'])->name('buku.template.download');
    Route::post('/buku/import/excel', [BukuController::class, 'importExcel'])->name('buku.import.excel');
    Route::resource('buku', BukuController::class);

    // Fitur Export Peminjam
    Route::get('/peminjam/export/excel', [PeminjamController::class, 'exportExcel'])->name('peminjam.export.excel');
    Route::get('/peminjam/export/pdf', [PeminjamController::class, 'exportPdf'])->name('peminjam.export.pdf');
    Route::resource('peminjam', PeminjamController::class);
    // Fitur Export Pengunjung
    Route::get('/pengunjung/export/excel', [PengunjungController::class, 'exportExcel'])->name('pengunjung.export.excel');
    Route::get('/pengunjung/export/pdf', [PengunjungController::class, 'exportPdf'])->name('pengunjung.export.pdf');
    Route::resource('pengunjung', PengunjungController::class);

    // Fitur Laporan & Export Laporan Bulanan (Sirkulasi & Evaluasi)
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store']);
    Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])
         ->name('peminjaman.kembalikan');

    // Fitur Profil Akun Pengguna
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});
