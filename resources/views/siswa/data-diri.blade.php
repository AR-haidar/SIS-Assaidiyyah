<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diri Siswa — SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/css/siswa/data-diri.css', 'resources/js/app.js'])
</head>
<body class="siswa-page">

    {{-- TOPBAR --}}
    <header class="siswa-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Buka/Tutup Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <a href="{{ url('/') }}" class="topbar-brand-wrap">
                <div class="topbar-logo-slot">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div class="topbar-brand-text">
                    <span class="topbar-school-name">MI Assaidiyyah</span>
                    <span class="topbar-system-name">Sistem Informasi Madrasah</span>
                </div>
            </a>
        </div>
        <div class="topbar-right">
            <div class="topbar-semester-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                <span>Tahun Ajaran</span>
                <span class="semester-badge">Semester Ganjil</span>
            </div>
            <div class="topbar-divider"></div>
            <div class="topbar-user-info">
                <span class="topbar-user-role">Siswa</span>
                <span class="topbar-user-nip">NIP. 198204122010011008</span>
            </div>
            <div class="topbar-avatar-box" title="Profil Saya">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </div>
        </div>
    </header>

    <div class="siswa-layout">

        {{-- SIDEBAR --}}
        <aside class="siswa-sidebar" id="siswaSidebar">
            <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/><path d="M20 12H9"/><path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
            <nav class="sidebar-nav">
                <a href="{{ url('/siswa/dashboard') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ url('/siswa/data-diri') }}" class="sidebar-nav-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="12" cy="11" r="2"/><path d="M8.5 15a4 4 0 0 1 7 0"/>
                    </svg>
                    Data Diri
                </a>
                <a href="{{ url('/siswa/absensi') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Absensi
                </a>
                <a href="{{ url('/siswa/tagihan-spp') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    Tagihan SPP
                </a>
                <a href="#" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    Perpustakaan Digital
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sidebar-user-name">Siswa</div>
                        <div class="sidebar-user-meta">Kelas 5B &bull; 20241029</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="siswa-main">

            {{-- Header Halaman --}}
            <div class="dd-page-header">
                <h1 class="dd-page-title">Data Diri Siswa</h1>
                <p class="dd-page-sub">Informasi biodata siswa</p>
            </div>

            {{-- 1. Top Profile Banner Card --}}
            <div class="dd-top-profile-card">
                {{-- Pasfoto 3x4 --}}
                <div class="dd-pasfoto-box">
                    <div class="dd-pasfoto-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <circle cx="12" cy="10" r="3"/>
                            <path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </div>
                    <span class="dd-pasfoto-text">Pasfoto Formal</span>
                    <span class="dd-pasfoto-size">3 × 4 CM</span>
                </div>

                {{-- Detail Header --}}
                <div class="dd-profile-quick-info">
                    <div class="dd-profile-tags-row">
                        <span class="dd-status-badge">
                            <span class="dd-status-dot"></span>
                            Status: Aktif
                        </span>
                        <span class="dd-ta-text">T.A. 2024/2025</span>
                    </div>

                    <div class="dd-profile-name">NAMA LENGKAP</div>

                    <div class="dd-profile-meta-row">
                        <div class="dd-meta-pill">NISN</div>
                        <div class="dd-meta-pill">Kelas</div>
                        <div class="dd-meta-pill">Kehadiran</div>
                    </div>
                </div>
            </div>

            {{-- 2. Grid Konten Utama: 2 Kolom (Kiri & Kanan) --}}
            <div class="dd-main-grid">

                {{-- KOLOM KIRI --}}
                <div class="dd-column">

                    {{-- Card: Identitas Siswa --}}
                    <div class="dd-card">
                        <div class="dd-card-header">
                            <h2 class="dd-card-title">Identitas Siswa</h2>
                        </div>
                        <div class="dd-card-body">
                            <div class="dd-field-grid">
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Nama Lengkap</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Nomor Induk Kependudukan (NIK)</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">NISN</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Alamat Domisili Siswa</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Tempat, Tanggal Lahir</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Jenis Kelamin</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Agama</span>
                                    <span class="dd-field-val">Islam</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Anak Ke- / Dari</span>
                                    <span class="dd-field-val">— / —</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Data Orang Tua / Wali Murid --}}
                    <div class="dd-card">
                        <div class="dd-card-header">
                            <h2 class="dd-card-title">Data Orang Tua / Wali Murid</h2>
                        </div>
                        <div class="dd-card-body">
                            <div class="dd-field-grid">
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Nama Ayah Kandung</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Pekerjaan Ayah</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Nama Ibu Kandung</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item">
                                    <span class="dd-field-label">Pekerjaan Ibu</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                                <div class="dd-field-item full-width">
                                    <span class="dd-field-label">Nomor WhatsApp / HP Aktif</span>
                                    <span class="dd-field-val">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#64748b;">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                        </svg>
                                        —
                                    </span>
                                </div>
                                <div class="dd-field-item full-width">
                                    <span class="dd-field-label">Alamat Domisili Orang Tua / Wali</span>
                                    <span class="dd-field-val">—</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div class="dd-column">

                    {{-- Card: Informasi Akademik & Kelas --}}
                    <div class="dd-card">
                        <div class="dd-card-header">
                            <h2 class="dd-card-title">Informasi Akademik &amp; Kelas</h2>
                        </div>
                        <div class="dd-card-body">
                            <div class="dd-list-rows">
                                <div class="dd-list-row">
                                    <span class="dd-list-label">Kelas</span>
                                    <span class="dd-list-value">—</span>
                                </div>
                                <div class="dd-list-row">
                                    <span class="dd-list-label">Wali Kelas</span>
                                    <span class="dd-list-value">—</span>
                                </div>
                                <div class="dd-list-row">
                                    <span class="dd-list-label">Kurikulum Diterapkan</span>
                                    <span class="dd-list-value">Kurikulum Merdeka</span>
                                </div>
                                <div class="dd-list-row">
                                    <span class="dd-list-label">Tingkat Kehadiran</span>
                                    <span class="dd-list-value" style="color:#2563eb; font-weight:700;">— %</span>
                                </div>
                            </div>

                            {{-- Kotak Rekapitulasi Presensi --}}
                            <div class="dd-presensi-banner">
                                <div class="dd-presensi-left">
                                    <div class="dd-presensi-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                                            <path d="m9 12 2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <div class="dd-presensi-texts">
                                        <span class="dd-presensi-title">Rekapitulasi Presensi</span>
                                        <span class="dd-presensi-sub">Data otomatis tersinkron setiap hari</span>
                                    </div>
                                </div>
                                <a href="{{ url('/siswa/absensi') }}" class="dd-presensi-link">
                                    <span>Detail</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Kelengkapan Berkas Administrasi --}}
                    <div class="dd-card">
                        <div class="dd-card-header">
                            <h2 class="dd-card-title">Kelengkapan Berkas Administrasi</h2>
                        </div>
                        <div class="dd-card-body">
                            <div class="dd-berkas-list">
                                <div class="dd-berkas-item">
                                    <span class="dd-berkas-name">Akta Kelahiran</span>
                                    <span class="dd-berkas-format">Format PDF</span>
                                </div>
                                <div class="dd-berkas-item">
                                    <span class="dd-berkas-name">Kartu Keluarga (KK)</span>
                                    <span class="dd-berkas-format">Format PDF</span>
                                </div>
                                <div class="dd-berkas-item">
                                    <span class="dd-berkas-name">Ijazah / Surat Keterangan Lulus</span>
                                    <span class="dd-berkas-format">Jenjang RA / TK</span>
                                </div>
                            </div>

                            <button type="button" class="btn-ajukan-berkas">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <span>Ajukan / Perbarui Berkas</span>
                            </button>
                        </div>
                    </div>

                </div>

            </div>

            {{-- 3. Bottom Note Banner (Full Width) --}}
            <div class="dd-bottom-note">
                <div class="dd-note-left">
                    <div class="dd-note-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </div>
                    <div class="dd-note-text">
                        <strong>Catatan:</strong> Apabila terdapat ketidaksesuaian data kependudukan atau NISN, silakan ajukan permohonan pembaruan data secara online menggunakan tombol di atas atau hubungi loket Tata Usaha MI Assaidiyyah pada jam kerja operasional madrasah.
                    </div>
                </div>
                <a href="#" class="dd-note-link">
                    <span>Kontak Tata Usaha</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                </a>
            </div>

        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('siswaSidebar');
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
