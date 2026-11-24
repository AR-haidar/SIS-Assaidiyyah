<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Guru\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'guru'])->name('dashboard');
Route::get('/profile', [ProfilController::class, 'edit'])->name('profil.edit');
Route::view('/rekap-absensi', 'guru.rekap-absensi')->name('rekap_absensi.index');
