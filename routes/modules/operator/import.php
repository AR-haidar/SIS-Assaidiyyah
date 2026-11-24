<?php

use App\Http\Controllers\Operator\ImporDataController;
use Illuminate\Support\Facades\Route;

Route::prefix('impor')->name('impor.')->group(function (): void {
    Route::post('/siswa', [ImporDataController::class, 'imporSiswa'])->name('siswa.store');
    Route::post('/gtk', [ImporDataController::class, 'imporGtk'])->name('gtk.store');
    Route::get('/template/siswa', [ImporDataController::class, 'templateSiswa'])->name('template.siswa');
    Route::get('/template/gtk', [ImporDataController::class, 'templateGtk'])->name('template.gtk');
});
