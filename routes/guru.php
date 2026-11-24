<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('guru.dashboard'));

foreach (glob(__DIR__.'/modules/guru/*.php') as $berkas) {
    require $berkas;
}
