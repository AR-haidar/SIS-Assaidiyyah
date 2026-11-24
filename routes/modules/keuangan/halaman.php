<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => redirect()->route('keuangan.dashboard'));
Route::view('/pembayaran-siswa', 'keuangan.pembayaran-siswa')->name('pembayaran.index');
Route::view('/pembayaran-siswa/detail', 'keuangan.detail-pembayaran')->name('pembayaran.detail');
Route::view('/tagihan/tambah', 'keuangan.tambah-tagihan')->name('tagihan.create');
Route::view('/rekap-absensi-guru', 'keuangan.rekap-absensi-guru')->name('rekap_absensi_guru.index');
Route::view('/pengumuman', 'keuangan.pengumuman')->name('pengumuman.index');
Route::view('/pengumuman/tambah', 'keuangan.tambah-pengumuman')->name('pengumuman.create');
Route::get('/pengumuman/{id}/edit', fn (int $id) => view('keuangan.tambah-pengumuman', ['announcementId' => $id]))
    ->whereNumber('id')->name('pengumuman.edit');
