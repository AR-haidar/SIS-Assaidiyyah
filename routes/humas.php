<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'humas.dashboard', ['peran' => 'humas'])->name('dashboard');

foreach (glob(__DIR__.'/modules/humas/*.php') as $berkas) {
    require $berkas;
}
