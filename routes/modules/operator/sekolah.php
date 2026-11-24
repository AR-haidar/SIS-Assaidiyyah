<?php

use App\Http\Controllers\SekolahController;
use Illuminate\Support\Facades\Route;

Route::get('/data-sekolah', [SekolahController::class, 'editOperator'])->name('sekolah.edit');
Route::put('/data-sekolah', [SekolahController::class, 'updateOperator'])->name('sekolah.update');
