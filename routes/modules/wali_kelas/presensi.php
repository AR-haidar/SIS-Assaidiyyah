<?php

use App\Http\Controllers\Presensi\PresensiSiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/presensi-absensi', [PresensiSiswaController::class, 'rekapWaliKelas'])
    ->name('presensi-absensi.index');

Route::prefix('presensi')->name('presensi.')->group(function (): void {
    Route::get('/siswa', [PresensiSiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [PresensiSiswaController::class, 'store'])->name('siswa.store');
});
