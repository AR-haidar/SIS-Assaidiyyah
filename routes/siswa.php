<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'siswa.dashboard', ['peran' => 'siswa'])->name('dashboard');

foreach (glob(__DIR__ . '/modules/siswa/*.php') as $berkas) {
    require $berkas;
}
