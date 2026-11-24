<?php

use App\Http\Controllers\Presensi\PresensiPegawaiController;
use Illuminate\Support\Facades\Route;

Route::prefix('presensi-pegawai')->name('presensi-pegawai.')->group(function (): void {
    Route::get('/', [PresensiPegawaiController::class, 'index'])->name('index');
    Route::post('/', [PresensiPegawaiController::class, 'store'])->name('store');
    Route::post('/{presensiPegawai}/validasi', [PresensiPegawaiController::class, 'validasi'])->name('validasi');
});
