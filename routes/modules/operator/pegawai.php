<?php

use Illuminate\Support\Facades\Route;

Route::view('/data-guru', 'operator.data-guru')->name('pegawai.index');
Route::view('/data-guru/tambah', 'operator.tambah-guru')->name('pegawai.create');