<?php

use App\Http\Controllers\Publik\BerandaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('publik.beranda');
Route::post('/kontak', [BerandaController::class, 'kirimPesan'])->middleware('throttle:5,1')->name('publik.kontak.store');
Route::get('/foto-madrasah/{jenis}/{id}', [BerandaController::class, 'foto'])
    ->whereIn('jenis', ['gedung', 'program', 'guru', 'prestasi'])
    ->whereNumber('id')
    ->name('publik.foto');
