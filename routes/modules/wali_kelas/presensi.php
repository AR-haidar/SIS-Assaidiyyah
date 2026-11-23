<?php

use App\Http\Controllers\Presensi\PresensiPegawaiController;
use App\Http\Controllers\Presensi\PresensiSiswaController;
use Illuminate\Support\Facades\Route;

Route::prefix('presensi')->name('presensi.')->group(function (): void {
    Route::get('/siswa', [PresensiSiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [PresensiSiswaController::class, 'store'])->name('siswa.store');

    Route::get('/pegawai', [PresensiPegawaiController::class, 'index'])->name('pegawai.index');
    Route::post('/pegawai', [PresensiPegawaiController::class, 'store'])->name('pegawai.store');
});
