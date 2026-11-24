<?php

use App\Http\Controllers\Siswa\TagihanSiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn () => redirect()->route('siswa.dashboard'));
Route::get('/tagihan-spp', [TagihanSiswaController::class, 'index']);
