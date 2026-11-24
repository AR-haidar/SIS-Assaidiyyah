<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi & Portal Akademik MI Assaidiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
</head>
<body class="home-page">

    {{-- ========================
         TOPBAR / NAVBAR (DIPERTAHANKAN)
         ======================== --}}
    <header class="navbar">
        <div class="navbar-container">

            {{-- Sisi Kiri: Logo & Identitas --}}
            <a href="{{ url('/') }}" class="navbar-brand">
                <div class="logo-slot" title="Slot Logo MI Assaidiyyah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-title">MI ASSA'IDIYYAH</span>
                    <span class="brand-subtitle">Madrasah Ibtidaiyah Unggulan Cipanas</span>
                </div>
            </a>

            {{-- Sisi Tengah: Navigasi --}}
            <nav class="navbar-menu">
                <a href="#beranda" class="nav-link active">Beranda</a>
                <a href="#profil" class="nav-link">Profile &amp; Visi Misi</a>
                <a href="#program" class="nav-link">Program</a>
                <a href="#guru" class="nav-link">Profil Guru</a>
                <a href="#prestasi" class="nav-link">Prestasi Siswa</a>
                <a href="{{ route('publik.perpustakaan.index') }}" data-section="#perpustakaan" class="nav-link">Perpustakaan</a>
                <a href="#kontak" class="nav-link">Kontak</a>
            </nav>

            {{-- Sisi Kanan: Tombol Portal Login / Dashboard --}}
            <div class="navbar-action">
                @if(auth()->check() || request()->query('demo_login') === '1')
                    <a href="{{ url('/siswa/dashboard') }}" class="btn-portal">
                        <span class="btn-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        </span>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ url('/login') }}" class="btn-portal">
                        <span class="btn-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <span>Portal Login</span>
                    </a>
                @endif
            </div>

        </div>
    </header>

    {{-- ========================
         MAIN CONTENT AREA
         ======================== --}}
    <main class="home-main-container">

        {{-- ========================================================
             PART 1 — SECTION 1: HERO SECTION
             ======================================================== --}}
        <section class="section-hero" id="beranda">
            <div class="hero-left-content">
                <span class="hero-tag-badge">Madrasah Ibtidaiyah Terakreditasi</span>
                
                <h1 class="hero-headline">
                    Sistem Informasi &amp; Portal Akademik MI Assaidiyyah
                </h1>

                <p class="hero-description-text">
                    Portal informasi, akademik, administrasi, dan layanan digital MI Assaidiyyah untuk siswa, orang tua, guru, dan tenaga kependidikan.
                </p>

                {{-- Skeleton line decoration --}}
                <div class="hero-skeleton-lines">
                    <div class="skeleton-line full"></div>
                    <div class="skeleton-line partial"></div>
                </div>

                <div class="hero-cta-buttons">
                    <a href="#profil" class="btn-hero-primary">Jelajahi Profil Madrasah</a>
                    <a href="#program" class="btn-hero-secondary">Program Unggulan</a>
                </div>

                <div class="hero-footer-meta" hidden>
                    <span>Kurikulum Merdeka &amp; Kemenag RI</span>
                    <span class="meta-bullet">•</span>
                    <span>Portal Terintegrasi 7 Peran</span>
                </div>
            </div>

            {{-- Kolom Kanan: Card Foto Madrasah --}}
            <div class="hero-right-visual">
                <div class="hero-photo-card">
                    <div class="hero-photo-inner-dashed">
                        <div class="hero-photo-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                        </div>
                        <h3 class="hero-photo-title" hidden>[Foto Gedung &amp; Lingkungan Madrasah]</h3>
                        <p class="hero-photo-sub" hidden>Slot foto resmi MI Assaidiyyah (Rasio 4:3, format lanskap)</p>
                    </div>
                    <div class="hero-photo-bottom-meta" hidden>
                        <span>[Keterangan Foto Madrasah]</span>
                        <span>Dimensi Rekomendasi: 800 × 600</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================================
             PART 1 — SECTION 2: VISI & MISI MADRASAH
             ======================================================== --}}
        <section class="section-block" id="profil">
            <div class="section-title-wrap">
                <span class="section-pre-tag">PROFIL LEMBAGA</span>
                <h2 class="section-heading">Visi &amp; Misi Madrasah</h2>
                <p class="section-caption">Landasan filosofis dan komitmen pendidikan dasar Islam di lingkungan MI Assaidiyyah.</p>
            </div>

            <div class="visimisi-two-cards-grid">
                
                {{-- Card Visi Utama (Kiri) --}}
                <div class="visimisi-card">
                    <div class="visimisi-card-header">
                        <span class="visimisi-card-title-left">VISI UTAMA</span>
                        <span class="visimisi-card-title-right">MI Assaidiyyah</span>
                    </div>
                    <div class="visimisi-card-body">
                        <div class="visi-quote-box">
                            <p class="visi-quote-text">Membentuk Pribadi Islami Nan Mandiri, Berbudi Pekerti dan Bermoderasi di Era Digitalisasi</p>
                        </div>
                    </div>
                </div>

                {{-- Card Misi Madrasah (Kanan) --}}
                <div class="visimisi-card">
                    <div class="visimisi-card-header">
                        <span class="visimisi-card-title-left">MISI MADRASAH</span>
                        <span class="visimisi-card-title-right">Komitmen Madrasah</span>
                    </div>
                    <div class="visimisi-card-body-list">
                        
                        {{-- Misi Item 01 --}}
                        <div class="misi-list-item">
                            <div class="misi-number-badge">01</div>
                            <div class="misi-item-text">
                                <p class="misi-item-desc">Menanamkan keimanan dan ketakwaan melalui pengamalan karakter keislaman.</p>
                            </div>
                        </div>

                        {{-- Misi Item 02 --}}
                        <div class="misi-list-item">
                            <div class="misi-number-badge">02</div>
                            <div class="misi-item-text">
                                <p class="misi-item-desc">Membina kemandirian peserta didik melalui kegiatan pembiasaan kewirausahaan dan pengembangan diri.</p>
                            </div>
                        </div>

                        {{-- Misi Item 03 --}}
                        <div class="misi-list-item">
                            <div class="misi-number-badge">03</div>
                            <div class="misi-item-text">
                                <p class="misi-item-desc">Mengoptimalkan proses pembelajaran dan bimbingan.</p>
                            </div>
                        </div>

                        <div class="misi-list-item"><div class="misi-number-badge">04</div><div class="misi-item-text"><p class="misi-item-desc">Mengembangkan ilmu pengetahuan dan teknologi berdasarkan minat, bakat, dan potensi.</p></div></div>
                        <div class="misi-list-item"><div class="misi-number-badge">05</div><div class="misi-item-text"><p class="misi-item-desc">Menjalin kerja sama yang harmonis antarwarga sekolah dan lembaga lain yang terkait.</p></div></div>

                    </div>
                </div>

            </div>
        </section>

        {{-- ========================================================
             PART 2 — SECTION 1: PROGRAM UNGGULAN MADRASAH
             ======================================================== --}}
@php
    $daftarGuruPublik = ($profilGuruPublik ?? collect())->filter(fn($g) => $g->aktif ?? ($g->tampil ?? true));
    $daftarPrestasiPublik = ($prestasiSekolah ?? collect())->filter(fn($p) => $p->aktif ?? true);
@endphp
<section class="section-block" id="guru"
    style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); box-sizing:border-box;">
    <div class="section-title-wrap">
        <span class="section-pre-tag">TENAGA PENDIDIK</span>
        <h2 class="section-heading">Profil Guru</h2>
        <p class="section-caption">Kenali tenaga pendidik dan staf yang mendampingi kegiatan belajar siswa.</p>
    </div>
    <div class="facilities-four-grid teacher-profile-grid">
        @forelse ($daftarGuruPublik as $guru)
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg>
                    </div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">
                        {{ $guru->nama_tampil ?? ($guru->pegawai?->nama_lengkap ?? 'Pendidik') }}</h3>
                    <p class="facility-item-desc">
                        @if ($guru->jabatan_tampil)
                            <strong>{{ $guru->jabatan_tampil }}</strong> &mdash;
                        @endif
                        {{ $guru->bio_singkat ?? 'Tenaga pendidik MI Assaidiyyah.' }}
                    </p>
                    <div class="dlib-actions-row">
                        <a href="{{ route('publik.perpustakaan.index') }}" class="btn-open-dlib">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                            <span>Buka Perpustakaan Digital</span>
                        </a>
                        <span class="dlib-support-caption">Mendukung format e-book dan modul DDC Kemenag</span>
                    </div>
                </div>
            </article>
        @empty
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Guru Kelas</h3>
                    <p class="facility-item-desc">Profil guru kelas dan informasi pendampingan belajar siswa.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Guru Pendidikan Agama</h3>
                    <p class="facility-item-desc">Profil pendidik yang membina pembelajaran dan karakter keislaman.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Guru Olahraga dan Seni</h3>
                    <p class="facility-item-desc">Profil pendidik yang mendukung kesehatan, kreativitas, dan minat
                        siswa.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Tenaga Kependidikan</h3>
                    <p class="facility-item-desc">Profil staf yang mendukung layanan dan kegiatan madrasah.</p>
                </div>
            </article>
        @endforelse
    </div>
</section>

{{-- ========================================================
             PART 3 — SECTION 1: KATALOG PRESTASI SISWA
             ======================================================== --}}
<section class="section-block" id="prestasi"
    style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); background:#fff; box-sizing:border-box;">
    <div class="section-title-wrap">
        <span class="section-pre-tag">KATALOG SISWA</span>
        <h2 class="section-heading">Katalog Prestasi Siswa</h2>
        <p class="section-caption">Ruang informasi untuk menampilkan capaian dan karya siswa MI Assaidiyyah.</p>
    </div>
    <div class="facilities-four-grid achievement-grid">
        @forelse ($daftarPrestasiPublik as $prestasi)
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg>
                    </div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">{{ $prestasi->judul }}</h3>
                    <p class="facility-item-desc">
                        @if ($prestasi->tingkat || $prestasi->tahun)
                            <strong>{{ implode(' • ', array_filter([$prestasi->kategori, $prestasi->tingkat, $prestasi->tahun])) }}</strong>
                            &mdash;
                        @endif
                        {{ $prestasi->deskripsi ?? 'Capaian prestasi siswa MI Assaidiyyah.' }}
                    </p>
                </div>
            </article>
        @empty
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34"
                            height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Prestasi Akademik</h3>
                    <p class="facility-item-desc">Informasi lomba, olimpiade, dan capaian akademik siswa akan
                        ditampilkan di sini.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34"
                            height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Prestasi Keagamaan</h3>
                    <p class="facility-item-desc">Informasi capaian siswa dalam kegiatan dan kompetisi keagamaan akan
                        ditampilkan di sini.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34"
                            height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Prestasi Olahraga dan Seni</h3>
                    <p class="facility-item-desc">Informasi karya, pertandingan, dan kegiatan kreatif siswa akan
                        ditampilkan di sini.</p>
                </div>
            </article>
        @endforelse
    </div>
</section>
