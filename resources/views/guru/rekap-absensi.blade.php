@extends('guru.layout')
@section('title', 'Rekap Absensi Guru')
@section('page-class', 'guru-attendance-page')
@section('content')
    <header class="guru-page-heading"><h1>Dashboard Guru &amp; Staff</h1></header>
    <section class="guru-attendance-filter" aria-label="Filter rekap kehadiran">
        <label>Pilih Periode / Bulan<select aria-label="Pilih periode atau bulan"><option value="">Pilih periode / bulan</option><option>Januari</option><option>Februari</option></select></label>
        <label>Tahun Akademik<select aria-label="Tahun akademik"><option value="">Pilih tahun akademik</option><option>2024/2025</option></select></label>
        <label>Filter Status<select aria-label="Filter status"><option value="">Semua Status</option><option>Hadir</option><option>Izin</option><option>Sakit</option></select></label>
        <button type="button" class="guru-filter-button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z"/></svg>Terapkan Filter</button>
    </section>
    <section class="guru-attendance-stats" aria-label="Ringkasan kehadiran">
        <article><div><span>Rata-Rata Kehadiran</span><b class="guru-stat-icon">%</b></div><strong>- %</strong><small>Target madrasah: = 95.0%</small></article>
        <article><div><span>Hari Hadir Kerja</span><b class="guru-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg></b></div><strong>- Hari</strong><small>Presensi terverifikasi</small></article>
        <article><div><span>Izin / Tugas Luar</span><b class="guru-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h6"/></svg></b></div><strong>- Hari</strong><small>Surat dinas terbit</small></article>
        <article><div><span>Sakit / Medis</span><b class="guru-stat-icon">+</b></div><strong>- Hari</strong><small>Keterangan dokter</small></article>
    </section>
    <section class="guru-attendance-table-card">
        <div class="guru-attendance-table-heading"><h2>Riwayat Presensi Mandiri Pendidik</h2><span>0 data dimuat</span></div>
        <div class="guru-table-scroll"><table class="guru-attendance-table"><thead><tr><th>No</th><th>Tanggal</th><th>Status Kehadiran</th><th>Keterangan</th><th>Validasi Tata Usaha</th></tr></thead></table></div>
        <div class="guru-attendance-empty"><strong>Belum Ada Catatan Kehadiran untuk Periode Terpilih</strong><p>Data log kehadiran ditarik secara otomatis dari mesin presensi<br class="guru-desktop-break"> biometrik madrasah setelah rekonsiliasi harian oleh bagian Tata<br class="guru-desktop-break"> Usaha.</p></div>
    </section>
@endsection