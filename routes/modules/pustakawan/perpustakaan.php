<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'perpustakaan.dashboard')->name('dashboard');
Route::view('/data-buku', 'perpustakaan.data-buku')->name('buku.index');
Route::view('/data-buku/tambah', 'perpustakaan.tambah-data-buku')->name('buku.create');
Route::view('/data-buku/edit', 'perpustakaan.edit-data-buku')->name('buku.edit');
Route::view('/kategori-buku', 'perpustakaan.kategori-buku')->name('kategori.index');
Route::view('/kategori-buku/tambah', 'perpustakaan.tambah-kategori-buku')->name('kategori.create');