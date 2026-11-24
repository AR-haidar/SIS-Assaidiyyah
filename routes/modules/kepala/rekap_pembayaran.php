<?php

// routes/modules/kepala/rekap_pembayaran.php
use App\Http\Controllers\Kepala\RekapController;
use Illuminate\Support\Facades\Route;

Route::get('/rekap-pembayaran', [RekapController::class, 'pembayaran'])->name('rekap_pembayaran.index');
