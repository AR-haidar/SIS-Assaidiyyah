<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan & SPP - SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/js/app.js'])
</head>
<body class="siswa-page wali-page">
    <header class="siswa-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Buka/Tutup Sidebar" aria-label="Buka atau tutup sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <a href="{{ url('/') }}" class="topbar-brand-wrap">
                <div class="topbar-logo-slot" title="Logo MI Assa'idiyyah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                <span>Tahun Ajaran</span>
                <span class="semester-badge">Semester Ganjil</span>
            </div>
            <div class="topbar-divider"></div>
            <div class="topbar-user-info">
                <span class="topbar-user-role">Wali Kelas</span>
                <span class="topbar-user-nip">NIP. 198204122010011008</span>
            </div>
            <div class="topbar-avatar-box" title="Profil Wali Kelas">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </div>
        </div>
    </header>

    <div class="siswa-layout">
        <aside class="siswa-sidebar" id="waliSidebar">
            <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/><path d="M20 12H9"/><path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
            <nav class="sidebar-nav" aria-label="Navigasi wali kelas">
                <a href="{{ url('/wali-kelas/dashboard') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ url('/wali-kelas/data-siswa') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Data Siswa
                </a>
                <a href="{{ url('/wali-kelas/presensi-absensi') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/>
                    </svg>
                    Presensi &amp; Absensi
                </a>
                <a href="{{ url('/wali-kelas/keuangan-spp') }}" class="sidebar-nav-item active" aria-current="page">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><path d="M16 15h2"/>
                    </svg>
                    Keuangan &amp; SPP
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sidebar-user-name">Wali Kelas</div>
                        <div class="sidebar-user-meta">Kelas -</div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="siswa-main wali-main wali-finance-main">
            <header class="wali-finance-titlebar">
                <div class="wali-page-heading">
                    <h1>Data Siswa &amp; Status Pembayaran</h1>
                    <p>Pemantauan riwayat administrasi SPP</p>
                </div>
                <div class="wali-finance-actions">
                    <span class="wali-finance-semester"><i></i>Semester Ganjil 2024/2025</span>
                    <button type="button" class="wali-finance-download">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh Daftar Siswa
                    </button>
                </div>
            </header>

            <section class="wali-finance-stats" aria-label="Ringkasan administrasi SPP">
                <article class="wali-finance-stat-card"><span>TOTAL SISWA TERDATA</span><p>-<small>Siswa</small></p></article>
                <article class="wali-finance-stat-card"><span>SISWA TERVERIFIKASI</span><p>- <small>Siswa</small></p></article>
                <article class="wali-finance-stat-card"><span>BEBAN TUNGGAKAN BERJALAN</span><p>- <small>Siswa</small></p></article>
            </section>

            <section class="wali-finance-filter" aria-label="Pencarian dan filter pembayaran">
                <label class="wali-finance-field">
                    <span>Pencarian Data</span>
                    <span class="wali-finance-search-control">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                        <input id="financeSearch" type="search" placeholder="Cari nama siswa..." aria-label="Cari nama siswa">
                    </span>
                </label>
                <label class="wali-finance-field">
                    <span>Status Pembayaran</span>
                    <select id="paymentStatus" aria-label="Filter status pembayaran">
                        <option value="all">Semua Status Pembayaran</option>
                        <option value="verified">Terverifikasi</option>
                        <option value="pending">Menunggu Verifikasi</option>
                        <option value="unpaid">Belum Bayar</option>
                    </select>
                </label>
                <label class="wali-finance-field">
                    <span>Tahun Ajaran</span>
                    <select id="schoolYear" aria-label="Filter tahun ajaran">
                        <option value="2024/2025">2024/2025 (Aktif)</option>
                        <option value="2023/2024">2023/2024</option>
                    </select>
                </label>
                <div class="wali-finance-filter-actions">
                    <button type="button" class="wali-finance-reset" id="resetFinanceFilters">Reset</button>
                    <button type="button" class="wali-finance-apply">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Terapkan Filter
                    </button>
                </div>
            </section>

            <section class="wali-finance-table-card" aria-label="Daftar status pembayaran siswa">
                <div class="wali-finance-table-scroll">
                    <table class="wali-finance-table">
                        <thead><tr><th>Nama Siswa</th><th>NIS / NISN</th><th>Kelas</th><th>Status Tagihan</th><th>Aksi</th></tr></thead>
                    </table>
                </div>
                <div class="wali-finance-empty">
                    <strong>Belum Ada Data Siswa Dimuat</strong>
                    <p>Pilih kelas atau masukkan kata kunci pencarian untuk menampilkan<br class="finance-desktop-break"> direktori siswa dan riwayat tagihannya.</p>
                </div>
                <footer class="wali-finance-pagination">
                    <span>Menampilkan 0 dari 0 siswa</span>
                    <nav aria-label="Navigasi halaman tabel">
                        <button type="button" aria-label="Halaman pertama" disabled>|<</button>
                        <button type="button" aria-label="Halaman sebelumnya" disabled><</button>
                        <span class="current-page">1</span>
                        <button type="button" aria-label="Halaman selanjutnya" disabled>></button>
                        <button type="button" aria-label="Halaman terakhir" disabled>>|</button>
                    </nav>
                </footer>
            </section>
        </main>
    </div>

    <script>
        const financeSearch = document.getElementById('financeSearch');
        const paymentStatus = document.getElementById('paymentStatus');
        const schoolYear = document.getElementById('schoolYear');
        document.getElementById('resetFinanceFilters').addEventListener('click', function () {
            financeSearch.value = '';
            paymentStatus.value = 'all';
            schoolYear.value = '2024/2025';
        });
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('waliSidebar');
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>