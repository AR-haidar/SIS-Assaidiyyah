@php($menuPeran = request()->routeIs('humas.*') ? 'humas' : 'kepala')
<aside class="siswa-sidebar" id="kepalaSidebar">
    <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 18-6-6 6-6" />
            <path d="M20 12H9" />
            <path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4" />
        </svg>
        <span>Kembali ke Beranda</span>
    </a>
    <nav class="sidebar-nav" aria-label="Navigasi kepala sekolah">
        <a href="{{ route($menuPeran . '.dashboard') }}"
            class="sidebar-nav-item {{ request()->routeIs($menuPeran . '.dashboard') ? 'active' : '' }}"
            @if (request()->routeIs($menuPeran . '.dashboard')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>Dashboard
        </a>
        <a href="{{ route($menuPeran . '.rekap_absensi.index') }}"
            class="sidebar-nav-item {{ request()->routeIs($menuPeran . '.rekap_absensi.index') ? 'active' : '' }}"
            @if (request()->routeIs($menuPeran . '.rekap_absensi.index')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
                <path d="m9 16 2 2 4-4" />
            </svg>Rekap Absensi
        </a>
        <a href="{{ route($menuPeran . '.rekap_pembayaran.index') }}"
            class="sidebar-nav-item {{ request()->routeIs($menuPeran . '.rekap_pembayaran.index') ? 'active' : '' }}"
            @if (request()->routeIs($menuPeran . '.rekap_pembayaran.index')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <rect x="2" y="5" width="20" height="14" rx="2" />
                <line x1="2" y1="10" x2="22" y2="10" />
                <path d="M16 15h2" />
            </svg>Rekap Pembayaran
        </a>
        <a href="{{ route($menuPeran . '.sekolah.show') }}"
            class="sidebar-nav-item {{ request()->routeIs($menuPeran . '.sekolah.show') ? 'active' : '' }}"
            @if (request()->routeIs($menuPeran . '.sekolah.show')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="m3 10 9-7 9 7" />
                <path d="M5 9v12h14V9" />
                <path d="M9 21v-8h6v8" />
            </svg>Data Sekolah
        </a>
        <a href="{{ route($menuPeran . '.pengumuman.index') }}"
            class="sidebar-nav-item {{ request()->routeIs($menuPeran . '.pengumuman.index') ? 'active' : '' }}"
            @if (request()->routeIs($menuPeran . '.pengumuman.index')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z" />
                <path d="M7 15l1.5 5h3L10 15" />
                <path d="M18 9a4 4 0 0 1 0 6" />
                <path d="M20 6a8 8 0 0 1 0 12" />
            </svg>Pengumuman
        </a>
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
