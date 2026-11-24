<?php

use App\Http\Controllers\Operator\ImportDataController;
use Illuminate\Support\Facades\Route;

Route::prefix('impor')->name('impor.')->group(function (): void {
    Route::post('/siswa', [ImportDataController::class, 'imporSiswa'])->name('siswa');
    Route::post('/gtk', [ImportDataController::class, 'imporGtk'])->name('gtk');
    Route::get('/template/siswa', [ImportDataController::class, 'templateSiswa'])->name('template.siswa');
    Route::get('/template/gtk', [ImportDataController::class, 'templateGtk'])->name('template.gtk');
});
