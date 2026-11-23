<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'perpustakaan.dashboard', ['peran' => 'pustakawan'])->name('dashboard');

foreach (glob(__DIR__.'/modules/pustakawan/*.php') as $berkas) {
    require $berkas;
}
