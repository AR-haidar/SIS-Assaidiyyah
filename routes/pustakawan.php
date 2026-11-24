<?php

use App\Http\Controllers\Pustakawan\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

foreach (glob(__DIR__.'/modules/pustakawan/*.php') as $berkas) {
    require $berkas;
}
