<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa — SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/css/siswa/absensi.css', 'resources/js/app.js'])
</head>

<body class="siswa-page">
    @php
        $riwayatPresensi = collect($riwayatPresensi ?? []);
        $jumlahHadir =
            $jumlahHadir ??
            $riwayatPresensi
                ->filter(fn($item) => ($item->status?->value ?? (string) $item->status) === 'hadir')
                ->count();
        $jumlahIzin =
            $jumlahIzin ??
            $riwayatPresensi
                ->filter(fn($item) => ($item->status?->value ?? (string) $item->status) === 'izin')
                ->count();
        $jumlahSakit =
            $jumlahSakit ??
            $riwayatPresensi
                ->filter(fn($item) => ($item->status?->value ?? (string) $item->status) === 'sakit')
                ->count();
        $jumlahAlpa =
            $jumlahAlpa ??
            $riwayatPresensi
                ->filter(fn($item) => ($item->status?->value ?? (string) $item->status) === 'alpa')
                ->count();
        $memilikiData = isset($siswa) && $siswa !== null;
        $rombelSiswa = $memilikiData ? $siswa->keanggotaanRombel->first()?->rombel?->nama ?? '—' : '—';
        $waliKelasNama =
            $waliKelasNama ??
            ($memilikiData ? $siswa->keanggotaanRombel->first()?->rombel?->waliKelas?->nama_lengkap ?? '—' : '—');
    @endphp

    {{-- TOPBAR --}}
    <header class="siswa-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Buka/Tutup Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <a href="{{ url('/') }}" class="topbar-brand-wrap">
                <div class="topbar-logo-slot">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
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
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
                <span>Tahun Ajaran</span>
                <span class="semester-badge">{{ $semesterAktif ?? 'Semester Ganjil' }}</span>
            </div>
            <div class="topbar-divider"></div>
            <div class="topbar-user-info">
                <span class="topbar-user-role">Siswa</span>
                <span
                    class="topbar-user-nip">{{ $memilikiData ? $siswa->nisn ?? '—' : 'NIP. 198204122010011008' }}</span>
            </div>
            <div class="topbar-avatar-box" title="Profil Saya">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                </svg>
            </div>
        </div>
    </header>

    <div class="siswa-layout">

        {{-- SIDEBAR --}}
        <aside class="siswa-sidebar" id="siswaSidebar">
            <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="m15 18-6-6 6-6" />
                    <path d="M20 12H9" />
                    <path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4" />
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
            <nav class="sidebar-nav">
                <a href="{{ url('/siswa/dashboard') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ url('/siswa/data-diri') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <circle cx="12" cy="11" r="2" />
                        <path d="M8.5 15a4 4 0 0 1 7 0" />
                    </svg>
                    Data Diri
                </a>
                <a href="{{ url('/siswa/absensi') }}" class="sidebar-nav-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    Absensi
                </a>
                <a href="{{ url('/siswa/tagihan-spp') }}" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                    </svg>
                    Tagihan
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                        </svg>
                    </div>
                    <div>
                        <div class="sidebar-user-name">
                            {{ $memilikiData ? $siswa->nama_lengkap ?? 'Siswa' : 'Siswa' }}</div>
                        <div class="sidebar-user-meta">{{ $rombelSiswa }} &bull;
                            {{ $memilikiData ? $siswa->nisn ?? '20241029' : '20241029' }}</div>
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
                    <span class="abs-info-val">{{ $memilikiData ? $siswa->nama_lengkap ?? '—' : '—' }}</span>
                </div>
                <div class="abs-info-col">
                    <span class="abs-info-key">KELAS</span>
                    <span class="abs-info-val">{{ $rombelSiswa }}</span>
                </div>
                <div class="abs-info-col">
                    <span class="abs-info-key">NISN</span>
                    <span class="abs-info-val">{{ $memilikiData ? $siswa->nisn ?? '—' : '—' }}</span>
                </div>
                <div class="abs-info-col">
                    <span class="abs-info-key">WALI KELAS</span>
                    <span class="abs-info-val">{{ $waliKelasNama }}</span>
                </div>
            </div>

            {{-- 3. Card Filter Periode --}}
            <form method="GET" action="{{ url('/siswa/absensi') }}" class="abs-filter-card">
                <div class="abs-filter-group">
                    <label class="abs-filter-label" for="filterSemester">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                            <line x1="16" x2="16" y1="2" y2="6" />
                            <line x1="8" x2="8" y1="2" y2="6" />
                            <line x1="3" x2="21" y1="10" y2="10" />
                        </svg>
                        Semester Akademik
                    </label>
                    <div class="abs-select-wrapper">
                        <select id="filterSemester" name="semester" class="abs-select-input">
                            <option value="ganjil" @selected(request('semester', 'ganjil') === 'ganjil')>Semester Ganjil</option>
                            <option value="genap" @selected(request('semester') === 'genap')>Semester Genap</option>
                        </select>
                        <span class="abs-select-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="abs-filter-group">
                    <label class="abs-filter-label" for="filterBulan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                            <line x1="16" x2="16" y1="2" y2="6" />
                            <line x1="8" x2="8" y1="2" y2="6" />
                            <line x1="3" x2="21" y1="10" y2="10" />
                        </svg>
                        Bulan
                    </label>
                    <div class="abs-select-wrapper">
                        <select id="filterBulan" name="bulan" class="abs-select-input">
                            <option value="all" @selected(request('bulan', 'all') === 'all')>Semua Bulan</option>
                            <option value="7" @selected(request('bulan') === '7')>Juli</option>
                            <option value="8" @selected(request('bulan') === '8')>Agustus</option>
                            <option value="9" @selected(request('bulan') === '9')>September</option>
                            <option value="10" @selected(request('bulan') === '10')>Oktober</option>
                            <option value="11" @selected(request('bulan') === '11')>November</option>
                            <option value="12" @selected(request('bulan') === '12')>Desember</option>
                            <option value="1" @selected(request('bulan') === '1')>Januari</option>
                            <option value="2" @selected(request('bulan') === '2')>Februari</option>
                            <option value="3" @selected(request('bulan') === '3')>Maret</option>
                            <option value="4" @selected(request('bulan') === '4')>April</option>
                            <option value="5" @selected(request('bulan') === '5')>Mei</option>
                            <option value="6" @selected(request('bulan') === '6')>Juni</option>
                        </select>
                        <span class="abs-select-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-terapkan-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </form>

            {{-- 4. 4 Kartu Status Kehadiran (Grid 4 Kolom) --}}
            <div class="abs-stat-grid">
                <div class="abs-stat-box hadir">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">HADIR</span>
                    </div>
                    <span class="abs-stat-value-text">{{ $memilikiData ? $jumlahHadir . ' Hari' : '— Hari' }}</span>
                </div>

                <div class="abs-stat-box izin">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">IZIN</span>
                    </div>
                    <span class="abs-stat-value-text">{{ $memilikiData ? $jumlahIzin . ' Hari' : '— Hari' }}</span>
                </div>

                <div class="abs-stat-box sakit">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">SAKIT</span>
                    </div>
                    <span class="abs-stat-value-text">{{ $memilikiData ? $jumlahSakit . ' Hari' : '— Hari' }}</span>
                </div>

                <div class="abs-stat-box alpa">
                    <div class="abs-stat-header">
                        <span class="abs-status-dot"></span>
                        <span class="abs-status-title">ALPA</span>
                    </div>
                    <span class="abs-stat-value-text">{{ $memilikiData ? $jumlahAlpa . ' Hari' : '— Hari' }}</span>
                </div>
            </div>

            {{-- 5. Card Tabel Riwayat Kehadiran Harian --}}
            <div class="abs-table-card">
                <div class="abs-table-header-row">
                    <h2 class="abs-table-title">Riwayat Kehadiran Harian</h2>
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
                        @if ($riwayatPresensi->isNotEmpty())
                            <tbody>
                                @foreach ($riwayatPresensi as $log)
                                    @php
                                        $kodeStatus = $log->status?->value ?? (string) $log->status;
                                        $labelStatus = method_exists($log->status, 'label')
                                            ? $log->status->label()
                                            : ucfirst($kodeStatus);
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>{{ $labelStatus }}</td>
                                        <td>{{ $log->keterangan ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                    @if ($riwayatPresensi->isEmpty())
                        <div class="abs-table-empty-area"></div>
                    @endif
                </div>
            </div>

        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('siswaSidebar');
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>

</html>
