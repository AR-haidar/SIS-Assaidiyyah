<?php

use App\Http\Controllers\Keuangan\JenisTagihanController;
use App\Http\Controllers\Keuangan\PembayaranController;
use App\Http\Controllers\Keuangan\TagihanController;
use Illuminate\Support\Facades\Route;

Route::prefix('jenis-tagihan')->name('jenis-tagihan.')->group(function (): void {
    Route::get('/', [JenisTagihanController::class, 'index'])->name('index');
    Route::post('/', [JenisTagihanController::class, 'store'])->name('store');
    Route::put('/{jenisTagihan}', [JenisTagihanController::class, 'update'])->name('update');
    Route::delete('/{jenisTagihan}', [JenisTagihanController::class, 'destroy'])->name('destroy');
});

Route::prefix('tagihan')->name('tagihan.')->group(function (): void {
    Route::get('/', [TagihanController::class, 'index'])->name('index');
    Route::post('/massal', [TagihanController::class, 'buatMassal'])->name('massal');
    Route::post('/{tagihan}/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
});

Route::prefix('pembayaran')->name('pembayaran.')->group(function (): void {
    Route::get('/', [PembayaranController::class, 'index'])->name('index');
    Route::post('/{pembayaran}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('verifikasi');
    Route::get('/{pembayaran}/bukti', [PembayaranController::class, 'bukti'])->name('bukti');
});
