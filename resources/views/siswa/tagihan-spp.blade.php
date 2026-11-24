<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan — SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/css/siswa/tagihan-spp.css', 'resources/css/keuangan/revisi.css', 'resources/js/app.js'])
</head>

<body class="siswa-page min-h-screen bg-slate-50 text-slate-900">
    @php
        $daftarTahunAjaran = $daftarTahunAjaran ?? collect();
        $daftarJenisTagihan = $daftarJenisTagihan ?? collect();
        $opsiMetodePembayaran = $opsiMetodePembayaran ?? collect();
        $daftarTagihan = $daftarTagihan ?? collect();
        $isPaginator = $daftarTagihan instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $rombelAktif = $siswa->keanggotaanRombel->sortByDesc('id')->first()?->rombel;
    @endphp

    {{-- TOPBAR --}}
    <header class="siswa-topbar sticky top-0 z-30 flex items-center justify-between gap-3 px-4 sm:px-6">
        <div class="topbar-left flex items-center gap-3 min-w-0">
            <button type="button" class="sidebar-toggle-btn shrink-0" id="sidebarToggle" title="Buka/Tutup Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <a href="{{ url('/') }}" class="topbar-brand-wrap flex items-center gap-2.5 min-w-0">
                <div class="topbar-logo-slot shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                </div>
                <div class="topbar-brand-text truncate">
                    <span class="topbar-school-name block truncate">MI Assaidiyyah</span>
                    <span class="topbar-system-name hidden sm:block truncate">Sistem Informasi Madrasah</span>
                </div>
            </a>
        </div>
        <div class="topbar-right flex items-center gap-2 sm:gap-3 shrink-0">
            <div class="topbar-semester-box hidden md:flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
                <span>Tahun Ajaran</span>
                <span
                    class="semester-badge">{{ $daftarTahunAjaran->firstWhere('aktif', true)?->kode ?? 'Aktif' }}</span>
            </div>
            <div class="topbar-divider hidden md:block"></div>
            <div class="topbar-user-info hidden sm:flex flex-col text-right">
                <span class="topbar-user-role">Siswa</span>
                <span class="topbar-user-nip">NISN. {{ $siswa->nisn ?? '—' }}</span>
            </div>
            <a class="student-bell relative inline-flex items-center justify-center" href="#daftar-tagihan"
                aria-label="Notifikasi tagihan">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M10 21h4" />
                </svg>
                <b>{{ $ringkasan['jumlah_belum_lunas'] ?? 0 }}</b>
            </a>
            <div class="topbar-avatar-box shrink-0" title="{{ $siswa->nama_lengkap }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                </svg>
            </div>
        </div>
    </header>

    <div class="siswa-layout flex flex-col lg:flex-row min-h-[calc(100vh-4rem)]">

        {{-- SIDEBAR --}}
        <aside class="siswa-sidebar w-full lg:w-64 shrink-0" id="siswaSidebar">
            <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
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
                <a href="{{ url('/siswa/absensi') }}" class="sidebar-nav-item">
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
                <a href="{{ route('siswa.tagihan.index') }}" class="sidebar-nav-item active">
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
                    <div class="sidebar-user-avatar shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="sidebar-user-name truncate">{{ $siswa->nama_lengkap }}</div>
                        <div class="sidebar-user-meta truncate">{{ $rombelAktif?->nama ?? 'Belum ada rombel' }} &bull;
                            {{ $siswa->nisn }}</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="siswa-main flex-1 min-w-0 px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- 1. Header Halaman --}}
            <div class="spp-page-header space-y-2">
                <h1 class="spp-page-title text-xl sm:text-2xl font-bold text-slate-900">Tagihan</h1>
                <p class="spp-page-sub text-sm text-slate-600">Informasi seluruh tagihan dan pengajuan bukti pembayaran
                    siswa</p>

                @if (session('status'))
                    <div role="status"
                        class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                        <strong class="font-semibold">{{ session('status') }}</strong>
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert"
                        class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                        <strong class="font-semibold">Pengajuan pembayaran gagal:</strong>
                        <ul class="mt-1.5 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- 2. Card Profil Siswa Singkat (Horizontal) --}}
            <div
                class="spp-student-banner flex flex-col sm:flex-row sm:items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="spp-student-avatar shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" />
                        <circle cx="12" cy="10" r="3" />
                        <path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" />
                    </svg>
                </div>
                <div class="spp-student-fields grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6 flex-1 min-w-0">
                    <div class="spp-banner-field min-w-0">
                        <span class="spp-banner-key block text-xs text-slate-500">Nama Siswa</span>
                        <span
                            class="spp-banner-val block font-semibold text-slate-900 truncate">{{ $siswa->nama_lengkap }}</span>
                    </div>
                    <div class="spp-banner-field min-w-0">
                        <span class="spp-banner-key block text-xs text-slate-500">Nomor Induk (NISN)</span>
                        <span
                            class="spp-banner-val block font-semibold text-slate-900">{{ $siswa->nisn ?? '—' }}</span>
                    </div>
                    <div class="spp-banner-field min-w-0">
                        <span class="spp-banner-key block text-xs text-slate-500">Kelas</span>
                        <span
                            class="spp-banner-val block font-semibold text-slate-900">{{ $rombelAktif?->nama ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- 3. 3 Kartu Ringkasan Kewajiban Keuangan (Grid 3 Kolom) --}}
            <div class="spp-summary-grid grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="spp-summary-box rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <span class="spp-sum-label block text-xs font-semibold tracking-wide text-slate-500">TOTAL
                        KEWAJIBAN AKTIF</span>
                    <span class="spp-sum-amount mt-1 block text-lg sm:text-xl font-bold text-slate-900">Rp
                        {{ number_format((float) ($ringkasan['total_kewajiban'] ?? 0), 0, ',', '.') }}</span>
                </div>
                <div class="spp-summary-box rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <span class="spp-sum-label block text-xs font-semibold tracking-wide text-slate-500">TERBAYAR /
                        TERVERIFIKASI</span>
                    <span class="spp-sum-amount mt-1 block text-lg sm:text-xl font-bold text-emerald-700">Rp
                        {{ number_format((float) ($ringkasan['terbayar'] ?? 0), 0, ',', '.') }}</span>
                </div>
                <div class="spp-summary-box rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <span class="spp-sum-label block text-xs font-semibold tracking-wide text-slate-500">SISA
                        TUNGGAKAN</span>
                    <span class="spp-sum-amount mt-1 block text-lg sm:text-xl font-bold text-amber-700">Rp
                        {{ number_format((float) ($ringkasan['sisa_tunggakan'] ?? 0), 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- 4. Card Filter Pembayaran --}}
            <form method="GET" action="{{ route('siswa.tagihan.index') }}"
                class="spp-filter-card grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="spp-filter-group flex flex-col gap-1.5">
                    <label class="spp-filter-label text-xs font-semibold text-slate-600"
                        for="filterTahunAjaran">Periode Akademik</label>
                    <div class="spp-select-wrapper relative">
                        <select id="filterTahunAjaran" name="tahun_ajaran_id"
                            class="spp-select-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach ($daftarTahunAjaran as $ta)
                                <option value="{{ $ta->id }}" @selected((string) ($filter['tahun_ajaran_id'] ?? '') === (string) $ta->id)>
                                    {{ $ta->kode }}
                                </option>
                            @endforeach
                        </select>
                        <span class="spp-select-icon pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="spp-filter-group flex flex-col gap-1.5">
                    <label class="spp-filter-label text-xs font-semibold text-slate-600"
                        for="filterJenisTagihan">Jenis Tagihan</label>
                    <div class="spp-select-wrapper relative">
                        <select id="filterJenisTagihan" name="jenis_tagihan_id"
                            class="spp-select-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                            <option value="">Semua Jenis</option>
                            @foreach ($daftarJenisTagihan as $jenis)
                                <option value="{{ $jenis->id }}" @selected((string) ($filter['jenis_tagihan_id'] ?? '') === (string) $jenis->id)>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                        <span class="spp-select-icon pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="spp-filter-group flex flex-col gap-1.5">
                    <label class="spp-filter-label text-xs font-semibold text-slate-600" for="filterStatus">Status
                        Pembayaran</label>
                    <div class="spp-select-wrapper relative">
                        <select id="filterStatus" name="status"
                            class="spp-select-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                            <option value="">Semua Status</option>
                            <option value="belum" @selected(($filter['status'] ?? '') === 'belum')>Belum Dibayar</option>
                            <option value="menunggu" @selected(($filter['status'] ?? '') === 'menunggu')>Menunggu Verifikasi</option>
                            <option value="disetujui" @selected(($filter['status'] ?? '') === 'disetujui')>Disetujui / Lunas</option>
                            <option value="ditolak" @selected(($filter['status'] ?? '') === 'ditolak')>Ditolak</option>
                        </select>
                        <span class="spp-select-icon pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </div>
                </div>

                <button type="submit"
                    class="btn-terapkan-spp-filter inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </form>

            {{-- 5. Card Tabel Tagihan & Pengajuan Pembayaran --}}
            <div class="spp-table-card rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <span id="daftar-tagihan"></span>
                <div class="spp-table-container w-full overflow-x-auto">
                    <table class="spp-table min-w-full divide-y divide-slate-200 text-sm">
                        <thead
                            class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            <tr>
                                <th class="px-4 py-3 whitespace-nowrap">JENIS TAGIHAN</th>
                                <th class="px-4 py-3 whitespace-nowrap">NOMINAL</th>
                                <th class="px-4 py-3 whitespace-nowrap">JATUH TEMPO</th>
                                <th class="px-4 py-3 whitespace-nowrap">STATUS</th>
                                <th class="px-4 py-3 whitespace-nowrap">AJUKAN PEMBAYARAN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($daftarTagihan as $tagihan)
                                @php
                                    $pembayaran =
                                        $tagihan->pembayaran instanceof \Illuminate\Support\Collection
                                            ? $tagihan->pembayaran->sortByDesc('id')->first()
                                            : $tagihan->pembayaran;
                                    $statusBayar = $pembayaran?->status_verifikasi?->value;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <strong
                                            class="font-semibold text-slate-900">{{ $tagihan->jenisTagihan?->nama ?? '—' }}</strong>
                                        @if ($tagihan->periode_bulan)
                                            <span
                                                class="block text-xs text-slate-500">{{ $tagihan->periode_bulan->translatedFormat('F Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900">Rp
                                        {{ number_format((float) $tagihan->nominal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                        {{ $tagihan->jatuh_tempo?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if ($statusBayar === 'disetujui')
                                            <span
                                                class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Disetujui
                                                / Lunas</span>
                                        @elseif ($statusBayar === 'menunggu')
                                            <span
                                                class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Menunggu
                                                Verifikasi</span>
                                        @elseif ($statusBayar === 'ditolak')
                                            <span
                                                class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">Ditolak</span>
                                            @if ($pembayaran?->alasan_penolakan)
                                                <span
                                                    class="block mt-1 max-w-xs whitespace-normal text-xs text-rose-700">{{ $pembayaran->alasan_penolakan }}</span>
                                            @endif
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">Belum
                                                Dibayar</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if (!$pembayaran || $statusBayar === 'ditolak')
                                            <form method="POST"
                                                action="{{ route('siswa.tagihan.ajukan-pembayaran', $tagihan) }}"
                                                enctype="multipart/form-data"
                                                class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-2 min-w-60">
                                                @csrf
                                                <input type="number" name="nominal" min="1" step="1"
                                                    value="{{ (float) $tagihan->nominal }}" required
                                                    aria-label="Nominal bayar"
                                                    class="w-full sm:w-28 rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-900 focus:border-emerald-600 focus:outline-none">
                                                <select name="metode_pembayaran_id" required
                                                    aria-label="Metode pembayaran"
                                                    class="w-full sm:w-auto rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-900 focus:border-emerald-600 focus:outline-none">
                                                    @foreach ($opsiMetodePembayaran as $metode)
                                                        <option value="{{ $metode->id }}">{{ $metode->label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="file" name="bukti_berkas"
                                                    accept=".jpg,.jpeg,.png,.webp,.pdf" aria-label="Bukti transfer"
                                                    class="w-full sm:w-44 text-xs text-slate-600 file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-2.5 file:py-1 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                                                <button type="submit"
                                                    class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                                                    Ajukan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-600 whitespace-nowrap">
                                                {{ $pembayaran->metodePembayaran?->label ?? '—' }}
                                                &bull; Rp
                                                {{ number_format((float) $pembayaran->nominal, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                        <strong class="font-semibold text-slate-700">Belum ada tagihan yang tercatat
                                            untuk akun siswa ini.</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($isPaginator && $daftarTagihan->hasPages())
                    <div class="border-t border-slate-200 px-4 py-3">
                        {{ $daftarTagihan->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('siswaSidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }
    </script>
</body>

</html>
