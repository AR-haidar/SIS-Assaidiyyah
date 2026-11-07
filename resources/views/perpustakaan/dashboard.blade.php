@extends('perpustakaan.layout')
@section('title', 'Dashboard Perpustakaan')
@section('content')
<section class="perpustakaan-dashboard">
    <header class="perpustakaan-dashboard-heading"><h1>Dashboard Perpustakaan Digital</h1></header>
    <section class="perpustakaan-summary" aria-label="Ringkasan katalog perpustakaan">
        <article class="perpustakaan-summary-card"><span>Katalog Koleksi</span><h2>Total Buku</h2><strong>-</strong></article>
        <article class="perpustakaan-summary-card"><span>Pustaka</span><h2>Total Kategori</h2><strong>-</strong></article>
        <article class="perpustakaan-summary-card perpustakaan-quick-card"><span>Aksi Operasional</span><h2>Tindakan Cepat</h2><p>Perbarui data atau unggah naskah buku</p><a href="{{ url('/perpustakaan/data-buku') }}" class="perpustakaan-quick-primary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>Kelola Buku</a><a href="{{ url('/perpustakaan/data-buku/tambah') }}" class="perpustakaan-quick-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2h8l4 4v16H4V2z"/><path d="M8 2v5h8V2M8 13h8M8 17h8"/></svg>Tambah Buku</a></article>
    </section>
    <section class="perpustakaan-collection">
        <header class="perpustakaan-collection-heading"><div><h2>Manajemen Buku Digital</h2><p>Ringkasan operasional katalog</p></div></header>
        <section class="perpustakaan-catalog-card" aria-label="Katalog koleksi siap baca">
            <header class="perpustakaan-catalog-toolbar"><h3>Katalog Koleksi Siap Baca</h3><label class="perpustakaan-search"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" placeholder="Cari dalam katalog..." aria-label="Cari dalam katalog"></label><button type="button" class="perpustakaan-filter-button" aria-label="Filter katalog"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg></button></header>
            <div class="perpustakaan-catalog-empty"><strong>Belum Ada Koleksi Buku Ditambahkan</strong><p>Katalog pustaka digital madrasah masih kosong.<br>Tambahkan judul dan berkas naskah buku digital pertama<br class="perpustakaan-desktop-break"> untuk dapat diakses oleh warga madrasah secara daring.</p><a href="{{ url('/perpustakaan/data-buku/tambah') }}"><span aria-hidden="true">＋</span>Tambah Buku Baru</a></div>
        </section>
    </section>
</section>
@endsection