<?php

use App\Http\Controllers\Pustakawan\BukuController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'perpustakaan.dashboard', ['peran' => 'pustakawan'])->name('dashboard');

Route::prefix('data-buku')->name('data-buku.')->controller(BukuController::class)->group(function (): void {
    Route::get('/', 'index')->name('index');
    Route::get('/tambah', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{buku}/edit', 'edit')->whereNumber('buku')->name('edit');
    Route::put('/{buku}', 'update')->whereNumber('buku')->name('update');
    Route::delete('/{buku}', 'destroy')->whereNumber('buku')->name('destroy');
    Route::get('/{buku}/sampul', 'sampul')->whereNumber('buku')->name('sampul');
});

foreach (glob(__DIR__ . '/modules/pustakawan/*.php') as $berkas) {
    require $berkas;
}
