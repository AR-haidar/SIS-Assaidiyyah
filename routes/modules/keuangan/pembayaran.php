<?php

// routes/modules/keuangan/pembayaran.php
use App\Http\Controllers\Keuangan\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/', [DashboardController::class, 'index']);

Route::view('/pembayaran-siswa', 'keuangan.pembayaran-siswa')->name('pembayaran.index');
Route::view('/pembayaran-siswa/detail', 'keuangan.detail-pembayaran')->name('pembayaran.detail');