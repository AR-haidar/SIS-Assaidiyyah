<?php

// routes/modules/humas/umum.php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'humas'])->name('dashboard');
