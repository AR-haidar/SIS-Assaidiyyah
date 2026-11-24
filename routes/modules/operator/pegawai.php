<?php

use App\Http\Controllers\Operator\PegawaiController;
use Illuminate\Support\Facades\Route;

Route::resource('pegawai', PegawaiController::class)
    ->except(['show']);

// Alias URL kompatibel dengan navigasi sidebar/frontend
Route::get('data-guru', [PegawaiController::class, 'index']);
Route::get('data-guru/tambah', [PegawaiController::class, 'create']);
Route::get('data-guru/{pegawai}/edit', [PegawaiController::class, 'edit']);
