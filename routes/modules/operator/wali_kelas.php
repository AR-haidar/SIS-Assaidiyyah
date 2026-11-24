<?php

use App\Http\Controllers\Operator\WaliKelasController;
use Illuminate\Support\Facades\Route;

Route::get('wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas.index');
Route::put('wali-kelas/{rombel}', [WaliKelasController::class, 'update'])->name('wali-kelas.update');
