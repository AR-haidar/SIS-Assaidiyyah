<?php

use App\Http\Controllers\Keuangan\DashboardController;
use App\Http\Controllers\Keuangan\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => to_route('keuangan.dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/pembayaran-siswa/{pembayaran}', [PembayaranController::class, 'show'])
    ->whereNumber('pembayaran')->name('pembayaran.show');
Route::post('/pembayaran-siswa/{pembayaran}/setujui', [PembayaranController::class, 'setujui'])
    ->whereNumber('pembayaran')->name('pembayaran.setujui');
Route::post('/pembayaran-siswa/{pembayaran}/tolak', [PembayaranController::class, 'tolak'])
    ->whereNumber('pembayaran')->name('pembayaran.tolak');

Route::get('/pembayaran-siswa', [PembayaranController::class, 'index'])->name('pembayaran.index');

Route::get('/pembayaran-siswa/{pembayaran}/bukti', [PembayaranController::class, 'bukti'])
    ->whereNumber('pembayaran')->name('pembayaran.bukti');