@php
    $waliNama = $waliNama ?? auth()->user()?->nama ?? 'Wali Kelas';
    $waliNip = $waliNip ?? ($pegawai->nip ?? $pegawai->nuptk ?? '-');
    $rombelLabel = $rombelLabel ?? ($rombel->nama ?? '-');
    $tahunAjaranLabel = $tahunAjaranLabel ?? ($tahunAjaran->nama ?? '-');
    $semesterLabel = $semesterLabel ?? ($semester->nama ?? 'Semester Ganjil');

    $statistik = $statistik ?? [];
    $daftarPengumuman = $daftarPengumuman ?? collect();
    $statistikPengumuman = $statistikPengumuman ?? [
        'total' => $daftarPengumuman->count(),
        'active' => $daftarPengumuman->count(),
        'class' => 0,
        'archive' => 0,
    ];

    $dashboardUrl = \Illuminate\Support\Facades\Route::has('wali_kelas.dashboard')
        ? route('wali_kelas.dashboard')
        : url('/wali-kelas/dashboard');
    $dataSiswaUrl = \Illuminate\Support\Facades\Route::has('wali_kelas.siswa.index')
        ? route('wali_kelas.siswa.index')
        : url('/wali-kelas/data-siswa');
    $presensiUrl = \Illuminate\Support\Facades\Route::has('wali_kelas.presensi.index')
        ? route('wali_kelas.presensi.index')
        : url('/wali-kelas/presensi-absensi');
    $keuanganUrl = \Illuminate\Support\Facades\Route::has('wali_kelas.keuangan.index')
        ? route('wali_kelas.keuangan.index')
        : url('/wali-kelas/keuangan-spp');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Wali Kelas - SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
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
                <span class="semester-badge">{{ $semesterLabel }}</span>
            </div>
            <div class="topbar-divider"></div>
            <div class="topbar-user-info">
                <span class="topbar-user-role">Wali Kelas</span>
                <span class="topbar-user-nip">NIP. {{ $waliNip }}</span>
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
                <a href="{{ $dashboardUrl }}" class="sidebar-nav-item active" aria-current="page">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ $dataSiswaUrl }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Data Siswa
                </a>
                <a href="{{ $presensiUrl }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/>
                    </svg>
                    Presensi &amp; Absensi
                </a>
                <a href="{{ $keuanganUrl }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><path d="M16 15h2"/>
                    </svg>
                    Keuangan &amp; Tagihan
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
                        <div class="sidebar-user-name">{{ $waliNama }}</div>
                        <div class="sidebar-user-meta">{{ $rombelLabel !== '-' ? $rombelLabel : 'Kelas -' }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="siswa-main wali-main">
            <header class="wali-page-heading">
                <h1>Dashboard Wali Kelas</h1>
                <p>Pengelolaan presensi, rekapitulasi akademik, dan aktivitas kelas binaan</p>
            </header>

            <section class="wali-class-summary" aria-label="Identitas wali kelas">
                <div class="wali-class-title">
                    <span>IDENTITAS WALI KELAS</span>
                    <h2>Ringkasan Kelas Binaan</h2>
                </div>
                <div class="wali-class-meta">
                    <span>Kelas: <strong>{{ $rombelLabel }}</strong></span>
                    <span>Tahun Pelajaran: <strong>{{ $tahunAjaranLabel }}</strong></span>
                    <span>Semester: <strong>{{ $semesterLabel }}</strong></span>
                </div>
            </section>

            <section class="wali-stat-grid" aria-label="Ringkasan siswa dan kehadiran">
                <article class="wali-stat-card"><h2>Total Siswa Terdaftar</h2><p>{{ $statistik['total_siswa'] ?? '-' }}</p></article>
                <article class="wali-stat-card"><h2>Hadir Hari Ini</h2><p>{{ $statistik['hadir_hari_ini'] ?? '-' }}</p></article>
                <article class="wali-stat-card"><h2>Izin / Sakit</h2><p>{{ $statistik['izin_sakit'] ?? '-' }}</p></article>
                <article class="wali-stat-card"><h2>Tanpa Keterangan</h2><p>{{ $statistik['alpa'] ?? '-' }}</p></article>
            </section>

            <section class="dashboard-announcement-panel" aria-labelledby="waliAnnouncementsTitle">
                <h2 id="waliAnnouncementsTitle">Pengumuman Madrasah</h2>
                @include('shared.announcement-controls', [
                    'announcementStats' => [
                        ['key' => 'total', 'label' => 'Total Pengumuman', 'value' => $statistikPengumuman['total'] ?? 0],
                        ['key' => 'active', 'label' => 'Pengumuman Aktif', 'value' => $statistikPengumuman['active'] ?? 0],
                        ['key' => 'class', 'label' => 'Informasi Kelas', 'value' => $statistikPengumuman['class'] ?? 0],
                        ['key' => 'archive', 'label' => 'Arsip Pengumuman', 'value' => $statistikPengumuman['archive'] ?? 0],
                    ],
                    'announcementTabs' => [
                        ['value' => 'all', 'label' => 'Semua Warta', 'count' => $statistikPengumuman['total'] ?? 0],
                        ['value' => 'class', 'label' => 'Kelas Binaan', 'count' => $statistikPengumuman['class'] ?? 0],
                        ['value' => 'academic', 'label' => 'Akademik', 'count' => $statistikPengumuman['academic'] ?? 0],
                        ['value' => 'general', 'label' => 'Umum', 'count' => $statistikPengumuman['general'] ?? 0],
                    ],
                    'announcementCategories' => ['Kelas Binaan', 'Akademik', 'Kesiswaan', 'Informasi Umum'],
                    'announcementSearch' => 'Cari pengumuman kelas dan madrasah',
                ])

                @if ($daftarPengumuman->isNotEmpty())
                    <div class="shared-announcement-list">
                        @foreach ($daftarPengumuman as $item)
                            <article class="shared-announcement-item">
                                <div>
                                    <small>{{ $item->kategori?->nama ?? $item->kategori_nama ?? 'Informasi Umum' }} &bull; {{ !empty($item->tanggal_terbit) ? \Illuminate\Support\Carbon::parse($item->tanggal_terbit)->translatedFormat('d M Y') : '-' }}</small>
                                    <h3>{{ $item->judul }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) ($item->isi ?? '')), 180) }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                <div class="shared-announcement-empty" @if ($daftarPengumuman->isNotEmpty()) hidden @endif>
                    <span class="shared-announcement-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z"/><path d="M18 9a4 4 0 0 1 0 6"/></svg></span>
                    <strong>Belum Ada Pengumuman Diterbitkan</strong>
                    <p>Pengumuman resmi untuk siswa dan wali kelas akan ditampilkan di sini jika tersedia.</p>
                </div>
            </section>

            <section class="wali-students-card" aria-labelledby="waliStudentsTitle">
                <div class="wali-table-toolbar">
                    <div class="wali-table-heading">
                        <h2 id="waliStudentsTitle">Daftar Siswa Kelas Binaan</h2>
                        <p>Kelola rekap administratif, nilai, dan rekam presensi peserta didik</p>
                    </div>
                    <form method="GET" action="{{ $dashboardUrl }}" class="wali-search-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                        </svg>
                        <input type="search" name="search" value="{{ request('search', '') }}" placeholder="Cari siswa atau NISN..." aria-label="Cari siswa atau NISN">
                    </form>
                    <button class="wali-export-button" type="button" onclick="window.print()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Export Rekap
                    </button>
                </div>

                <div class="wali-table-scroll">
                    <table class="wali-students-table">
                        <thead>
                            <tr><th>No</th><th>NISN</th><th>Nama Siswa</th><th>Jenis Kelamin</th><th>Kehadiran (%)</th><th>Status Tagihan</th></tr>
                        </thead>
                        <tbody>
                            @if (isset($daftarSiswa))
                                @forelse ($daftarSiswa as $index => $siswa)
                                    @php
                                        $nomor = method_exists($daftarSiswa, 'firstItem')
                                            ? ($daftarSiswa->firstItem() + $index)
                                            : ($index + 1);
                                        $persenHadirSiswa = $siswa->persentase_kehadiran ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $nomor }}</td>
                                        <td>{{ $siswa->nisn ?? '-' }}</td>
                                        <td>{{ $siswa->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $siswa->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $persenHadirSiswa !== null ? number_format((float) $persenHadirSiswa, 1) . ' %' : '- %' }}</td>
                                        <td>{{ $siswa->status_tagihan ?? 'Lunas' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Belum ada data siswa pada kelas binaan.</td>
                                    </tr>
                                @endforelse
                            @else
                                @for ($row = 1; $row <= 5; $row++)
                                    <tr>
                                        <td>{{ $row }}</td>
                                        <td><span class="wali-skeleton wali-skeleton-id"></span></td>
                                        <td><span class="wali-name-placeholder"><span class="wali-skeleton wali-skeleton-avatar"></span><span class="wali-skeleton wali-skeleton-name"></span></span></td>
                                        <td>-</td>
                                        <td><span class="wali-attendance-placeholder"><span class="wali-skeleton wali-skeleton-progress"></span><span>- %</span></span></td>
                                        <td><span class="wali-skeleton wali-skeleton-status"></span></td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('waliSidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
            });
        }
    </script>
</body>
</html> 
