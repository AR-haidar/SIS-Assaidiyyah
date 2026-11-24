<?php

// routes/modules/keuangan/pembayaran.php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'keuangan'])->name('dashboard');
Route::view('/pembayaran-siswa', 'keuangan.pembayaran-siswa')->name('pembayaran.index');
Route::view('/pembayaran-siswa/detail', 'keuangan.detail-pembayaran')->name('pembayaran.detail');
