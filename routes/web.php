<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/siswa/dashboard', function () {
    return view('siswa.dashboard');
});

Route::get('/siswa/data-diri', function () {
    return view('siswa.data-diri');
});

Route::get('/siswa/absensi', function () {
    return view('siswa.absensi');
});

Route::get('/siswa/tagihan-spp', function () {
    return view('siswa.tagihan-spp');
});

Route::get('/helloworld', function () {
    return 'Hello World!';
});
