<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'keuangan.dashboard', ['peran' => 'keuangan'])->name('dashboard');

foreach (glob(__DIR__.'/modules/keuangan/*.php') as $berkas) {
    require $berkas;
}
