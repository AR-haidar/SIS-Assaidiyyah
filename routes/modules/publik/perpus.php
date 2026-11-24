<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('perpustakaan-digital')
    ->name('publik.perpustakaan.')
    ->group(function (): void {
        Route::view('/', 'perpustakaan-digital')->name('index');
        Route::view('/buku/{bookId}', 'detail-buku')->name('buku.detail');
        Route::view('/buku/{bookId}/baca', 'halaman-baca-buku')->name('buku.baca');
    });
