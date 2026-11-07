@extends('keuangan.layout')
@section('title', 'Dashboard Keuangan')
@section('content')
<section class="keuangan-dashboard">
<header class="keuangan-dashboard-heading"><div><h1>Dashboard Keuangan</h1><p>Ringkasan pemantauan transaksi dan antrean verifikasi pembayaran SPP</p></div>
<div class="keuangan-dashboard-actions"><button type="button" class="keuangan-button keuangan-button-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg>Unduh Laporan</button><a href="{{ url('/keuangan/pembayaran-siswa') }}" class="keuangan-button keuangan-button-primary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M12 14v5M9.5 16.5h5"/></svg>Input Pembayaran</a></div></header>
<div class="keuangan-status-cards" aria-label="Ringkasan status pembayaran">
<article class="keuangan-status-card"><span>Status Antrean</span><h2>Menunggu Verifikasi</h2><p><strong>&mdash;</strong> Transaksi</p></article>
<article class="keuangan-status-card"><span>Status Valid</span><h2>Pembayaran Disetujui</h2><p><strong>&mdash;</strong> Transaksi</p></article>
<article class="keuangan-status-card"><span>Status Retur</span><h2>Pembayaran Ditolak</h2><p><strong>&mdash;</strong> Transaksi</p></article></div>
<section class="keuangan-verification-card">
<header class="keuangan-verification-heading"><div><h2>Pembayaran Menunggu Verifikasi</h2><p>Daftar pengajuan pembayaran santri yang membutuhkan konfirmasi petugas keuangan.</p></div><a href="{{ url('/keuangan/pembayaran-siswa') }}">Lihat Semua Pembayaran <span aria-hidden="true">&rarr;</span></a></header>
<div class="keuangan-quick-filters"><div class="keuangan-filter-pills"><span>Filter Cepat:</span><button type="button" class="selected">Semua Tingkat</button><button type="button">SPP Bulanan</button><button type="button">Daftar Ulang</button></div>
<span class="keuangan-sync"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 7v5h-5M4 17v-5h5"/><path d="M5.6 9A7 7 0 0 1 18 6l2 2M4 16l2 2a7 7 0 0 0 12.4-3"/></svg>Sinkronisasi otomatis aktif</span></div>
<div class="keuangan-table-scroll"><table><thead><tr><th>No</th><th>Nama Siswa</th><th>Kelas</th><th>Periode SPP</th><th>Nominal</th><th>Tanggal Pengajuan</th><th>Status</th></tr></thead>
<tbody><tr><td colspan="7" class="keuangan-empty-state"><strong>Belum Ada Pengajuan Pembayaran Menunggu Verifikasi</strong><p>Seluruh kiriman bukti pembayaran santri telah diverifikasi atau belum<br class="keuangan-desktop-break"> ada entri baru yang masuk.</p></td></tr></tbody></table></div></section>
</section>
@endsection