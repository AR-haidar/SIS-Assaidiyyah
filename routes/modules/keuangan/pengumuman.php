<?php

use Illuminate\Support\Facades\Route;

Route::view('/pengumuman', 'keuangan.pengumuman')->name('pengumuman.index');
Route::view('/pengumuman/tambah', 'keuangan.tambah-pengumuman')->name('pengumuman.create');
Route::view('/pengumuman/{id}/edit', 'keuangan.tambah-pengumuman')->name('pengumuman.edit');