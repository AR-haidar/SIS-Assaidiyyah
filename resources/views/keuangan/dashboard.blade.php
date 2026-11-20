@extends('keuangan.layout')
@section('title', 'Dashboard Keuangan')
@section('content')
<section class="keuangan-dashboard">
<header class="keuangan-dashboard-heading"><div><h1>Dashboard Keuangan</h1><p>Ringkasan pemantauan transaksi seluruh kategori tagihan</p></div>
<div class="keuangan-dashboard-actions"><a href="{{ url('/keuangan/tagihan/tambah') }}" class="keuangan-button keuangan-button-light">Input Tagihan</a><a href="{{ url('/keuangan/pembayaran-siswa') }}" class="keuangan-button keuangan-button-primary">Input Pembayaran</a></div></header>
<div class="keuangan-status-cards" aria-label="Ringkasan status pembayaran">
<article class="keuangan-status-card"><span>Status Antrean</span><h2>Menunggu Verifikasi</h2><p><strong>&mdash;</strong> Transaksi</p></article>
<article class="keuangan-status-card"><span>Status Valid</span><h2>Pembayaran Disetujui</h2><p><strong>&mdash;</strong> Transaksi</p></article>
<article class="keuangan-status-card"><span>Status Retur</span><h2>Pembayaran Ditolak</h2><p><strong>&mdash;</strong> Transaksi</p></article></div>
<section class="keuangan-verification-card">
<header class="keuangan-verification-heading"><div><h2>Pembayaran Menunggu Verifikasi</h2><p>Daftar pengajuan pembayaran santri yang membutuhkan konfirmasi petugas keuangan.</p></div><a href="{{ url('/keuangan/pembayaran-siswa') }}">Lihat Semua Pembayaran <span aria-hidden="true">&rarr;</span></a></header>
<div class="keuangan-quick-filters"><div class="keuangan-filter-pills"><span>Filter Cepat:</span><button type="button" class="selected">Semua Tingkat</button><button type="button">Infaq</button><button type="button">Uang Buku</button><button type="button">Wisata</button><button type="button">Seragam</button><button type="button">Lainnya</button></div>
<span class="keuangan-sync"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 7v5h-5M4 17v-5h5"/><path d="M5.6 9A7 7 0 0 1 18 6l2 2M4 16l2 2a7 7 0 0 0 12.4-3"/></svg>Sinkronisasi otomatis aktif</span></div>
<div class="keuangan-table-scroll"><table><thead><tr><th>No</th><th>Nama Siswa</th><th>Kelas</th><th>Jenis Tagihan</th><th>Nominal</th><th>Tanggal Pengajuan</th><th>Status</th></tr></thead>
<tbody><tr><td colspan="7" class="keuangan-empty-state"><strong>Belum Ada Pengajuan Pembayaran Menunggu Verifikasi</strong><p>Seluruh kiriman bukti pembayaran santri telah diverifikasi atau belum<br class="keuangan-desktop-break"> ada entri baru yang masuk.</p></td></tr></tbody></table></div></section>
</section>
@endsection
