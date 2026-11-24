<?php

use App\Http\Controllers\Publik\BerandaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('publik.beranda');
