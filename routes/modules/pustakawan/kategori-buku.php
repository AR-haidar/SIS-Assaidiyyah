<?php

use App\Http\Controllers\Pustakawan\KategoriBukuController;
use Illuminate\Support\Facades\Route;

Route::prefix('kategori-buku')->name('kategori-buku.')->group(function (): void {
    Route::get('/', [KategoriBukuController::class, 'index'])->name('index');
    Route::view('/tambah', 'perpustakaan.tambah-kategori-buku')->name('create');
    Route::post('/', [KategoriBukuController::class, 'store'])->name('store');
    Route::delete('/{kategoriBuku}', [KategoriBukuController::class, 'destroy'])
        ->whereNumber('kategoriBuku')
        ->name('destroy');
});
