<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'operator.dashboard', ['peran' => 'operator'])->name('dashboard');

foreach (glob(__DIR__.'/modules/operator/*.php') as $berkas) {
    require $berkas;
}
