<?php

use App\Http\Controllers\Keuangan\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => to_route('keuangan.dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');