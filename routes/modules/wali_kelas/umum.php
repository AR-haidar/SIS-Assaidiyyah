<?php

// routes/modules/wali_kelas/umum.php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Presensi\PresensiSiswaController;
use App\Http\Controllers\WaliKelas\SiswaRombelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'waliKelas'])->name('dashboard');
Route::get('/data-siswa', [SiswaRombelController::class, 'index'])->name('siswa.index');
Route::get('/presensi-absensi', [PresensiSiswaController::class, 'rekapWaliKelas'])->name('presensi.index');
