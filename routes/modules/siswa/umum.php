<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Presensi\PresensiSiswaController;
use App\Http\Controllers\Siswa\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'siswa'])->name('dashboard');
Route::get('/data-diri', [ProfilController::class, 'show'])->name('profil.show');
Route::get('/absensi', [PresensiSiswaController::class, 'riwayatSiswa'])->name('absensi.index');
