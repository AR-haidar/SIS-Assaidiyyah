<?php

use App\Http\Controllers\Keuangan\TagihanController;
use Illuminate\Support\Facades\Route;

// Sudah dibungkus prefix "keuangan", nama "keuangan.", dan middleware oleh bootstrap/app.php.
Route::get('/tagihan/tambah', [TagihanController::class, 'create'])->name('tagihan.create');
Route::post('/tagihan', [TagihanController::class, 'store'])->name('tagihan.store');