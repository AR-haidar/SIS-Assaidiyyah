<?php

use App\Http\Controllers\Siswa\TagihanSiswaController;
use Illuminate\Support\Facades\Route;

Route::prefix('tagihan')->name('tagihan.')->group(function (): void {
    Route::get('/', [TagihanSiswaController::class, 'index'])->name('index');
    Route::post('/{tagihan}/ajukan-pembayaran', [TagihanSiswaController::class, 'ajukanPembayaran'])->name('ajukan-pembayaran');
});
