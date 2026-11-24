<?php

use App\Http\Controllers\Keuangan\TagihanSiswaController;
use Illuminate\Support\Facades\Route;

// Sudah dibungkus prefix "siswa", nama "siswa.", dan middleware peran:siswa oleh bootstrap/app.php.
Route::get('/tagihan-spp', [TagihanSiswaController::class, 'index'])->name('tagihan.index');
Route::post('/tagihan-spp/{tagihan}/ajukan', [TagihanSiswaController::class, 'ajukan'])
    ->whereNumber('tagihan')->name('tagihan.ajukan');