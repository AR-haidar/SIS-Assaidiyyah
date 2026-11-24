<?php

use App\Http\Controllers\PerpustakaanDigitalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('perpustakaan-digital')
    ->name('publik.perpustakaan.')
    ->controller(PerpustakaanDigitalController::class)
    ->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::get('/buku/{bookId}', 'detail')->whereNumber('bookId')->name('buku.detail');
        Route::get('/buku/{bookId}/baca', 'baca')->whereNumber('bookId')->name('buku.baca');
        Route::get('/buku/{bookId}/sampul', 'sampul')->whereNumber('bookId')->name('buku.sampul');
        Route::get('/buku/{bookId}/naskah', 'naskah')->whereNumber('bookId')->name('buku.naskah');
    });
