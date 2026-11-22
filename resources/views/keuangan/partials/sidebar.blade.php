<aside class="siswa-sidebar" id="keuanganSidebar">
    <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda"><svg
            xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 18-6-6 6-6" />
            <path d="M20 12H9" />
            <path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4" />
        </svg><span>Kembali ke Beranda</span></a>
    <nav class="sidebar-nav" aria-label="Navigasi Keuangan">
        <a href="{{ url('/keuangan/dashboard') }}"
            class="sidebar-nav-item {{ request()->is('keuangan/dashboard') ? 'active' : '' }}"
            @if (request()->is('keuangan/dashboard')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>Dashboard</a>
        <a href="{{ url('/keuangan/pembayaran-siswa') }}"
            class="sidebar-nav-item {{ request()->is('keuangan/pembayaran-siswa*') ? 'active' : '' }}"
            @if (request()->is('keuangan/pembayaran-siswa*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="2" y="5" width="20" height="14" rx="2" />
                <path d="M2 10h20M7 15h3" />
            </svg>Pembayaran Siswa</a>
        <a href="{{ url('/keuangan/tagihan/tambah') }}"
            class="sidebar-nav-item {{ request()->is('keuangan/tagihan*') ? 'active' : '' }}"
            @if (request()->is('keuangan/tagihan*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="4" width="18" height="16" rx="2" />
                <path d="M8 2v4m8-4v4M3 10h18M8 14h3m-3 3h7" />
            </svg>Input Tagihan</a>
        <a href="{{ url('/keuangan/rekap-absensi-guru') }}"
            class="sidebar-nav-item {{ request()->is('keuangan/rekap-absensi-guru') ? 'active' : '' }}"
            @if (request()->is('keuangan/rekap-absensi-guru')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="4" width="18" height="17" rx="2" />
                <path d="M16 2v4M8 2v4M3 10h18" />
                <path d="m16 15 2 2 4-4" />
            </svg>Rekap Absensi Guru</a>
        <a href="{{ url('/keuangan/pengumuman') }}"
            class="sidebar-nav-item {{ request()->is('keuangan/pengumuman*') ? 'active' : '' }}"
            @if (request()->is('keuangan/pengumuman*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z" />
                <path d="M7 15l1.5 5h3L10 15M18 9a4 4 0 0 1 0 6" />
            </svg>Pengumuman Keuangan</a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user-card flex items-center gap-3">
            <div class="sidebar-user-avatar shrink-0">
                {{ mb_strtoupper(mb_substr(auth()->user()?->nama_tampil ?? 'O', 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="sidebar-user-name truncate">
                    {{ auth()->user()?->nama_tampil ?? 'Operator' }}
                </div>
                <div class="sidebar-user-meta truncate">MI Assaidiyyah</div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="shrink-0"
                onsubmit="return confirm('Yakin ingin keluar dari sistem?')">
                @csrf
                <button type="submit" title="Keluar dari sistem" aria-label="Keluar"
                    class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-current opacity-65 transition-all duration-150 hover:bg-red-500/15 hover:text-red-500 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-red-500/40">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" class="h-4 w-4"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
