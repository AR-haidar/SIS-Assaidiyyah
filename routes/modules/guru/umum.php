<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'guru.dashboard')->name('dashboard');
Route::view('/profile', 'guru.profile')->name('profil.edit');
Route::view('/rekap-absensi', 'guru.rekap-absensi')->name('rekap_absensi.index');