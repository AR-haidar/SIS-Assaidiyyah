@extends('perpustakaan.layout')
@section('title', 'Dashboard Perpustakaan')
@section('content')
<section class="perpustakaan-dashboard">
    <header class="perpustakaan-dashboard-heading"><h1>Dashboard Perpustakaan Digital</h1></header>
    <section class="perpustakaan-summary" aria-label="Ringkasan katalog perpustakaan">
        <article class="perpustakaan-summary-card"><span>Katalog Koleksi</span><h2>Total Buku</h2><strong>-</strong></article>
        <article class="perpustakaan-summary-card"><span>Pustaka</span><h2>Total Kategori</h2><strong>-</strong></article>
        <article class="perpustakaan-summary-card perpustakaan-quick-card"><span>Aksi Operasional</span><h2>Tindakan Cepat</h2><p>Perbarui data atau unggah naskah buku</p><a href="{{ route('pustakawan.data-buku.index') }}" class="perpustakaan-quick-primary action-button action-button--primary"><x-perpustakaan.icon name="books" size="14" />Kelola Buku</a><a href="{{ route('pustakawan.data-buku.create') }}" class="perpustakaan-quick-secondary action-button"><x-perpustakaan.icon name="file-plus" size="14" />Tambah Buku</a></article>
    </section>
    <section class="perpustakaan-collection">
        <header class="perpustakaan-collection-heading"><div><h2>Manajemen Buku Digital</h2><p>Ringkasan operasional katalog</p></div></header>
        <section class="perpustakaan-catalog-card" aria-label="Katalog koleksi siap baca">
            <header class="perpustakaan-catalog-toolbar"><h3>Katalog Koleksi Siap Baca</h3><label class="perpustakaan-search"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" placeholder="Cari dalam katalog..." aria-label="Cari dalam katalog"></label><button type="button" class="perpustakaan-filter-button action-button action-button--quiet" aria-label="Filter katalog"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg></button></header>
            <div class="perpustakaan-catalog-empty"><strong>Belum Ada Koleksi Buku Ditambahkan</strong><p>Katalog pustaka digital madrasah masih kosong.<br>Tambahkan judul dan berkas naskah buku digital pertama<br class="perpustakaan-desktop-break"> untuk dapat diakses oleh warga madrasah secara daring.</p><a href="{{ route('pustakawan.data-buku.create') }}" class="action-button action-button--primary"><x-perpustakaan.icon name="plus" size="14" />Tambah Buku Baru</a></div>
        </section>
    </section>
</section>
@endsection
