<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\ObatController;
use App\Http\Controllers\Api\PasienController;
use App\Http\Controllers\Api\PemeriksaanController;
use App\Http\Controllers\Api\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        Route::get('pasien/search', [PasienController::class, 'search']);
        Route::apiResource('pasien', PasienController::class);

        Route::get('pendaftaran/antrian', [PendaftaranController::class, 'antrian']);
        Route::apiResource('pendaftaran', PendaftaranController::class)->only(['index', 'show', 'store']);

        Route::get('pemeriksaan/daftar-periksa', [PemeriksaanController::class, 'daftarPeriksa']);
        Route::apiResource('pemeriksaan', PemeriksaanController::class)->only(['index', 'show', 'store']);

        Route::get('obat/hampir-habis', [ObatController::class, 'hampirHabis']);
        Route::apiResource('obat', ObatController::class)->only(['index', 'show']);

        Route::get('laporan', [LaporanController::class, 'index']);
    });
});
