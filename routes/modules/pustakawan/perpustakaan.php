<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'perpustakaan.dashboard')->name('dashboard');
Route::view('/data-buku', 'perpustakaan.data-buku')->name('buku.index');
Route::view('/data-buku/tambah', 'perpustakaan.tambah-data-buku')->name('buku.create');
Route::view('/data-buku/edit', 'perpustakaan.edit-data-buku')->name('buku.edit');
