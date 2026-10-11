@extends('guru.layout')
@section('title', 'Profile Guru')
@section('page-class', 'guru-profile-page')
@section('content')
    <header class="guru-page-heading"><h1>Profil</h1></header>
    <div class="guru-profile-layout">
        <aside class="guru-profile-card">
            <div class="guru-photo-placeholder"><span><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></span><strong>Pasfoto Pendidik</strong><small>Format Resmi<br>3-4</small></div>
            <div class="guru-profile-summary"><span>NAMA LENGKAP<br>&amp; GELAR</span><strong>-</strong></div>
            <div class="guru-profile-summary"><span>Nomor Induk Pegawai<br>(NIP / ID)</span><strong>-</strong></div>
            <div class="guru-profile-summary"><span>Status Kepegawaian</span><strong>-</strong></div>
        </aside>
        <div class="guru-profile-details">
            <section class="guru-profile-section"><h2>Identitas Pokok Kepegawaian</h2><div class="guru-profile-fields">
                <article><span>Nama Lengkap</span><strong>-</strong></article>
                <article><span>Nomor Induk Pegawai (NIP)</span><strong>-</strong></article>
                <article><span>Nomor Unik Pendidik &amp; Tenaga Kependidikan (NUPTK)</span><strong>-</strong></article>
                <article><span>Nomor Induk Kependudukan (NIK)</span><strong>-</strong></article>
                <article><span>Status Kepegawaian</span><strong>-</strong></article>
                <article><span>Pendidikan Terakhir</span><strong>-</strong></article>
            </div></section>
            <section class="guru-profile-section"><h2>Informasi Pribadi &amp; Kontak Resmi</h2><div class="guru-profile-fields">
                <article><span>Tempat, Tanggal Lahir</span><strong>-</strong></article>
                <article><span>Jenis Kelamin</span><strong>-</strong></article>
                <article><span>Nomor Telepon / WhatsApp</span><strong>-</strong></article>
                <article><span>Alamat Email Resmi</span><strong>-</strong></article>
                <article class="guru-field-wide"><span>Alamat Domisili Lengkap</span><strong>-</strong></article>
            </div></section>
        </div>
    </div>
@endsection