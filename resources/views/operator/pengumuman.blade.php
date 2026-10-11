@extends('operator.layout')
@section('title', 'Pengumuman')
@section('content')
    <section class="operator-announcements">
        <header class="operator-announcement-heading">
            <div>
                <h1>Manajemen &amp; Publikasi Pengumuman</h1>
                <p>Kanal resmi penerbitan instruksi, surat edaran, dan warta akademik Kepala Madrasah.</p>
            </div>
            <a class="operator-announcement-create" href="{{ url('/operator/pengumuman/tambah') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Buat Pengumuman Baru
            </a>
        </header>

        <section class="operator-announcement-stats" aria-label="Ringkasan pengumuman">
            <article><span>Total Publikasi</span><strong>0</strong><i class="operator-ann-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 11 18-5-5 18-4-8-9-5z"/><path d="M12 16 19 9"/></svg></i></article>
            <article><span>Pengumuman Aktif</span><strong>0</strong><i class="operator-ann-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 14 5.5 17 5l.5 3 2.5 2-1.5 2.5.5 3-3 .5-2 2.5-2.5-1.5-3 .5-.5-3-2.5-2L7 10l-.5-3 3-.5z"/><path d="m9 12 2 2 4-4"/></svg></i></article>
            <article><span>Draf Internal</span><strong>0</strong><i class="operator-ann-icon operator-ann-icon-muted"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h5M8 17h4M17 15l3 3-3 3"/></svg></i></article>
            <article><span>Arsip Dokumen</span><strong>0</strong><i class="operator-ann-icon operator-ann-icon-muted"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h18v5H3zM5 9v11h14V9M10 13h4"/></svg></i></article>
        </section>

        <section class="operator-announcement-controls">
            <nav class="operator-announcement-tabs" aria-label="Filter status pengumuman">
                <button type="button" class="active">Semua Warta (0)</button>
                <button type="button">Aktif / Terbit (0)</button>
                <button type="button">Draf Internal (0)</button>
                <button type="button">Arsip Surat (0)</button>
            </nav>
            <form class="operator-announcement-filters">
                <label class="operator-announcement-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input type="search" placeholder="Cari judul pengumuman, nomor surat, atau kata kunci" aria-label="Cari pengumuman">
                </label>
                <label class="operator-announcement-select"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16l-6 7v6l-4 3v-9z"/></svg><select aria-label="Pilih kategori"><option>Semua Kategori</option></select></label>
                <label class="operator-announcement-select"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/><circle cx="7" cy="6" r="1"/><circle cx="17" cy="12" r="1"/></svg><select aria-label="Pilih status"><option>Semua Status</option></select></label>
                <button class="operator-announcement-apply" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg>Terapkan<br>Filter</button>
            </form>
        </section>

        <section class="operator-announcement-table-card" aria-label="Daftar pengumuman">
            <div class="operator-announcement-table-scroll">
                <table>
                    <thead><tr><th>No</th><th>Judul Pengumuman</th><th>Kategori</th><th>Nomor Surat / Edaran</th><th>Tanggal Terbit</th><th>Sasaran</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody><tr><td colspan="8" class="operator-announcement-empty">
                        <strong>Belum Ada Pengumuman Diterbitkan</strong>
                        <p>Seluruh pengumuman dan surat edaran resmi yang dibuat oleh Kepala<br class="operator-ann-desktop-break"> Madrasah akan terdaftar di sini.</p>
                        <a class="operator-announcement-create" href="{{ url('/operator/pengumuman/tambah') }}">+ Buat Pengumuman Baru</a>
                    </td></tr></tbody>
                </table>
            </div>
            <footer><span>Menampilkan 0 dari 0 pengumuman</span><nav aria-label="Navigasi halaman"><button type="button" disabled aria-label="Halaman sebelumnya">&lsaquo;</button><span>1</span><button type="button" disabled aria-label="Halaman berikutnya">&rsaquo;</button></nav></footer>
        </section>
    </section>
@endsection