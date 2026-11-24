<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => redirect()->route('siswa.dashboard'));
Route::view('/data-diri', 'siswa.data-diri')->name('profil.show');
Route::view('/absensi', 'siswa.absensi')->name('absensi.index');
Route::view('/tagihan-spp', 'siswa.tagihan-spp')->name('tagihan.index');
