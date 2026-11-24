<?php

use Illuminate\Support\Facades\Route;

Route::view('/rekap-absensi', 'humas.rekap-absensi')->name('rekap_absensi.index');
Route::view('/rekap-pembayaran', 'humas.rekap-pembayaran')->name('rekap_pembayaran.index');
Route::view('/data-sekolah', 'humas.data-sekolah')->name('sekolah.show');
Route::view('/pengumuman', 'humas.pengumuman')->name('pengumuman.index');
