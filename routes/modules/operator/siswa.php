<?php

use App\Http\Controllers\Operator\SiswaController;
use Illuminate\Support\Facades\Route;

Route::prefix('siswa')->name('siswa.')->group(function (): void {
    Route::get('/', [SiswaController::class, 'index'])->name('index');
    Route::get('/tambah', [SiswaController::class, 'create'])->name('create');
    Route::post('/', [SiswaController::class, 'store'])->name('store');
    Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('edit');
    Route::put('/{siswa}', [SiswaController::class, 'update'])->name('update');
    Route::post('/{siswa}/verifikasi', [SiswaController::class, 'verifikasi'])->name('verifikasi');
    Route::post('/{siswa}/rombel', [SiswaController::class, 'aturRombel'])->name('rombel');
    Route::post('/{siswa}/akun', [SiswaController::class, 'buatAkun'])->name('akun.buat');
    Route::post('/{siswa}/reset-pin', [SiswaController::class, 'resetPin'])->name('akun.reset-pin');
    Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('destroy');
});
