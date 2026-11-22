<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'siswa.dashboard')->name('dashboard');
Route::view('/data-diri', 'siswa.data-diri')->name('profil.show');
Route::view('/absensi', 'siswa.absensi')->name('absensi.index');