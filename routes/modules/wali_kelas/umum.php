<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'wali-kelas.dashboard')->name('dashboard');
Route::view('/data-siswa', 'wali-kelas.data-siswa')->name('siswa.index');
Route::view('/presensi-absensi', 'wali-kelas.presensi-absensi')->name('presensi.index');
