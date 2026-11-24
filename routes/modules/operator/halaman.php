<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => redirect()->route('operator.dashboard'));
Route::view('/data-siswa', 'operator.data-siswa')->name('siswa.index');
Route::view('/data-siswa/tambah', 'operator.tambah-siswa')->name('siswa.create');
Route::view('/data-guru', 'operator.data-guru')->name('pegawai.index');
Route::view('/data-guru/tambah', 'operator.tambah-guru')->name('pegawai.create');
Route::view('/data-sekolah', 'operator.data-sekolah')->name('sekolah.edit');
Route::view('/pengumuman', 'operator.pengumuman')->name('pengumuman.index');
Route::view('/pengumuman/tambah', 'operator.tambah-pengumuman')->name('pengumuman.create');
