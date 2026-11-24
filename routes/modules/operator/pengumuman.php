<?php

use App\Http\Controllers\PengumumanController;
use Illuminate\Support\Facades\Route;

Route::prefix('pengumuman')->name('pengumuman.')->group(function (): void {
    Route::get('/', [PengumumanController::class, 'index'])->name('index');
    Route::get('/tambah', [PengumumanController::class, 'create'])->name('create');
    Route::post('/', [PengumumanController::class, 'store'])->name('store');
    Route::get('/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('edit');
    Route::put('/{pengumuman}', [PengumumanController::class, 'update'])->name('update');
    Route::delete('/{pengumuman}', [PengumumanController::class, 'destroy'])->name('destroy');
});
