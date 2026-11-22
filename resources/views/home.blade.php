<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi & Portal Akademik MI Assaidiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
</head>
<body class="home-page" style="overflow-x:hidden;">

    {{-- ========================
         TOPBAR / NAVBAR (DIPERTAHANKAN)
         ======================== --}}
    <header class="navbar">
        <div class="navbar-container" style="width:100%; max-width:1800px; margin:0 auto; padding:12px 32px; display:grid; grid-template-columns:minmax(0,1fr) auto minmax(0,1fr); gap:16px;">

            {{-- Sisi Kiri: Logo & Identitas --}}
            <a href="{{ url('/') }}" class="navbar-brand" style="justify-self:start;">
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
            <nav class="navbar-menu" style="justify-self:center;">
                <a href="#beranda" class="nav-link active">Beranda</a>
                <a href="#profil" class="nav-link">Profile &amp; Visi Misi</a>
                <a href="#program" class="nav-link">Program</a>
                <a href="#guru" class="nav-link">Profil Guru</a>
                <a href="#prestasi" class="nav-link">Prestasi Siswa</a>
                <a href="{{ url('/perpustakaan-digital') }}" data-section="#perpustakaan" class="nav-link">Perpustakaan</a>
                <a href="#kontak" class="nav-link">Kontak</a>
            </nav>

            {{-- Sisi Kanan: Tombol Portal Login / Dashboard --}}
            <div class="navbar-action" style="justify-self:end;">
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
    <main class="home-main-container" style="padding-top:0;">

        {{-- ========================================================
             PART 1 — SECTION 1: HERO SECTION
             ======================================================== --}}
        <section class="section-hero" id="beranda" style="width:100vw; margin-left:calc(50% - 50vw); padding:72px max(32px, calc((100vw - 1800px) / 2 + 32px)); background:#fff; border:0; border-radius:0; box-shadow:none; box-sizing:border-box;">
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
                <div class="hero-photo-card" style="display:block; width:min(100%, 520px); aspect-ratio:1 / 1; flex:0 0 auto;">
                    <div class="hero-photo-inner-dashed" style="width:100%; height:100%; min-height:0; aspect-ratio:1 / 1;">
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
        <section class="section-block" id="profil" style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); box-sizing:border-box;">
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
        <section class="section-block" id="program" style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); background:#fff; box-sizing:border-box;">
            <div class="section-title-split">
                <div>
                    <span class="section-pre-tag">KURIKULUM &amp; EKSTRAKURIKULER</span>
                    <h2 class="section-heading">Program Unggulan Madrasah</h2>
                </div>
            </div>

            <div class="programs-three-grid">
                
                {{-- Card Program 1 --}}
                <div class="program-box-card">
                    <div class="program-img-placeholder">
                        <div class="p-img-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                        </div>
                    </div>
                    <div class="program-box-body">
                        <span class="program-category-pill">Program Utama</span>
                        <h3 class="program-item-name">Pembiasaan Keislaman</h3>
                        <p class="program-item-summary">Penguatan karakter, keimanan, dan amaliyah Islam dalam kegiatan belajar sehari-hari.</p>
                    </div>
                    <div class="program-box-footer">
                        <span class="program-jenjang-text">Jenjang: Kelas I - VI</span>
                    </div>
                </div>

                {{-- Card Program 2 --}}
                <div class="program-box-card">
                    <div class="program-img-placeholder">
                        <div class="p-img-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                        </div>
                    </div>
                    <div class="program-box-body">
                        <span class="program-category-pill">Program Utama</span>
                        <h3 class="program-item-name">Tahfidz Al-Qur’an</h3>
                        <p class="program-item-summary">Pembinaan hafalan Al-Qur’an dan pemahaman dasar tajwid sesuai jenjang siswa.</p>
                    </div>
                    <div class="program-box-footer">
                        <span class="program-jenjang-text">Jenjang: Reguler &amp; Khusus</span>
                    </div>
                </div>

                {{-- Card Program 3 --}}
                <div class="program-box-card">
                    <div class="program-img-placeholder">
                        <div class="p-img-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                        </div>
                    </div>
                    <div class="program-box-body">
                        <span class="program-category-pill">Pengembangan Minat</span>
                        <h3 class="program-item-name">Literasi dan Pengembangan Minat</h3>
                        <p class="program-item-summary">Kegiatan untuk mengembangkan literasi, sains, seni, dan kepramukaan siswa.</p>
                    </div>
                    <div class="program-box-footer">
                        <span class="program-jenjang-text">Kegiatan Pengembangan Diri</span>
                    </div>
                </div>

            </div>
        </section>

        {{-- ========================================================
             PART 2 — SECTION 2: FASILITAS PENUNJANG BELAJAR
             ======================================================== --}}
        <section class="section-block" id="guru" style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); box-sizing:border-box;">
            <div class="section-title-wrap">
                <span class="section-pre-tag">TENAGA PENDIDIK</span>
                <h2 class="section-heading">Profil Guru</h2>
                <p class="section-caption">Kenali tenaga pendidik dan staf yang mendampingi kegiatan belajar siswa.</p>
            </div>
            <div class="facilities-four-grid teacher-profile-grid">
                <article class="facility-box-card"><div class="facility-img-box"><div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div></div><div class="facility-box-body"><h3 class="facility-item-title">Guru Kelas</h3><p class="facility-item-desc">Profil guru kelas dan informasi pendampingan belajar siswa.</p></div></article>
                <article class="facility-box-card"><div class="facility-img-box"><div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div></div><div class="facility-box-body"><h3 class="facility-item-title">Guru Pendidikan Agama</h3><p class="facility-item-desc">Profil pendidik yang membina pembelajaran dan karakter keislaman.</p></div></article>
                <article class="facility-box-card"><div class="facility-img-box"><div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div></div><div class="facility-box-body"><h3 class="facility-item-title">Guru Olahraga dan Seni</h3><p class="facility-item-desc">Profil pendidik yang mendukung kesehatan, kreativitas, dan minat siswa.</p></div></article>
                <article class="facility-box-card"><div class="facility-img-box"><div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div></div><div class="facility-box-body"><h3 class="facility-item-title">Tenaga Kependidikan</h3><p class="facility-item-desc">Profil staf yang mendukung layanan dan kegiatan madrasah.</p></div></article>
            </div>
        </section>

        {{-- ========================================================
             PART 3 — SECTION 1: BERITA & AGENDA TERKINI
             ======================================================== --}}
        <section class="section-block" id="prestasi" style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); background:#fff; box-sizing:border-box;">
            <div class="section-title-wrap">
                <span class="section-pre-tag">KATALOG SISWA</span>
                <h2 class="section-heading">Katalog Prestasi Siswa</h2>
                <p class="section-caption">Ruang informasi untuk menampilkan capaian dan karya siswa MI Assaidiyyah.</p>
            </div>
            <div class="facilities-four-grid achievement-grid">
                <article class="facility-box-card"><div class="facility-img-box"><div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z"/><path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4"/></svg></div></div><div class="facility-box-body"><h3 class="facility-item-title">Prestasi Akademik</h3><p class="facility-item-desc">Informasi lomba, olimpiade, dan capaian akademik siswa akan ditampilkan di sini.</p></div></article>
                <article class="facility-box-card"><div class="facility-img-box"><div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z"/><path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4"/></svg></div></div><div class="facility-box-body"><h3 class="facility-item-title">Prestasi Keagamaan</h3><p class="facility-item-desc">Informasi capaian siswa dalam kegiatan dan kompetisi keagamaan akan ditampilkan di sini.</p></div></article>
                <article class="facility-box-card"><div class="facility-img-box"><div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z"/><path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4"/></svg></div></div><div class="facility-box-body"><h3 class="facility-item-title">Prestasi Olahraga dan Seni</h3><p class="facility-item-desc">Informasi karya, pertandingan, dan kegiatan kreatif siswa akan ditampilkan di sini.</p></div></article>
            </div>
        </section>

        {{-- ========================================================
             PART 3 — SECTION 2: PERPUSTAKAAN DIGITAL
             ======================================================== --}}
        <section class="section-block" id="perpustakaan" style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); box-sizing:border-box;">
            <div class="dlib-banner-frame">
                <div class="dlib-banner-left">
                    <span class="dlib-tag-pill">Koleksi Literasi Madrasah</span>
                    <h2 class="dlib-banner-heading">Perpustakaan Digital</h2>
                    <p class="dlib-banner-paragraph">
                        [Deskripsi Layanan: Akses modul pembelajaran tematik Kemenag, buku pegangan santri, dan literatur Islam secara daring tanpa proses sirkulasi fisik. Pembaca terautentikasi dapat langsung membuka katalog bacaan secara cuma-cuma.]
                    </p>
                    <div class="dlib-actions-row">
                        <a href="{{ url('/perpustakaan-digital') }}" class="btn-open-dlib">
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

                {{-- Sisi Kanan: Kartu Dashed Ilustrasi Katalog --}}
                <div class="dlib-banner-right">
                    <div class="dlib-dashed-preview-box">
                        <div class="dlib-doc-badge-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                        </div>
                        <h4 class="dlib-preview-text-main">[Ilustrasi / Pratinjau Katalog]</h4>
                        <span class="dlib-preview-text-sub">E-Book Pembelajaran MI</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================================
             PART 4 — SECTION 1: HUBUNGI KAMI
             ======================================================== --}}
        <section class="section-block" id="kontak" style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); background:#fff; box-sizing:border-box;">
            <div class="section-title-wrap">
                <span class="section-pre-tag">SEKRETARIAT MADRASAH</span>
                <h2 class="section-heading">Hubungi Kami</h2>
                <p class="section-caption">Saluran komunikasi resmi untuk pertanyaan wali santri, pendaftaran, dan informasi umum.</p>
            </div>

            <div class="contact-two-columns-layout">
                
                {{-- Kolom Kiri: Detail Info & Embed Peta --}}
                <div class="contact-left-stack">
                    
                    {{-- Card Info Kontak --}}
                    <div class="contact-info-list-card">
                        <div class="c-info-item">
                            <span class="c-info-label">ALAMAT MADRASAH</span>
                            <p class="c-info-val">[Informasi Alamat Lengkap MI Assaidiyyah]</p>
                            <span class="c-info-sub">Kecamatan / Kabupaten / Provinsi</span>
                        </div>

                        <div class="c-info-item">
                            <span class="c-info-label">TELEPON / WHATSAPP</span>
                            <p class="c-info-val">[Nomor Kontak Resmi Madrasah]</p>
                        </div>

                        <div class="c-info-item">
                            <span class="c-info-label">POS-EL RESMI</span>
                            <p class="c-info-val">[Alamat Email Resmi Madrasah]</p>
                        </div>

                        <div class="c-info-item">
                            <span class="c-info-label">WAKTU PELAYANAN ADMINISTRASI</span>
                            <p class="c-info-val">Senin – Sabtu | [Waktu Jam Operasional Tata Usaha]</p>
                        </div>
                    </div>

                    {{-- Card Embed Peta Placeholder --}}
                    <div class="contact-map-placeholder-card">
                        <div class="map-placeholder-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" style="color:#206BC7;">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <h4 class="map-placeholder-title">[Placeholder Embed Peta Lokasi Madrasah]</h4>
                            <span class="map-placeholder-sub">Integrasi Google Maps / OpenStreetMap</span>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Card Form Kirim Pesan --}}
                <div class="contact-right-form-col">
                    <div class="contact-form-box-card">
                        <h3 class="c-form-heading">Kirim Pesan ke Tata Usaha</h3>
                        
                        <form action="#" method="POST" class="c-actual-form" onsubmit="event.preventDefault();">
                            <div class="form-input-group">
                                <label for="form_nama" class="form-field-label">Nama Lengkap</label>
                                <input type="text" id="form_nama" name="nama" class="form-input-control">
                            </div>

                            <div class="form-input-group">
                                <label for="form_kontak" class="form-field-label">Email / Kontak Aktif</label>
                                <input type="text" id="form_kontak" name="kontak" class="form-input-control">
                            </div>

                            <div class="form-input-group">
                                <label for="form_subjek" class="form-field-label">Subjek</label>
                                <input type="text" id="form_subjek" name="subjek" class="form-input-control">
                            </div>

                            <div class="form-input-group">
                                <label for="form_pesan" class="form-field-label">Pesan</label>
                                <textarea id="form_pesan" name="pesan" rows="4" class="form-textarea-control"></textarea>
                            </div>

                            <button type="submit" class="btn-send-message">Kirim Pesan</button>
                        </form>
                    </div>
                </div>

            </div>
        </section>

    </main>

    {{-- ========================================================
         PART 4 — SECTION 2: FOOTER BOTTOMBAR GELAP
         ======================================================== --}}
    <footer class="site-dark-footer">
        <div class="dark-footer-container">
            
            {{-- 4 Kolom Footer Sejajar --}}
            <div class="dark-footer-grid">
                
                {{-- Kolom 1: MI Assaidiyyah --}}
                <div class="dark-footer-col">
                    <h3 class="dark-footer-brand-title">MI Assaidiyyah</h3>
                    <p class="dark-footer-brand-desc">
                        [Deskripsi Singkat Madrasah: Lembaga pendidikan formal tingkat dasar berciri khas Islam yang menyelenggarakan kurikulum nasional dan nilai amaliah Ahlussunnah wal Jamaah.]
                    </p>
                    <span class="dark-footer-akreditasi-text">Status Akreditasi Kemenag: Terdaftar Resmi</span>
                </div>

                {{-- Kolom 2: TAUTAN HALAMAN --}}
                <div class="dark-footer-col">
                    <h4 class="dark-footer-col-heading">TAUTAN HALAMAN</h4>
                    <ul class="dark-footer-nav-list">
                        <li><a href="#beranda">Beranda Utama</a></li>
                        <li><a href="#profil">Profil &amp; Visi Misi</a></li>
                        <li><a href="#program">Program Unggulan</a></li>
                        <li><a href="#guru">Profil Guru</a></li>
                        <li><a href="#prestasi">Katalog Prestasi Siswa</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: LAYANAN INTERNAL --}}
                <div class="dark-footer-col">
                    <h4 class="dark-footer-col-heading">LAYANAN INTERNAL</h4>
                    <ul class="dark-footer-nav-list">
                        <li><a href="#perpustakaan">Perpustakaan Digital</a></li>
                        <li><a href="{{ url('/siswa/dashboard') }}">Portal Siswa &amp; Wali</a></li>
                        <li><a href="{{ url('/login') }}">Portal Pendidik</a></li>
                        <li><a href="{{ url('/login') }}">Portal Tata Usaha</a></li>
                    </ul>
                </div>

                {{-- Kolom 4: SEKRETARIAT --}}
                <div class="dark-footer-col">
                    <h4 class="dark-footer-col-heading">SEKRETARIAT</h4>
                    <ul class="dark-footer-contact-list">
                        <li>[Informasi Kontak Madrasah]</li>
                        <li>[Nomor Kontak &amp; Surel Resmi]</li>
                        <li class="dark-footer-highlight-item">Kemenag EMIS 4.0 &amp; SIMPATIKA Support</li>
                    </ul>
                </div>

            </div>

            <div class="dark-footer-divider"></div>

            {{-- Baris Copyright Bawah --}}
            <div class="dark-footer-bottom-bar">
                <p class="dark-footer-copy-text">
                    &copy; MI Assaidiyyah. [Hak Cipta Dilindungi]. Sistem Informasi Madrasah.
                </p>
                <div class="dark-footer-policy-links">
                    <a href="#">Kebijakan Privasi</a>
                    <span class="policy-dot">•</span>
                    <a href="#">Syarat Ketentuan</a>
                </div>
            </div>

        </div>
    </footer>

    <script>
        (function () {
            const menuLinks = Array.from(document.querySelectorAll('.navbar-menu .nav-link'));
            const observedSections = menuLinks.map(function (link) {
                const selector = link.dataset.section || (link.getAttribute('href') || '').startsWith('#') ? (link.dataset.section || link.getAttribute('href')) : null;
                return selector ? { link: link, section: document.querySelector(selector) } : null;
            }).filter(function (item) { return item && item.section; });

            function activate(link) {
                menuLinks.forEach(function (item) {
                    const isActive = item === link;
                    item.classList.toggle('active', isActive);
                    if (isActive) item.setAttribute('aria-current', 'page');
                    else item.removeAttribute('aria-current');
                });
            }

            function updateActiveSection() {
                if (!observedSections.length) return;
                let current = observedSections[0];
                observedSections.forEach(function (item) {
                    if (item.section.getBoundingClientRect().top <= 145) current = item;
                });
                if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 3) {
                    current = observedSections[observedSections.length - 1];
                }
                activate(current.link);
            }

            menuLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    if (link.dataset.section || (link.getAttribute('href') || '').startsWith('#')) activate(link);
                });
            });
            window.addEventListener('scroll', updateActiveSection, { passive: true });
            window.addEventListener('resize', updateActiveSection);
            updateActiveSection();
        })();
    </script>
</body>
</html>
