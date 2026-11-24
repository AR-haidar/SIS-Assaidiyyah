<?php

// routes/modules/kepala/umum.php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kepala\RekapController;
use App\Http\Controllers\SekolahController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'kepala'])->name('dashboard');
Route::get('/rekap-absensi', [RekapController::class, 'absensi'])->name('rekap_absensi.index');
Route::get('/data-sekolah', [SekolahController::class, 'showKepala'])->name('sekolah.show');
Route::view('/pengumuman', 'kepala-sekolah.pengumuman')->name('pengumuman.index');
