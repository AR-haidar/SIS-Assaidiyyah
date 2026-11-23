<?php

use App\Http\Controllers\Operator\AkunStafController;
use Illuminate\Support\Facades\Route;

Route::resource('akun-staf', AkunStafController::class)
    ->parameters(['akun-staf' => 'pengguna'])
    ->except(['show']);

Route::patch('akun-staf/{pengguna}/status', [AkunStafController::class, 'ubahStatus'])
    ->name('akun-staf.status');

Route::patch('akun-staf/{pengguna}/reset-sandi', [AkunStafController::class, 'resetSandi'])
    ->name('akun-staf.reset-sandi');
