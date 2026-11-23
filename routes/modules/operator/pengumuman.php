<?php

use Illuminate\Support\Facades\Route;

Route::view('/pengumuman', 'operator.pengumuman')->name('pengumuman.index');
Route::view('/pengumuman/tambah', 'operator.tambah-pengumuman')->name('pengumuman.create');
