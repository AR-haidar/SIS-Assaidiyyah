<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('<h1>Dashboard Humas</h1><p>Halaman tampilan Humas sedang disiapkan.</p>');
})->name('dashboard');