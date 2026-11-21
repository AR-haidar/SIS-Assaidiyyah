<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'kepala-sekolah.dashboard', ['peran' => 'kepala'])->name('dashboard');

foreach (glob(__DIR__ . '/modules/kepala/*.php') as $berkas) {
    require $berkas;
}
