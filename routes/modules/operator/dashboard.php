<?php

// routes/modules/operator/dashboard.php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'operator'])->name('dashboard');
