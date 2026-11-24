<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kepala\RekapController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\SekolahController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'kepala'])->name('dashboard');
Route::get('/rekap-absensi', [RekapController::class, 'absensi'])->name('rekap_absensi.index');
Route::get('/data-sekolah', [SekolahController::class, 'showKepala'])->name('sekolah.show');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
