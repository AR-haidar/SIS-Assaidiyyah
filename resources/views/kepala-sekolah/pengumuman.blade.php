@extends('kepala-sekolah.layout')
@section('title', 'Pengumuman')
@section('page-class', 'ks-announcements-page')
@section('content')
    <header class="ks-page-heading ks-heading-with-action">
        <div><h1>Manajemen &amp; Publikasi Pengumuman</h1><p>Kanal resmi penerbitan instruksi, surat edaran, dan warta akademik Kepala Madrasah.</p></div>
        <button type="button" class="ks-primary-button">+ Buat Pengumuman Baru</button>
    </header>
    <section class="ks-announcement-stats" aria-label="Ringkasan publikasi">
        <article class="ks-announcement-stat"><div><span>TOTAL PUBLIKASI</span><strong>0</strong></div><i class="ks-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z"/><path d="M18 9a4 4 0 0 1 0 6"/></svg></i></article>
        <article class="ks-announcement-stat"><div><span>PENGUMUMAN AKTIF</span><strong>0</strong></div><i class="ks-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg></i></article>
        <article class="ks-announcement-stat"><div><span>DRAF INTERNAL</span><strong>0</strong></div><i class="ks-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="m9 15 5-5 2 2-5 5-3 1z"/></svg></i></article>
        <article class="ks-announcement-stat"><div><span>ARSIP DOKUMEN</span><strong>0</strong></div><i class="ks-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16v14H4z"/><path d="M3 3h18v4H3z"/><path d="M9 11h6"/></svg></i></article>
    </section>
    <section class="ks-announcement-controls">
        <div class="ks-filter-tabs"><button class="selected" type="button">Semua Warta (0)</button><button type="button">Aktif / Terbit (0)</button><button type="button">Draf Internal (0)</button><button type="button">Arsip Surat (0)</button></div>
        <div class="ks-announcement-filter-row">
            <label class="ks-search-field"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" placeholder="Cari judul pengumuman, nomor surat, atau kata kunci" aria-label="Cari pengumuman"></label>
            <label class="ks-select-field"><span class="sr-only">Kategori</span><select aria-label="Filter kategori"><option>Semua Kategori</option><option>Akademik</option><option>Kesiswaan</option><option>Surat Edaran</option></select></label>
            <label class="ks-select-field"><span class="sr-only">Status</span><select aria-label="Filter status"><option>Semua Status</option><option>Terbit</option><option>Draf</option><option>Arsip</option></select></label>
            <button type="button" class="ks-filter-button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z"/></svg><span>Terapkan Filter</span></button>
        </div>
    </section>
    <section class="ks-announcement-table-card">
        <div class="ks-table-scroll"><table class="ks-data-table"><thead><tr><th>No</th><th>Judul Pengumuman</th><th>Kategori</th><th>Nomor Surat / Edaran</th><th>Tanggal Terbit</th><th>Sasaran</th><th>Status</th><th>Aksi</th></tr></thead></table></div>
        <div class="ks-empty-state"><strong>Belum Ada Pengumuman Diterbitkan</strong><p>Seluruh pengumuman dan surat edaran resmi yang dibuat akan terdaftar di sini</p><button class="ks-primary-button" type="button">+ Buat Pengumuman Baru</button></div>
        <footer class="ks-table-footer"><span>Menampilkan 0 dari 0 pengumuman</span><div class="ks-pagination"><button disabled><</button><span>1</span><button disabled>></button></div></footer>
    </section>
@endsection