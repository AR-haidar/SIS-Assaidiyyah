<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa — SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/js/app.js'])
</head>
<body class="siswa-page">

    {{-- ========================
         TOPBAR
         ======================== --}}
    <header class="siswa-topbar">
        <div class="topbar-left">

            {{-- Toggle Sidebar --}}
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Buka/Tutup Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            {{-- Brand / Logo --}}
            <a href="{{ url('/') }}" class="topbar-brand-wrap">
                <div class="topbar-logo-slot" title="Logo MI Assa'idiyyah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div class="topbar-brand-text">
                    <span class="topbar-school-name">MI Assaidiyyah</span>
                    <span class="topbar-system-name">Sistem Informasi Madrasah</span>
                </div>
            </a>
        </div>

        {{-- Kanan Topbar --}}
        <div class="topbar-right">

            {{-- Tahun Ajaran & Semester --}}
            <div class="topbar-semester-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                <span>Tahun Ajaran</span>
                <span class="semester-badge">Semester Ganjil</span>
            </div>

            {{-- Garis Pemisah --}}
            <div class="topbar-divider"></div>

            {{-- Info User --}}
            <div class="topbar-user-info">
                <span class="topbar-user-role">Siswa</span>
                <span class="topbar-user-nip">NIP. 198204122010011008</span>
            </div>

            {{-- Avatar --}}
            <div class="topbar-avatar-box" title="Profil Saya">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </div>

        </div>
    </header>

    {{-- ========================
         LAYOUT: SIDEBAR + MAIN
         ======================== --}}
    <div class="siswa-layout">

        {{-- ========================
             SIDEBAR
             ======================== --}}
        <aside class="siswa-sidebar" id="siswaSidebar">

            <nav class="sidebar-nav">
                {{-- Dashboard (Aktif) --}}
                <a href="{{ url('/siswa/dashboard') }}" class="sidebar-nav-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Data Diri --}}
                <a href="{{ url('/siswa/data-diri') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                        <circle cx="12" cy="11" r="2"/>
                        <path d="M8.5 15a4 4 0 0 1 7 0"/>
                    </svg>
                    Data Diri
                </a>

                {{-- Absensi --}}
                <a href="{{ url('/siswa/absensi') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Absensi
                </a>

                {{-- Tagihan SPP --}}
                <a href="{{ url('/siswa/tagihan-spp') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                        <line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    Tagihan SPP
                </a>

                {{-- Perpustakaan Digital --}}
                <a href="#" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    Perpustakaan Digital
                </a>
            </nav>

            {{-- Sidebar Footer: User Card --}}
            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sidebar-user-name">Siswa</div>
                        <div class="sidebar-user-meta">Kelas 5B &bull; 20241029</div>
                    </div>
                </div>
            </div>

        </aside>

        {{-- ========================
             MAIN CONTENT
             ======================== --}}
        <main class="siswa-main">

            {{-- Header Dashboard --}}
            <div class="dashboard-header">
                <div class="dashboard-header-left">
                    <h1>Dashboard Siswa</h1>
                    <p>Informasi akademik, presensi harian, dan administrasi kewajiban santri</p>
                </div>
                <div class="dashboard-header-right">
                    <div class="identity-card-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <circle cx="12" cy="11" r="2"/>
                            <path d="M8.5 15a4 4 0 0 1 7 0"/>
                        </svg>
                        <div>
                            <span class="identity-card-label">Kartu Identitas Digital</span>
                            <span class="identity-card-value">Siswa Aktif</span>
                        </div>
                    </div>
                    <div class="btn-refresh-icon" title="Segarkan Data">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                            <path d="M21 3v5h-5"/>
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                            <path d="M8 16H3v5"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card Identitas Santri --}}
            <div class="card-identitas">
                <div class="card-section-header">
                    <div>
                        <div class="card-section-title">Identitas Santri</div>
                        <div class="card-section-sub">Data primer terdaftar pada database pokok madrasah (EMIS/Simpatika)</div>
                    </div>
                    <div class="sync-badge">
                        <span class="sync-dot"></span>
                        SINKRONISASI SISTEM
                    </div>
                </div>
                <div class="card-identitas-body">

                    {{-- Pasfoto --}}
                    <div class="pasfoto-box">
                        <div class="pasfoto-frame">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                            </svg>
                            <span class="pasfoto-label">PASFOTO</span>
                        </div>
                        <span class="pasfoto-format">Format 3×4</span>
                    </div>

                    {{-- Grid Data --}}
                    <div class="identitas-data-grid">
                        <div class="identitas-field">
                            <div class="field-key">Nama Lengkap</div>
                            <div class="field-value">—</div>
                        </div>
                        <div class="identitas-field">
                            <div class="field-key">NIS / NISN</div>
                            <div class="field-value">—</div>
                        </div>
                        <div class="identitas-field">
                            <div class="field-key">Kelas &amp; Rombel</div>
                            <div class="field-value">—</div>
                        </div>
                        <div class="identitas-field">
                            <div class="field-key">Wali Kelas</div>
                            <div class="field-value">—</div>
                        </div>
                        <div class="identitas-field">
                            <div class="field-key">Tahun Ajaran</div>
                            <div class="field-value">—</div>
                        </div>
                        <div class="identitas-field">
                            <div class="field-key">Status Akademik</div>
                            <div class="field-value">—</div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Layout 2 Kolom: Presensi + SPP --}}
            <div class="dashboard-two-col">

                {{-- Card Ringkasan Presensi --}}
                <div class="card-presensi">
                    <div class="card-section-header">
                        <div>
                            <div class="card-section-title">Ringkasan Presensi</div>
                            <div class="card-section-sub">Semester Berjalan (Aktif)</div>
                        </div>
                    </div>

                    {{-- 4 Stat Berjajar --}}
                    <div class="presensi-stats-row">
                        <div class="presensi-stat-item hadir">
                            <span class="presensi-stat-label">Hadir</span>
                            <span class="presensi-stat-value">—</span>
                        </div>
                        <div class="presensi-stat-item izin">
                            <span class="presensi-stat-label">Izin</span>
                            <span class="presensi-stat-value">—</span>
                        </div>
                        <div class="presensi-stat-item sakit">
                            <span class="presensi-stat-label">Sakit</span>
                            <span class="presensi-stat-value">—</span>
                        </div>
                        <div class="presensi-stat-item alpa">
                            <span class="presensi-stat-label">ALPA</span>
                            <span class="presensi-stat-value">—</span>
                        </div>
                    </div>

                    {{-- Progress Bar Kehadiran --}}
                    <div class="presensi-progress-box">
                        <div class="progress-header">
                            <span class="progress-title">Tingkat Kehadiran Kumulatif</span>
                            <span class="progress-percent">—%</span>
                        </div>
                        <div class="progress-bar-track">
                            <div class="progress-bar-fill" style="width: 0%;"></div>
                        </div>
                        <div class="progress-footer">
                            <span>Target madrasah: min. 85%</span>
                            <span>Total: — Hari Efektif</span>
                        </div>
                        <div class="progress-divider"></div>
                        <div class="presensi-info-row">
                            <div class="presensi-info-note">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                Data presensi diperbarui setiap jam operasional berakhir
                            </div>
                            <a href="#" class="presensi-detail-link">Lihat Rincian Harian</a>
                        </div>
                    </div>
                </div>

                {{-- Card Status Tagihan SPP --}}
                <div class="card-spp">
                    <div class="card-section-header">
                        <div>
                            <div class="card-section-title">Status Tagihan SPP</div>
                        </div>
                    </div>
                    <div class="spp-body">
                        <div class="spp-field-group">
                            <span class="spp-field-key">Status Pembayaran Bulan Ini</span>
                            <span class="spp-field-value">—</span>
                        </div>
                        <div class="spp-row-two">
                            <div class="spp-field-group">
                                <span class="spp-field-key">Nominal Tagihan</span>
                                <span class="spp-field-value big">Rp —</span>
                            </div>
                            <div class="spp-field-group">
                                <span class="spp-field-key">Batas Waktu Jatuh Tempo</span>
                                <span class="spp-field-value">—</span>
                            </div>
                        </div>
                        <div class="spp-divider"></div>
                        <div class="spp-field-group">
                            <span class="spp-field-key">Kode Pembayaran Siswa</span>
                            <span class="spp-field-value">—</span>
                        </div>
                    </div>
                    <div class="spp-actions">
                        <a href="#" class="btn-spp btn-spp-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            Rincian Tagihan
                        </a>
                        <a href="#" class="btn-spp btn-spp-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            Riwayat Setoran
                        </a>
                    </div>
                </div>

            </div>

            {{-- Card Pengumuman Madrasah --}}
            <div class="card-pengumuman">
                <div class="pengumuman-header">
                    <div class="pengumuman-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 8.01V16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1"/>
                            <path d="m22 8-10 5L2 8"/>
                            <path d="M6 4h12a2 2 0 0 1 2 2v.5"/>
                        </svg>
                    </div>
                    <div class="pengumuman-header-text">
                        <h3>Pengumuman Madrasah</h3>
                        <p>Warta akademik dan agenda kesiswaan resmi</p>
                    </div>
                    <span class="pengumuman-badge">Papan Informasi</span>
                </div>

                {{-- Empty State --}}
                <div class="pengumuman-empty">
                    <div class="empty-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </div>
                    <h4 class="empty-title">Belum Ada Pengumuman Baru</h4>
                    <p class="empty-desc">
                        Pengumuman resmi dari madrasah akan ditampilkan di sini. Silakan periksa kembali secara berkala untuk pembaruan agenda.
                    </p>
                    <div class="empty-time-note">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Terakhir diperiksa: Hari ini
                    </div>
                </div>

                <div class="pengumuman-footer">
                    <span class="arsip-label">Arsip pengumuman semester lalu</span>
                    <a href="#" class="arsip-link">
                        Buka Arsip Dokumen
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
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
