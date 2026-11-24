<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => redirect()->route('wali_kelas.dashboard'));
Route::view('/data-siswa', 'wali-kelas.data-siswa')->name('siswa.index');
Route::view('/presensi-absensi', 'wali-kelas.presensi-absensi')->name('presensi.index');
Route::view('/keuangan-spp', 'wali-kelas.keuangan-spp')->name('spp.index');
