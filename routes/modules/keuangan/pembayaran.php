<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'keuangan.dashboard')->name('dashboard');
Route::view('/pembayaran-siswa', 'keuangan.pembayaran-siswa')->name('pembayaran.index');
Route::view('/pembayaran-siswa/detail', 'keuangan.detail-pembayaran')->name('pembayaran.detail');
Route::view('/tagihan/tambah', 'keuangan.tambah-tagihan')->name('tagihan.create');