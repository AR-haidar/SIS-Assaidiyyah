<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa — SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/css/siswa/absensi.css', 'resources/js/app.js'])
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
                <a href="{{ url('/siswa/data-diri') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="12" cy="11" r="2"/><path d="M8.5 15a4 4 0 0 1 7 0"/>
                    </svg>
                    Data Diri
                </a>
                <a href="{{ url('/siswa/absensi') }}" class="sidebar-nav-item active">
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

            {{-- 1. Header Halaman --}}
            <div class="abs-page-header">
                <h1 class="abs-page-title">Absensi Siswa</h1>
                <p class="abs-page-sub">Informasi rekapitulasi absen siswa</p>
            </div>

            {{-- 2. Card Info Siswa Ringkas (4 Kolom Horizontal) --}}
            <div class="abs-student-info-card">
                <div class="abs-info-col">
                    <span class="abs-info-key">NAMA SISWA</span>
                    <span class="abs-info-val">—</span>
                </div>
                <div class="abs-info-col">
                    <span class="abs-info-key">KELAS</span>
                    <span class="abs-info-val">—</span>
                </div>
                <div class="abs-info-col">
                    <span class="abs-info-key">NISN</span>
                    <span class="abs-info-val">—</span>
                </div>
                <div class="abs-info-col">
                    <span class="abs-info-key">WALI KELAS</span>
                    <span class="abs-info-val">—</span>
                </div>
            </div>

            {{-- 3. Card Filter Periode --}}
            <div class="abs-filter-card">
                <div class="abs-filter-group">
                    <label class="abs-filter-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        Semester Akademik
                    </label>
                    <div class="abs-select-wrapper">
                        <select class="abs-select-input">
                            <option value="ganjil" selected>Semester Ganjil</option>
                            <option value="genap">Semester Genap</option>
                        </select>
                        <span class="abs-select-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="abs-filter-group">
                    <label class="abs-filter-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        Bulan
                    </label>
                    <div class="abs-select-wrapper">
                        <select class="abs-select-input">
                            <option value="all" selected>Semua Bulan</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <span class="abs-select-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <button type="button" class="btn-terapkan-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>

            {{-- 4. 4 Kartu Status Kehadiran (Grid 4 Kolom) --}}
            <div class="abs-stat-grid">
                
                {{-- HADIR --}}
                <div class="abs-stat-box hadir">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">HADIR</span>
                    </div>
                    <span class="abs-stat-value-text">— Hari</span>
                </div>

                {{-- IZIN --}}
                <div class="abs-stat-box izin">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">IZIN</span>
                    </div>
                    <span class="abs-stat-value-text">— Hari</span>
                </div>

                {{-- SAKIT --}}
                <div class="abs-stat-box sakit">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">SAKIT</span>
                    </div>
                    <span class="abs-stat-value-text">— Hari</span>
                </div>

                {{-- ALPA --}}
                <div class="abs-stat-box alpa">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">ALPA</span>
                    </div>
                    <span class="abs-stat-value-text">— Hari</span>
                </div>

            </div>

            {{-- 5. Card Tabel Riwayat Kehadiran Harian --}}
            <div class="abs-table-card">
                <div class="abs-table-header-row">
                    <h2 class="abs-table-title">Riwayat Kehadiran Harian</h2>
                    <div class="abs-sync-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                            <path d="M21 3v5h-5"/>
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                            <path d="M8 16H3v5"/>
                        </svg>
                        <span>Sinkronisasi Realtime EMIS</span>
                    </div>
                </div>

                <div class="abs-table-container">
                    <table class="abs-table">
                        <thead>
                            <tr>
                                <th>TANGGAL PRESENSI</th>
                                <th>STATUS KEHADIRAN</th>
                                <th>KETERANGAN / CATATAN GURU</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- Area Kosong Bersih Sesuai Foto --}}
                    <div class="abs-table-empty-area"></div>
                </div>
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
