<?php

    // use Illuminate\Support\Facades\Route;

    // Route::get('/', function () {
    //     return view('home');
    // });

    // Route::get('/login', function () {
    //     return view('login');
    // });

    // Route::get('/siswa/dashboard', function () {
    //     return view('siswa.dashboard');
    // });

    // Route::get('/siswa/data-diri', function () {
    //     return view('siswa.data-diri');
    // });

    // Route::get('/siswa/absensi', function () {
    //     return view('siswa.absensi');
    // });

    // Route::get('/siswa/tagihan-spp', function () {
    //     return view('siswa.tagihan-spp');
    // });

    // Route::get('/wali-kelas/dashboard', function () {
    //     return view('wali-kelas.dashboard');
    // });

    // Route::get('/wali-kelas/data-siswa', function () {
    //     return view('wali-kelas.data-siswa');
    // });

    // Route::get('/wali-kelas/presensi-absensi', function () {
    //     return view('wali-kelas.presensi-absensi');
    // });

    // Route::get('/wali-kelas/keuangan-spp', function () {
    //     return view('wali-kelas.keuangan-spp');
    // });

    // Route::get('/kepala-sekolah/dashboard', function () {
    //     return view('kepala-sekolah.dashboard');
    // });
    // Route::get('/kepala-sekolah/rekap-absensi', function () {
    //     return view('kepala-sekolah.rekap-absensi');
    // });
    // Route::get('/kepala-sekolah/rekap-pembayaran', function () {
    //     return view('kepala-sekolah.rekap-pembayaran');
    // });
    // Route::get('/kepala-sekolah/data-sekolah', function () {
    //     return view('kepala-sekolah.data-sekolah');
    // });
    // Route::get('/kepala-sekolah/pengumuman', function () {
    //     return view('kepala-sekolah.pengumuman');
    // });

    // Route::get('/guru/dashboard', function () {
    //     return view('guru.dashboard');
    // });
    // Route::get('/guru/profile', function () {
    //     return view('guru.profile');
    // });
    // Route::get('/guru/rekap-absensi', function () {
    //     return view('guru.rekap-absensi');
    // });

    // Route::get('/operator/dashboard', function () {
    //     return view('operator.dashboard');
    // });
    // Route::get('/operator/data-siswa', function () {
    //     return view('operator.data-siswa');
    // });
    // Route::get('/operator/data-guru', function () {
    //     return view('operator.data-guru');
    // });
    // Route::get('/operator/data-sekolah', function () {
    //     return view('operator.data-sekolah');
    // });
    // Route::get('/operator/pengumuman', function () {
    //     return view('operator.pengumuman');
    // });
    // Route::get('/operator/pengumuman/tambah', function () {
    //     return view('operator.tambah-pengumuman');
    // });
    // Route::get('/operator/data-guru/tambah', function () {
    //     return view('operator.tambah-guru');
    // });
    // Route::get('/operator/data-siswa/tambah', function () {
    //     return view('operator.tambah-siswa');
    // });
    // Route::get('/operator/data-siswa/{id}/edit', function ($id) {
    //     return view('operator.edit-siswa', ['studentId' => $id]);
    // });

    // Route::get('/keuangan/dashboard', function () {
    //     return view('keuangan.dashboard');
    // });
    // Route::get('/keuangan/pembayaran-siswa', function () {
    //     return view('keuangan.pembayaran-siswa');
    // });
    // Route::get('/keuangan/pembayaran-siswa/detail', function () {
    //     return view('keuangan.detail-pembayaran');
    // });
    // Route::get('/keuangan/tagihan/tambah', function () {
    //     return view('keuangan.tambah-tagihan');
    // });
    // Route::get('/keuangan/rekap-absensi-guru', function () {
    //     return view('keuangan.rekap-absensi-guru');
    // });
    // Route::get('/keuangan/pengumuman', function () {
    //     return view('keuangan.pengumuman');
    // });
    // Route::get('/keuangan/pengumuman/tambah', function () {
    //     return view('keuangan.tambah-pengumuman');
    // });
    // Route::get('/keuangan/pengumuman/{id}/edit', function ($id) {
    //     return view('keuangan.tambah-pengumuman', ['announcementId' => $id]);
    // });

    // Route::get('/perpustakaan/dashboard', function () {
    //     return view('perpustakaan.dashboard');
    // });
    // Route::get('/perpustakaan/data-buku', function () {
    //     return view('perpustakaan.data-buku');
    // });
    // Route::get('/perpustakaan/kategori-buku', function () {
    //     return view('perpustakaan.kategori-buku');
    // });
    // Route::get('/perpustakaan/data-buku/tambah', function () {
    //     return view('perpustakaan.tambah-data-buku');
    // });
    // Route::get('/perpustakaan/kategori-buku/tambah', function () {
    //     return view('perpustakaan.tambah-kategori-buku');
    // });
    // Route::get('/perpustakaan/data-buku/edit', function () {
    //     return view('perpustakaan.edit-data-buku');
    // });

    // Route::get('/perpustakaan-digital', function () {
    //     return view('perpustakaan-digital');
    // });
    // Route::get('/perpustakaan-digital/buku/{id}', function ($id) {
    //     return view('detail-buku', ['bookId' => $id]);
    // });
    // Route::get('/perpustakaan-digital/buku/{id}/baca', function ($id) {
    //     return view('halaman-baca-buku', ['bookId' => $id]);
    // });

    use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PeranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/keluar', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/pilih-peran', [PeranController::class, 'index'])->name('peran.pilih');
});

foreach (glob(__DIR__.'/modules/publik/*.php') ?: [] as $berkas) {
    require $berkas;
}
// Route::get('/keuangan/dashboard', function () {
//     return view('keuangan.dashboard');
// });
// Route::get('/keuangan/pembayaran-siswa', function () {
//     return view('keuangan.pembayaran-siswa');
// });
// Route::get('/keuangan/pembayaran-siswa/detail', function () {
//     return view('keuangan.detail-pembayaran');
// });
// Route::get('/keuangan/tagihan/tambah', function () {
//     return view('keuangan.tambah-tagihan');
// });
// Route::get('/keuangan/rekap-absensi-guru', function () {
//     return view('keuangan.rekap-absensi-guru');
// });
// Route::get('/keuangan/pengumuman', function () {
//     return view('keuangan.pengumuman');
// });
// Route::get('/keuangan/pengumuman/tambah', function () {
//     return view('keuangan.tambah-pengumuman');
// });
// Route::get('/keuangan/pengumuman/{id}/edit', function ($id) {
//     return view('keuangan.tambah-pengumuman', ['announcementId' => $id]);
// });

// Route::get('/perpustakaan/dashboard', function () {
//     return view('perpustakaan.dashboard');
// });
// Route::get('/perpustakaan/data-buku', [LibraryController::class, 'books']);
// Route::post('/perpustakaan/data-buku', [LibraryController::class, 'storeBook'])->name('library.books.store');
// Route::put('/perpustakaan/data-buku/{book}', [LibraryController::class, 'updateBook'])->name('library.books.update');
// Route::delete('/perpustakaan/data-buku/{book}', [LibraryController::class, 'deleteBook'])->name('library.books.delete');
// Route::get('/perpustakaan/kategori-buku', [LibraryController::class, 'categories']);
// Route::post('/perpustakaan/kategori-buku', [LibraryController::class, 'storeCategory'])->name('library.categories.store');
// Route::put('/perpustakaan/kategori-buku/{category}', [LibraryController::class, 'updateCategory'])->name('library.categories.update');
// Route::delete('/perpustakaan/kategori-buku/{category}', [LibraryController::class, 'deleteCategory'])->name('library.categories.delete');
// Route::get('/perpustakaan/data-buku/tambah', function () {
//     return view('perpustakaan.tambah-data-buku');
// });
// Route::get('/perpustakaan/kategori-buku/tambah', function () {
//     return view('perpustakaan.tambah-kategori-buku');
// });
// Route::get('/perpustakaan/data-buku/{book}/edit', function (\App\Models\Book $book) {
//     return view('perpustakaan.edit-data-buku', ['book' => $book, 'categories' => \App\Models\BookCategory::orderBy('name')->get()]);
// });
// Route::get('/perpustakaan/kategori-buku/{category}/edit', function (\App\Models\BookCategory $category) {
//     return view('perpustakaan.edit-kategori-buku', compact('category'));
// });

// Route::get('/perpustakaan-digital', function () {
//     return view('perpustakaan-digital');
// });
// Route::get('/perpustakaan-digital/buku/{id}', function ($id) {
//     return view('detail-buku', ['bookId' => $id]);
// });
// Route::get('/perpustakaan-digital/buku/{id}/baca', function ($id) {
//     return view('halaman-baca-buku', ['bookId' => $id]);
// });

// Route::prefix('humas')->group(function () {
//     Route::get('/dashboard', fn () => view('humas.dashboard'));
//     Route::get('/guru/tambah', fn () => view('humas.guru-tambah'));
//     $modules = [
//         'beranda' => ['beranda', 'Kelola Konten Beranda', ['Badge / Label', 'Judul Utama', 'Deskripsi', 'Teks Tombol CTA', 'Banner Utama']],
//         'profil' => ['profil', 'Kelola Profil & Visi Misi', ['Profil Madrasah', 'Deskripsi Sekolah', 'Visi Madrasah', 'Daftar Misi']],
//         'program' => ['program', 'Kelola Program & Ekstrakurikuler', ['Nama Program', 'Kategori Program', 'Deskripsi', 'Gambar Program', 'Urutan Tampilan']],
//         'guru' => ['guru', 'Kelola Profil Guru Landing Page', ['Nama Guru', 'Jabatan / Bidang Mengajar', 'Deskripsi Singkat', 'Foto Guru', 'Urutan Tampilan']],
//         'prestasi' => ['prestasi', 'Kelola Prestasi Siswa', ['Judul Prestasi', 'Kategori Prestasi', 'Deskripsi', 'Nama Peraih', 'Tahun / Tanggal', 'Foto Dokumentasi']],
//         'kontak' => ['kontak', 'Kelola Kontak & Media Resmi', ['Alamat Sekolah', 'Nomor Telepon', 'WhatsApp', 'Email', 'Tautan Media Sosial']],
//     ];
//     foreach ($modules as $slug => [$view, $title, $fields]) {
//         Route::get('/' . $slug, fn () => view('humas.' . $view, ['slug' => $slug, 'title' => $title, 'fields' => $fields]));
//     }
// });
