@extends('keuangan.layout')
@section('title', 'Rekap Absensi Guru')
@section('content')
<section class="keuangan-payments keuangan-attendance-page"><header class="keuangan-payments-heading"><div><h1>Rekap Absensi Guru</h1><p>Rekapitulasi kehadiran guru dan staf (hanya dapat dilihat)</p></div><span class="attendance-readonly-badge">Read-only</span></header>
<section class="attendance-summary" aria-label="Ringkasan rekap absensi"><article><span>Tenaga Pendidik</span><strong>03</strong><small>Data contoh</small></article><article><span>Periode Rekap</span><strong>Bulanan</strong><small>Gunakan filter periode</small></article><article><span>Akses Data</span><strong>Hanya Lihat</strong><small>Perubahan absensi tidak tersedia</small></article></section>
<form class="keuangan-revision-filters" data-attendance-filters><input type="search" placeholder="Cari nama guru" aria-label="Cari nama guru"><select aria-label="Bulan"><option value="">Semua Bulan</option>@foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $month)<option>{{ $month }}</option>@endforeach</select><select aria-label="Tahun"><option>2026</option><option>2025</option><option>2024</option></select></form>
<div class="keuangan-payment-table-card keuangan-payment-table-scroll"><table><thead><tr><th>Nama Guru</th><th>Total Hadir</th><th>Izin</th><th>Sakit</th><th>Tanpa Keterangan</th><th>Aksi</th></tr></thead><tbody data-teacher-attendance></tbody></table></div></section>
@endsection
