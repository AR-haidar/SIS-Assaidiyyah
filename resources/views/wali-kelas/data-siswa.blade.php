@php
    $waliNama = $waliNama ?? (auth()->user()?->nama ?? 'Wali Kelas');
    $waliNip = $waliNip ?? ($pegawai->nip ?? ($pegawai->nuptk ?? '-'));
    $rombelLabel = $rombelLabel ?? ($rombel->nama ?? 'Kelas -');
    $tahunAjaranLabel = $tahunAjaranLabel ?? ($tahunAjaran->nama ?? 'Semester Ganjil');
    $daftarSiswa = $daftarSiswa ?? collect();
    $statistik = $statistik ?? [
        'total' => $daftarSiswa->count(),
        'laki_laki' => $daftarSiswa
            ->filter(fn($s) => strtolower((string) ($s->jenis_kelamin ?? '')) === 'laki-laki')
            ->count(),
        'perempuan' => $daftarSiswa
            ->filter(fn($s) => strtolower((string) ($s->jenis_kelamin ?? '')) === 'perempuan')
            ->count(),
    ];
    $filterSearch = (string) request('search', '');
    $filterStatus = (string) request('status', 'all');
    $filterGender = (string) request('gender', 'all');
    $indexUrl = \Illuminate\Support\Facades\Route::has('wali_kelas.siswa.index')
        ? route('wali_kelas.siswa.index')
        : (\Illuminate\Support\Facades\Route::has('wali_kelas.data_siswa.index')
            ? route('wali_kelas.data_siswa.index')
            : url('/wali-kelas/data-siswa'));
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/js/app.js'])
</head>

<body class="siswa-page wali-page">
    <header class="siswa-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Buka/Tutup Sidebar"
                aria-label="Buka atau tutup sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <a href="{{ url('/') }}" class="topbar-brand-wrap">
                <div class="topbar-logo-slot" title="Logo MI Assa'idiyyah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
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
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
                <span>Tahun Ajaran</span>
                <span class="semester-badge">{{ $tahunAjaranLabel }}</span>
            </div>
            <div class="topbar-divider"></div>
            <div class="topbar-user-info">
                <span class="topbar-user-role">Wali Kelas</span>
                <span class="topbar-user-nip">NIP. {{ $waliNip }}</span>
            </div>
            <div class="topbar-avatar-box" title="Profil Wali Kelas">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                </svg>
            </div>
        </div>
    </header>

    <div class="siswa-layout">
        <aside class="siswa-sidebar" id="waliSidebar">
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
            <nav class="sidebar-nav" aria-label="Navigasi wali kelas">
                <a href="{{ \Illuminate\Support\Facades\Route::has('wali_kelas.dashboard') ? route('wali_kelas.dashboard') : url('/wali-kelas/dashboard') }}"
                    class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ $indexUrl }}" class="sidebar-nav-item active" aria-current="page">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="10" cy="7" r="4" />
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Data Siswa
                </a>
                <a href="{{ \Illuminate\Support\Facades\Route::has('wali_kelas.presensi.index') ? route('wali_kelas.presensi.index') : url('/wali-kelas/presensi-absensi') }}"
                    class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <path d="m9 16 2 2 4-4" />
                    </svg>
                    Presensi &amp; Absensi
                </a>
                <a href="{{ \Illuminate\Support\Facades\Route::has('wali_kelas.keuangan.index') ? route('wali_kelas.keuangan.index') : url('/wali-kelas/keuangan-spp') }}"
                    class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                        <path d="M16 15h2" />
                    </svg>
                    Keuangan &amp; Tagihan
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                        </svg>
                    </div>
                    <div>
                        <div class="sidebar-user-name">{{ $waliNama }}</div>
                        <div class="sidebar-user-meta">{{ $rombelLabel }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="siswa-main wali-main wali-data-main">
            <header class="wali-page-heading wali-data-heading">
                <h1>Data Siswa Kelas Binaan</h1>
            </header>

            <section class="wali-data-stat-grid" aria-label="Ringkasan data siswa">
                <article class="wali-data-stat-card"><span>TOTAL SISWA</span>
                    <p>{{ $statistik['total'] ?? 0 }} Siswa</p>
                </article>
                <article class="wali-data-stat-card"><span>SISWA LAKI-LAKI</span>
                    <p>{{ $statistik['laki_laki'] ?? 0 }} Siswa</p>
                </article>
                <article class="wali-data-stat-card"><span>SISWA PEREMPUAN</span>
                    <p>{{ $statistik['perempuan'] ?? 0 }} Siswi</p>
                </article>
            </section>

            <form method="GET" action="{{ $indexUrl }}" class="wali-data-filter"
                aria-label="Filter data siswa">
                <label class="wali-data-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-4-4" />
                    </svg>
                    <input id="studentSearch" type="search" name="search" value="{{ $filterSearch }}"
                        placeholder="Cari nama siswa atau NISN..." aria-label="Cari nama siswa">
                </label>
                <label class="wali-data-select-label">
                    <span class="sr-only">Status kesiswaan</span>
                    <select id="studentStatus" name="status" aria-label="Filter status kesiswaan">
                        <option value="all" @selected($filterStatus === 'all')>Semua Status Kesiswaan</option>
                        <option value="aktif" @selected($filterStatus === 'aktif')>Aktif</option>
                        <option value="pindah" @selected($filterStatus === 'pindah')>Pindah</option>
                        <option value="lulus" @selected($filterStatus === 'lulus')>Lulus</option>
                    </select>
                </label>
                <label class="wali-data-select-label">
                    <span class="sr-only">Jenis kelamin</span>
                    <select id="studentGender" name="gender" aria-label="Filter jenis kelamin">
                        <option value="all" @selected($filterGender === 'all')>Jenis Kelamin (Semua)</option>
                        <option value="laki-laki" @selected($filterGender === 'laki-laki')>Laki-laki</option>
                        <option value="perempuan" @selected($filterGender === 'perempuan')>Perempuan</option>
                    </select>
                </label>
                <button class="wali-data-apply" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                    Terapkan Filter
                </button>
                <a class="wali-data-reset" id="resetStudentFilters" href="{{ $indexUrl }}">Reset</a>
            </form>

            <section class="wali-data-table-card" aria-label="Daftar siswa kelas binaan">
                @if ($daftarSiswa->isNotEmpty())
                    <div class="wali-data-table-scroll">
                        <table class="wali-data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarSiswa as $i => $siswa)
                                    @php
                                        $noUrut = method_exists($daftarSiswa, 'firstItem')
                                            ? $daftarSiswa->firstItem() + $i
                                            : $i + 1;
                                        $kelasSiswa =
                                            $siswa->rombel_nama ??
                                            (optional($siswa->keanggotaanRombel->first()?->rombel)->nama ??
                                                $rombelLabel);
                                        $statusLabel =
                                            $siswa->status_kesiswaan_nama ??
                                            ($siswa->statusKesiswaan?->nama ??
                                                ($siswa->status_data?->label() ??
                                                    ($siswa->status_data?->value ?? 'Aktif')));
                                    @endphp
                                    <tr>
                                        <td>{{ $noUrut }}</td>
                                        <td>{{ $siswa->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $siswa->nisn ?? '-' }}</td>
                                        <td>{{ $kelasSiswa }}</td>
                                        <td>{{ $statusLabel }}</td>
                                        <td>
                                            @if (\Illuminate\Support\Facades\Route::has('wali_kelas.presensi.index'))
                                                <a
                                                    href="{{ route('wali_kelas.presensi.index', ['siswa_id' => $siswa->getKey()]) }}">Presensi</a>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if (method_exists($daftarSiswa, 'links'))
                        <div>{{ $daftarSiswa->withQueryString()->links() }}</div>
                    @endif
                @else
                    <div class="wali-data-table-scroll">
                        <table class="wali-data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="wali-data-empty">
                        <strong>Belum Ada Data Siswa Dimuat</strong>
                        <p>Daftar peserta didik kelas binaan akan disinkronkan langsung dari<br class="desktop-break">
                            basis data kesiswaan madrasah.</p>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('waliSidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }
    </script>
</body>

</html>
