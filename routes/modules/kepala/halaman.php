<?php

use Illuminate\Support\Facades\Route;

Route::view('/rekap-absensi', 'kepala-sekolah.rekap-absensi')->name('rekap_absensi.index');
Route::view('/rekap-pembayaran', 'kepala-sekolah.rekap-pembayaran')->name('rekap_pembayaran.index');
Route::view('/data-sekolah', 'kepala-sekolah.data-sekolah')->name('sekolah.show');
Route::view('/pengumuman', 'kepala-sekolah.pengumuman')->name('pengumuman.index');
