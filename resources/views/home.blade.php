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
                <a href="#fasilitas" class="nav-link">Fasilitas</a>
                <a href="#berita" class="nav-link">Berita</a>
                <a href="#perpustakaan" class="nav-link">Perpustakaan</a>
                <a href="#kontak" class="nav-link">Kontak</a>
            </nav>

            {{-- Sisi Kanan: Tombol Portal Login / Dashboard --}}
            <div class="navbar-action">
                @auth
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
                @endauth
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
                    [Deskripsi Sekolah: Platform resmi layanan administrasi madrasah, pusat literasi digital, pemantauan presensi dan akademik terpadu bagi seluruh santri, wali murid, pendidik, dan tenaga kependidikan.]
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

                <div class="hero-footer-meta">
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
                        <h3 class="hero-photo-title">[Foto Gedung &amp; Lingkungan Madrasah]</h3>
                        <p class="hero-photo-sub">Slot foto resmi MI Assaidiyyah (Rasio 4:3, format lanskap)</p>
                    </div>
                    <div class="hero-photo-bottom-meta">
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
                        <span class="visimisi-card-title-right">[Visi Madrasah]</span>
                    </div>
                    <div class="visimisi-card-body">
                        <div class="visi-quote-box">
                            <p class="visi-quote-text">“[Pernyataan Visi Resmi MI Assaidiyyah]”</p>
                        </div>
                        <p class="visi-explanation">
                            [Deskripsi penjabaran orientasi nilai religius, keunggulan sains, dan pembentukan akhlak karimah santri dasar.]
                        </p>
                    </div>
                    <div class="visimisi-card-footer">
                        <span>Status: Menunggu Konfirmasi Pimpinan</span>
                        <span>Dokumen Renstra</span>
                    </div>
                </div>

                {{-- Card Misi Madrasah (Kanan) --}}
                <div class="visimisi-card">
                    <div class="visimisi-card-header">
                        <span class="visimisi-card-title-left">MISI MADRASAH</span>
                        <span class="visimisi-card-title-right">Struktur Komponen Misi</span>
                    </div>
                    <div class="visimisi-card-body-list">
                        
                        {{-- Misi Item 01 --}}
                        <div class="misi-list-item">
                            <div class="misi-number-badge">01</div>
                            <div class="misi-item-text">
                                <h4 class="misi-item-title">[Judul Komponen Misi 1]</h4>
                                <p class="misi-item-desc">[Deskripsi ringkas pelaksanaan program kurikulum dan pembiasaan amaliyah Islamiyah.]</p>
                            </div>
                        </div>

                        {{-- Misi Item 02 --}}
                        <div class="misi-list-item">
                            <div class="misi-number-badge">02</div>
                            <div class="misi-item-text">
                                <h4 class="misi-item-title">[Judul Komponen Misi 2]</h4>
                                <p class="misi-item-desc">[Deskripsi penguatan literasi digital, sains madrasah, dan penguasaan bahasa dasar.]</p>
                            </div>
                        </div>

                        {{-- Misi Item 03 --}}
                        <div class="misi-list-item">
                            <div class="misi-number-badge">03</div>
                            <div class="misi-item-text">
                                <h4 class="misi-item-title">[Judul Komponen Misi 3]</h4>
                                <p class="misi-item-desc">[Deskripsi pembinaan tata kelola madrasah yang akuntabel, transparan, dan partisipatif.]</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>

        {{-- ========================================================
             PART 2 — SECTION 1: PROGRAM UNGGULAN MADRASAH
             ======================================================== --}}
        <section class="section-block" id="program">
            <div class="section-title-split">
                <div>
                    <span class="section-pre-tag">KURIKULUM &amp; EKSTRAKURIKULER</span>
                    <h2 class="section-heading">Program Unggulan Madrasah</h2>
                </div>
                <div class="section-title-right-label">[Pola Struktur Komponen Program]</div>
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
                            <span>[Placeholder Foto Program]</span>
                        </div>
                    </div>
                    <div class="program-box-body">
                        <span class="program-category-pill">Program Utama</span>
                        <h3 class="program-item-name">[Nama Program 1]</h3>
                        <p class="program-item-summary">[Deskripsi ringkas program unggulan, target kompetensi siswa, serta integrasi nilai keislaman dan akademik.]</p>
                    </div>
                    <div class="program-box-footer">
                        <span class="program-jenjang-text">Jenjang: Kelas I - VI</span>
                        <a href="#" class="program-detail-link">Detail Program &rarr;</a>
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
                            <span>[Placeholder Foto Program]</span>
                        </div>
                    </div>
                    <div class="program-box-body">
                        <span class="program-category-pill">Program Utama</span>
                        <h3 class="program-item-name">[Nama Program 2]</h3>
                        <p class="program-item-summary">[Deskripsi ringkas program penguatan tahfidz quran, hafalan juz amma, dan pemahaman dasar tajwid.]</p>
                    </div>
                    <div class="program-box-footer">
                        <span class="program-jenjang-text">Jenjang: Reguler &amp; Khusus</span>
                        <a href="#" class="program-detail-link">Detail Program &rarr;</a>
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
                            <span>[Placeholder Foto Program]</span>
                        </div>
                    </div>
                    <div class="program-box-body">
                        <span class="program-category-pill">Pengembangan Minat</span>
                        <h3 class="program-item-name">[Nama Program 3]</h3>
                        <p class="program-item-summary">[Deskripsi program literasi digital, sains anak, seni kaligrafi Islam, dan kegiatan kepramukaan madrasah.]</p>
                    </div>
                    <div class="program-box-footer">
                        <span class="program-jenjang-text">Peminatan Santri</span>
                        <a href="#" class="program-detail-link">Detail Program &rarr;</a>
                    </div>
                </div>

            </div>
        </section>

        {{-- ========================================================
             PART 2 — SECTION 2: FASILITAS PENUNJANG BELAJAR
             ======================================================== --}}
        <section class="section-block" id="fasilitas">
            <div class="section-title-wrap">
                <span class="section-pre-tag">SARANA &amp; PRASARANA</span>
                <h2 class="section-heading">Fasilitas Penunjang Belajar</h2>
                <p class="section-caption">Prasarana penunjang kenyamanan kegiatan belajar mengajar santri di lingkungan madrasah.</p>
            </div>

            <div class="facilities-four-grid">
                
                {{-- Fasilitas 1: Ruang Kelas --}}
                <div class="facility-box-card">
                    <div class="facility-img-box">
                        <div class="f-img-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/>
                                <path d="M2 20h20"/>
                                <path d="M14 12v.01"/>
                            </svg>
                            <span>[Foto Ruang Kelas]</span>
                        </div>
                    </div>
                    <div class="facility-box-body">
                        <h3 class="facility-item-title">Ruang Kelas</h3>
                        <p class="facility-item-desc">[Deskripsi fasilitas ruang kelas representatif dan ventilasi alami]</p>
                    </div>
                </div>

                {{-- Fasilitas 2: Mushola --}}
                <div class="facility-box-card">
                    <div class="facility-img-box">
                        <div class="f-img-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a4 4 0 0 0-4 4v2H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2h-4V6a4 4 0 0 0-4-4z"/>
                            </svg>
                            <span>[Foto Mushola]</span>
                        </div>
                    </div>
                    <div class="facility-box-body">
                        <h3 class="facility-item-title">Mushola</h3>
                        <p class="facility-item-desc">[Deskripsi sarana ibadah salat dhuha dan pembiasaan salat berjamaah]</p>
                    </div>
                </div>

                {{-- Fasilitas 3: Perpustakaan --}}
                <div class="facility-box-card">
                    <div class="facility-img-box">
                        <div class="f-img-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                            <span>[Foto Perpustakaan]</span>
                        </div>
                    </div>
                    <div class="facility-box-body">
                        <h3 class="facility-item-title">Perpustakaan</h3>
                        <p class="facility-item-desc">[Deskripsi ruang baca santri serta integrasi literasi digital madrasah]</p>
                    </div>
                </div>

                {{-- Fasilitas 4: Lapangan --}}
                <div class="facility-box-card">
                    <div class="facility-img-box">
                        <div class="f-img-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="m4.93 4.93 4.24 4.24"/>
                                <path d="m14.83 9.17 4.24-4.24"/>
                                <path d="m14.83 14.83 4.24 4.24"/>
                                <path d="m9.17 14.83-4.24 4.24"/>
                            </svg>
                            <span>[Foto Lapangan]</span>
                        </div>
                    </div>
                    <div class="facility-box-body">
                        <h3 class="facility-item-title">Lapangan</h3>
                        <p class="facility-item-desc">[Deskripsi area upacara bendera, olahraga, dan aktivitas santri luar ruangan]</p>
                    </div>
                </div>

            </div>
        </section>

        {{-- ========================================================
             PART 3 — SECTION 1: BERITA & AGENDA TERKINI
             ======================================================== --}}
        <section class="section-block" id="berita">
            <div class="section-title-split">
                <div>
                    <span class="section-pre-tag">INFORMASI PUBLIK</span>
                    <h2 class="section-heading">Berita &amp; Agenda Terkini</h2>
                </div>
                <div>
                    <a href="#" class="section-link-right">Lihat Semua Berita &rarr;</a>
                </div>
            </div>

            <div class="news-three-grid">
                
                {{-- Card Berita 1 --}}
                <article class="news-box-card">
                    <div class="news-img-placeholder">
                        <div class="n-img-icon-wrap">
                            <span>[Placeholder Foto Berita]</span>
                        </div>
                    </div>
                    <div class="news-box-body">
                        <div class="news-meta-category-date">
                            <span class="n-category">[Kategori Berita]</span>
                            <span class="n-bullet">•</span>
                            <span class="n-date">[Tanggal Publikasi]</span>
                        </div>
                        <h3 class="news-item-title">[Judul Berita 1]</h3>
                        <p class="news-item-summary">[Ringkasan atau kutipan teks berita resmi madrasah terkait kegiatan akademik, rapat wali murid, atau agenda madrasah.]</p>
                        <a href="#" class="news-read-more-link">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>

                {{-- Card Berita 2 --}}
                <article class="news-box-card">
                    <div class="news-img-placeholder">
                        <div class="n-img-icon-wrap">
                            <span>[Placeholder Foto Berita]</span>
                        </div>
                    </div>
                    <div class="news-box-body">
                        <div class="news-meta-category-date">
                            <span class="n-category">[Kategori Berita]</span>
                            <span class="n-bullet">•</span>
                            <span class="n-date">[Tanggal Publikasi]</span>
                        </div>
                        <h3 class="news-item-title">[Judul Berita 2]</h3>
                        <p class="news-item-summary">[Ringkasan teks berita resmi madrasah terkait pengumuman asesmen sumatif, kalender akademik, dan kegiatan santri.]</p>
                        <a href="#" class="news-read-more-link">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>

                {{-- Card Berita 3 --}}
                <article class="news-box-card">
                    <div class="news-img-placeholder">
                        <div class="n-img-icon-wrap">
                            <span>[Placeholder Foto Berita]</span>
                        </div>
                    </div>
                    <div class="news-box-body">
                        <div class="news-meta-category-date">
                            <span class="n-category">[Kategori Berita]</span>
                            <span class="n-bullet">•</span>
                            <span class="n-date">[Tanggal Publikasi]</span>
                        </div>
                        <h3 class="news-item-title">[Judul Berita 3]</h3>
                        <p class="news-item-summary">[Ringkasan teks pengumuman partisipasi keikutsertaan santri madrasah pada ajang kompetisi sains dan seni Islam.]</p>
                        <a href="#" class="news-read-more-link">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>

            </div>
        </section>

        {{-- ========================================================
             PART 3 — SECTION 2: PERPUSTAKAAN DIGITAL
             ======================================================== --}}
        <section class="section-block" id="perpustakaan">
            <div class="dlib-banner-frame">
                <div class="dlib-banner-left">
                    <span class="dlib-tag-pill">Koleksi Literasi Madrasah</span>
                    <h2 class="dlib-banner-heading">Perpustakaan Digital</h2>
                    <p class="dlib-banner-paragraph">
                        [Deskripsi Layanan: Akses modul pembelajaran tematik Kemenag, buku pegangan santri, dan literatur Islam secara daring tanpa proses sirkulasi fisik. Pembaca terautentikasi dapat langsung membuka katalog bacaan secara cuma-cuma.]
                    </p>
                    <div class="dlib-actions-row">
                        <a href="#" class="btn-open-dlib">
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
        <section class="section-block" id="kontak">
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
                                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" style="color:#2563eb;">
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
                        <li><a href="#fasilitas">Fasilitas Madrasah</a></li>
                        <li><a href="#berita">Warta Berita &amp; Agenda</a></li>
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

</body>
</html>
