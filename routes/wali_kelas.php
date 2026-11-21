<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'wali-kelas.dashboard', ['peran' => 'wali_kelas'])->name('dashboard');

foreach (glob(__DIR__ . '/modules/wali_kelas/*.php') as $berkas) {
    require $berkas;
}
