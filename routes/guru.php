<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'guru.dashboard', ['peran' => 'guru'])->name('dashboard');

foreach (glob(__DIR__ . '/modules/guru/*.php') as $berkas) {
    require $berkas;
}