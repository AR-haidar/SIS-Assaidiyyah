@extends('guru.layout')
@section('title', 'Dashboard Guru')
@section('page-class', 'guru-dashboard-page')
@section('content')
    <header class="guru-page-heading"><h1>Dashboard Guru &amp; Staff</h1><p>Rekapitulasi Presensi Pegawai</p></header>
    <section class="guru-dashboard-stats" aria-label="Ringkasan presensi pegawai">
        <article><span>TINGKAT KEHADIRAN<br>KUMULATIF</span><strong>- %</strong></article>
        <article><span>TOTAL HARI HADIR KERJA</span><strong>- <small>Hari</small></strong></article>
        <article><span>IZIN &amp; DINAS LUAR<br>RESMI</span><strong>- <small>Hari</small></strong></article>
        <article><span>SAKIT / ALASAN MEDIS</span><strong>- <small>Hari</small></strong></article>
    </section>
    <section class="guru-shortcuts" aria-label="Fitur guru">
        <article class="guru-shortcut-card">
            <span class="guru-shortcut-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="12" cy="9" r="3"/><path d="M7 19a5 5 0 0 1 10 0"/></svg></span>
            <h2>Profil Pendidik</h2><p>Lihat data identitas kepegawaian, NIP, serta informasi kontak resmi yang terdaftar pada sistem madrasah.</p>
            <a href="{{ url('/guru/profile') }}">Buka Data Profil  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                    </svg></a>
        </article>
        <article class="guru-shortcut-card">
            <span class="guru-shortcut-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
            <h2>Rekapitulasi Absensi Mandiri</h2><p>Pantau rincian log kehadiran harian pegawai, status verifikasi mesin presensi, dan bukti dokumen per bulan.</p>
            <a href="{{ url('/guru/rekap-absensi') }}">Lihat Rekap Absensi  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                    </svg></a>
        </article>
    </section>
    <section class="dashboard-announcement-panel" aria-labelledby="guruAnnouncementsTitle"><h2 id="guruAnnouncementsTitle">Pengumuman Madrasah</h2>@include('shared.announcement-controls', ['announcementStats' => [['key' => 'total', 'label' => 'Total Pengumuman', 'value' => 0], ['key' => 'active', 'label' => 'Pengumuman Aktif', 'value' => 0], ['key' => 'staff', 'label' => 'Informasi Guru', 'value' => 0], ['key' => 'archive', 'label' => 'Arsip Pengumuman', 'value' => 0]], 'announcementTabs' => [['value' => 'all', 'label' => 'Semua Warta'], ['value' => 'academic', 'label' => 'Akademik'], ['value' => 'staff', 'label' => 'Guru & Staf'], ['value' => 'general', 'label' => 'Informasi Umum']], 'announcementCategories' => ['Akademik', 'Guru & Staf', 'Kegiatan Madrasah', 'Informasi Umum'], 'announcementSearch' => 'Cari pengumuman untuk guru'])<div class="shared-announcement-empty"><span class="shared-announcement-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z"/><path d="M18 9a4 4 0 0 1 0 6"/></svg></span><strong>Belum Ada Pengumuman Diterbitkan</strong><p>Pengumuman resmi madrasah akan ditampilkan di sini jika tersedia.</p></div></section>
@endsection
