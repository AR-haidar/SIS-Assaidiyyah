@extends('perpustakaan.layout')
@section('title', 'Data Buku')
@section('content')
<section class="perpustakaan-books">
    <header class="perpustakaan-books-heading">
        <h1>Data Buku</h1>
        <a href="{{ url('/perpustakaan/data-buku/tambah') }}" class="perpustakaan-add-book"><span aria-hidden="true">＋</span>Tambah Buku</a>
    </header>

    <section class="perpustakaan-book-stats" aria-label="Ringkasan data buku">
        <article><span>Koleksi Terdaftar</span><strong>0</strong></article>
        <article><span>Status Aktif</span><strong>0</strong></article>
        <article><span>Draft Buku</span><strong>0</strong></article>
        <article><span>Modul e-Reader</span><strong class="ready">Online Ready</strong></article>
    </section>

    <section class="perpustakaan-book-table-card" aria-label="Daftar buku digital">
        <form class="perpustakaan-book-filters">
            <label class="perpustakaan-book-search"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" placeholder="Cari judul atau penulis buku..." aria-label="Cari judul atau penulis buku"></label>
            <label><select aria-label="Filter kategori"><option value="">Semua Kategori</option></select></label>
            <label><select aria-label="Filter status"><option value="">Semua Status</option><option>Aktif</option><option>Draft</option></select></label>
            <button type="submit" class="perpustakaan-filter-submit"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16l-6 7v6l-4 3v-9z"/></svg>Terapkan Filter</button>
            <button type="reset" class="perpustakaan-filter-reset"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>Reset</button>
        </form>
        <div class="perpustakaan-book-table-scroll">
            <table>
                <thead><tr><th>No</th><th>Judul Buku</th><th>Penulis</th><th>Kategori</th><th>Status Publikasi</th><th>Aksi</th></tr></thead>
                <tbody><tr><td colspan="6" class="perpustakaan-book-empty"><strong>Belum Ada Buku</strong><p>Belum ada data koleksi buku digital yang dimuat pada<br> sistem pustaka</p><a href="{{ url('/perpustakaan/data-buku/tambah') }}"><span aria-hidden="true">＋</span>Tambah Buku</a></td></tr></tbody>
            </table>
        </div>
        <footer class="perpustakaan-book-pagination"><span>Menampilkan <strong>0</strong> dari <strong>0</strong> buku</span><nav aria-label="Navigasi halaman"><button type="button" disabled aria-label="Halaman sebelumnya">&lsaquo;</button><span>1</span><button type="button" disabled aria-label="Halaman selanjutnya">&rsaquo;</button></nav></footer>
    </section>
</section>
@endsection