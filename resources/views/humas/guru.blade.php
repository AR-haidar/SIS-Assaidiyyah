@extends('humas.layout')
@section('title', 'Profil Guru Landing Page')
@section('content')
<section class="humas-teachers" data-humas-teachers>
    <header class="humas-teachers-heading">
        <div><h1>Kelola Profil Dewan Guru di Landing Page</h1><p>Kurasi tampilan profil tenaga pendidik dan kependidikan yang ditampilkan kepada pengunjung website sekolah.</p></div>
        <div class="humas-teacher-top-actions"><a class="primary" href="{{ url('/humas/guru/tambah') }}">＋ Tambah Profil Guru Baru</a></div>
    </header>
    <div class="humas-teacher-notice"><p><strong>Pemberitahuan Otoritas Pengelolaan Humas</strong><br>Modul ini <b>hanya mengatur kartu profil guru yang tampil di publik Landing Page.</b> Penambahan NUPTK, mutasi jabatan, kredensial akun SIM Madrasah, dan data induk kepegawaian tetap dikelola oleh <b>Operator Madrasah</b> melalui menu resmi <i>Data Induk Guru</i>.</p></div>
    <div class="humas-teacher-stats"><article><span>Status Visibilitas Aktif</span><strong data-teacher-published>6 Guru</strong><small>Live di Website</small></article><article><span>Draf / Disembunyikan</span><strong data-teacher-draft>2 Profil</strong><small>Menunggu Publikasi</small></article></div>
    <form class="humas-teacher-filters" data-teacher-filter-form>
        <label><span aria-hidden="true">⌕</span><input type="search" name="search" placeholder="Cari nama dewan guru atau bidang studi..." aria-label="Cari profil guru"></label>
        <select name="role" aria-label="Filter jabatan"><option value="">Semua Jabatan / Tingkat</option></select>
        <select name="visibility" aria-label="Filter visibilitas"><option value="">Semua Visibilitas</option><option>Published</option><option>Draft</option></select>
        <button type="submit"><span aria-hidden="true">▽</span> Terapkan Filter</button>
    </form>
    <div class="humas-teacher-table-wrap">
        <table class="humas-teacher-table">
            <thead><tr><th>No</th><th>Urutan</th><th>Foto Profil</th><th>Nama &amp; Gelar Lengkap</th><th>Jabatan / Bidang Mengajar</th><th>Deskripsi Singkat / Kutipan</th><th>Visibilitas Landing Page</th><th>Aksi</th></tr></thead>
            <tbody data-teacher-rows></tbody>
        </table>
        <footer><span data-teacher-summary>Menampilkan 1-5 dari 8 data sorotan aktif di landing page</span><nav data-teacher-pagination aria-label="Halaman profil guru"></nav></footer>
    </div>
    <section class="humas-teacher-preview"><h2>Pratinjau Langsung Komponen Landing Page</h2><p>Representasi visual kartu profil guru yang akan dilihat oleh calon wali murid di website publik.</p><div data-teacher-preview-cards></div></section>
    <section class="humas-teacher-modal" data-teacher-modal hidden><form data-teacher-form><header><h2 data-teacher-form-title>Tambah Sorotan Profil Guru</h2><button type="button" data-teacher-close aria-label="Tutup">×</button></header><label>Nama Lengkap &amp; Gelar<input name="name" required maxlength="100" placeholder="Nama guru beserta gelar"></label><label>Jabatan / Bidang Mengajar<input name="role" required maxlength="100" placeholder="Contoh: Guru Bahasa Arab"></label><label>Deskripsi Singkat / Kutipan<textarea name="description" required maxlength="160" rows="3" placeholder="Maksimal 160 karakter"></textarea></label><label>Foto Profil<input name="photo" type="file" accept="image/jpeg,image/png,image/webp"><img data-teacher-photo-preview hidden alt="Pratinjau foto profil"></label><label>Visibilitas<select name="status"><option>Published</option><option>Draft</option></select></label><footer><button type="button" data-teacher-close>Batal</button><button type="submit" class="primary">Simpan Profil</button></footer></form></section>
</section>
@endsection