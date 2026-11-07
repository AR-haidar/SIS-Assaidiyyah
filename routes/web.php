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

Route::get('/wali-kelas/dashboard', function () {
    return view('wali-kelas.dashboard');
});

Route::get('/wali-kelas/data-siswa', function () {
    return view('wali-kelas.data-siswa');
});

Route::get('/wali-kelas/presensi-absensi', function () {
    return view('wali-kelas.presensi-absensi');
});

Route::get('/wali-kelas/keuangan-spp', function () {
    return view('wali-kelas.keuangan-spp');
});

Route::get('/kepala-sekolah/dashboard', function () { return view('kepala-sekolah.dashboard'); });
Route::get('/kepala-sekolah/rekap-absensi', function () { return view('kepala-sekolah.rekap-absensi'); });
Route::get('/kepala-sekolah/rekap-pembayaran', function () { return view('kepala-sekolah.rekap-pembayaran'); });
Route::get('/kepala-sekolah/data-sekolah', function () { return view('kepala-sekolah.data-sekolah'); });
Route::get('/kepala-sekolah/pengumuman', function () { return view('kepala-sekolah.pengumuman'); });

Route::get('/guru/dashboard', function () { return view('guru.dashboard'); });
Route::get('/guru/profile', function () { return view('guru.profile'); });
Route::get('/guru/rekap-absensi', function () { return view('guru.rekap-absensi'); });

Route::get('/operator/dashboard', function () { return view('operator.dashboard'); });
Route::get('/operator/data-siswa', function () { return view('operator.data-siswa'); });
Route::get('/operator/data-guru', function () { return view('operator.data-guru'); });
Route::get('/operator/data-sekolah', function () { return view('operator.data-sekolah'); });
Route::get('/operator/pengumuman', function () { return view('operator.pengumuman'); });
Route::get('/operator/pengumuman/tambah', function () { return view('operator.tambah-pengumuman'); });
Route::get('/operator/data-guru/tambah', function () { return view('operator.tambah-guru'); });
Route::get('/operator/data-siswa/tambah', function () { return view('operator.tambah-siswa'); });

Route::get('/keuangan/dashboard', function () { return view('keuangan.dashboard'); });
Route::get('/keuangan/pembayaran-siswa', function () { return view('keuangan.pembayaran-siswa'); });
Route::get('/keuangan/pembayaran-siswa/detail', function () { return view('keuangan.detail-pembayaran'); });

Route::get('/perpustakaan/dashboard', function () { return view('perpustakaan.dashboard'); });
Route::get('/perpustakaan/data-buku', function () { return view('perpustakaan.data-buku'); });
Route::get('/perpustakaan/kategori-buku', function () { return view('perpustakaan.kategori-buku'); });
Route::get('/perpustakaan/data-buku/tambah', function () { return view('perpustakaan.tambah-data-buku'); });
Route::get('/perpustakaan/kategori-buku/tambah', function () { return view('perpustakaan.tambah-kategori-buku'); });
Route::get('/perpustakaan/data-buku/edit', function () { return view('perpustakaan.edit-data-buku'); });

Route::get('/perpustakaan-digital', function () { return view('perpustakaan-digital'); });
Route::get('/perpustakaan-digital/buku/{id}', function ($id) { return view('detail-buku', ['bookId' => $id]); });
Route::get('/perpustakaan-digital/buku/{id}/baca', function ($id) { return view('halaman-baca-buku', ['bookId' => $id]); });
