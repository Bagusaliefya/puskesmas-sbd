<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/antrian', [QueueController::class, 'display'])->name('antrian.display');
Route::get('/antrian/json', [QueueController::class, 'json'])->name('antrian.json')->middleware('throttle:60,1');
Route::get('/cek-resep', [DaftarController::class, 'cekResep'])->name('cek-resep');
Route::post('/cek-resep', [DaftarController::class, 'cariResep'])->name('cek-resep.cari')->middleware('throttle:10,1');
Route::get('/', [DaftarController::class, 'landing'])->name('landing');
Route::get('/daftar', [DaftarController::class, 'form'])->name('daftar.form');
Route::post('/daftar', [DaftarController::class, 'submit'])->name('daftar.submit');
Route::get('/daftar/sukses/{id}', [DaftarController::class, 'sukses'])->name('daftar.sukses');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('dashboard')->middleware(['auth', 'active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [DashboardController::class, 'search'])->name('dashboard.search');

    Route::middleware('role:admin')->group(function () {
        Route::resource('pegawai', PegawaiController::class);
        Route::get('obat/csv/export', [ObatController::class, 'exportCsv'])->name('obat.csv');
        Route::post('obat/csv/import', [ObatController::class, 'importCsv'])->name('obat.import');
        Route::resource('obat', ObatController::class);
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('users', [UserController::class, 'index'])->name('user.index');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('user.toggle-active');
    });

    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('pasien/csv/export', [PasienController::class, 'exportCsv'])->name('pasien.csv');
        Route::post('pasien/csv/import', [PasienController::class, 'importCsv'])->name('pasien.import');
        Route::resource('pasien', PasienController::class);
    });

    Route::middleware('role:petugas')->prefix('petugas')->group(function () {
        Route::get('pendaftaran', [PendaftaranController::class, 'index'])->name('petugas.pendaftaran.index');
        Route::get('pendaftaran/create', [PendaftaranController::class, 'create'])->name('petugas.pendaftaran.create');
        Route::post('pendaftaran', [PendaftaranController::class, 'store'])->name('petugas.pendaftaran.store');
        Route::get('pendaftaran/{id}', [PendaftaranController::class, 'show'])->name('petugas.pendaftaran.show');
        Route::get('pendaftaran/{id}/edit', [PendaftaranController::class, 'edit'])->name('petugas.pendaftaran.edit');
        Route::put('pendaftaran/{id}', [PendaftaranController::class, 'update'])->name('petugas.pendaftaran.update');
        Route::delete('pendaftaran/{id}', [PendaftaranController::class, 'destroy'])->name('petugas.pendaftaran.destroy');
        Route::post('pendaftaran/{id}/panggil', [PendaftaranController::class, 'panggil'])->name('petugas.pendaftaran.panggil');
    });

    Route::middleware('role:dokter')->prefix('dokter')->group(function () {
        Route::get('pemeriksaan', [PemeriksaanController::class, 'index'])->name('dokter.pemeriksaan.index');
        Route::get('pemeriksaan/{id}/create', [PemeriksaanController::class, 'create'])->name('dokter.pemeriksaan.create');
        Route::post('pemeriksaan', [PemeriksaanController::class, 'store'])->name('dokter.pemeriksaan.store');
        Route::get('pemeriksaan/{id}', [PemeriksaanController::class, 'show'])->name('dokter.pemeriksaan.show');
        Route::get('pemeriksaan/riwayat/{idPasien}', [PemeriksaanController::class, 'riwayatPasien'])->name('dokter.pemeriksaan.riwayat');
        Route::get('pemeriksaan/{id}/edit', [PemeriksaanController::class, 'edit'])->name('dokter.pemeriksaan.edit');
        Route::put('pemeriksaan/{id}', [PemeriksaanController::class, 'update'])->name('dokter.pemeriksaan.update');
        Route::delete('pemeriksaan/{id}', [PemeriksaanController::class, 'destroy'])->name('dokter.pemeriksaan.destroy');
        Route::get('resep/{pemeriksaanId}/create', [ResepController::class, 'create'])->name('dokter.resep.create');
        Route::post('resep', [ResepController::class, 'store'])->name('dokter.resep.store');
        Route::get('resep/{id}', [ResepController::class, 'show'])->name('dokter.resep.show');
        Route::delete('resep/{id}', [ResepController::class, 'destroy'])->name('dokter.resep.destroy');
    });
});
