<?php

use App\Http\Controllers\Humas\KontenLandingController;
use Illuminate\Support\Facades\Route;

Route::get('/data-sekolah', [KontenLandingController::class, 'dataSekolah'])->name('sekolah.show');

Route::prefix('prestasi')->name('prestasi.')->group(function (): void {
    Route::get('/', [KontenLandingController::class, 'prestasiIndex'])->name('index');
    Route::post('/', [KontenLandingController::class, 'prestasiStore'])->name('store');
    Route::delete('/{prestasi}', [KontenLandingController::class, 'prestasiDestroy'])->name('destroy');
});

Route::prefix('guru')->name('guru.')->group(function (): void {
    Route::get('/', [KontenLandingController::class, 'guruIndex'])->name('index');
    Route::get('/tambah', [KontenLandingController::class, 'guruCreate'])->name('create');
    Route::post('/', [KontenLandingController::class, 'guruStore'])->name('store');
    Route::delete('/{profil}', [KontenLandingController::class, 'guruDestroy'])->name('destroy');
});
