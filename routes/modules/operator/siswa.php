<?php

use Illuminate\Support\Facades\Route;

Route::view('/data-siswa', 'operator.data-siswa')->name('siswa.index');
Route::view('/data-siswa/tambah', 'operator.tambah-siswa')->name('siswa.create');
Route::view('/data-siswa/{id}/edit', 'operator.edit-siswa')->name('siswa.edit');
