<?php

// routes/modules/keuangan/pembayaran.php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Keuangan\TagihanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'keuangan'])->name('dashboard');
Route::get('/pembayaran-siswa', [TagihanController::class, 'index']);
Route::get('/pembayaran-siswa/detail', function (Request $request) {
    $id = $request->query('id');

    return is_string($id) && ctype_digit($id)
        ? redirect()->route('keuangan.pembayaran.show', $id)
        : redirect()->route('keuangan.tagihan.index');
})->name('pembayaran.detail');
