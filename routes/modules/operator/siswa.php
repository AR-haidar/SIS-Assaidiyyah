<?php

use App\Http\Controllers\Operator\SiswaController;
use Illuminate\Support\Facades\Route;

Route::resource('siswa', SiswaController::class)
    ->except(['show']);

// Alias URL kompatibel dengan navigasi sidebar/frontend
Route::get('data-siswa', [SiswaController::class, 'index']);
Route::get('data-siswa/tambah', [SiswaController::class, 'create']);
Route::get('data-siswa/{siswa}/edit', [SiswaController::class, 'edit']);

Route::prefix('siswa/{siswa}')->name('siswa.')->group(function (): void {
    Route::post('verifikasi', [SiswaController::class, 'verifikasi'])->name('verifikasi');
    Route::post('rombel', [SiswaController::class, 'aturRombel'])->name('rombel');
    Route::post('akun', [SiswaController::class, 'buatAkun'])->name('akun.buat');
    Route::post('akun/reset-pin', [SiswaController::class, 'resetPin'])->name('akun.reset-pin');
    Route::patch('akun/reset-pin', [SiswaController::class, 'resetPin']);
});
