<aside class="siswa-sidebar" id="operatorSidebar">
    <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 18-6-6 6-6" />
            <path d="M20 12H9" />
            <path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4" />
        </svg><span>Kembali ke Beranda</span>
    </a>
    <nav class="sidebar-nav" aria-label="Navigasi Operator">
        <a href="{{ url('/operator/dashboard') }}"
            class="sidebar-nav-item {{ request()->is('operator/dashboard') ? 'active' : '' }}"
            @if (request()->is('operator/dashboard')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>Dashboard</a>
        <a href="{{ url('/operator/data-siswa') }}"
            class="sidebar-nav-item {{ request()->is('operator/data-siswa*') ? 'active' : '' }}"
            @if (request()->is('operator/data-siswa*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="9" cy="8" r="4" />
                <path d="M3 21v-2a6 6 0 0 1 12 0v2M17 11a4 4 0 1 0 0-8" />
            </svg>Data Siswa</a>
        <a href="{{ url('/operator/data-guru') }}"
            class="sidebar-nav-item {{ request()->is('operator/data-guru*') ? 'active' : '' }}"
            @if (request()->is('operator/data-guru*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m12 3 10 5-10 5L2 8z" />
                <path d="M6 10v6c3 3 9 3 12 0v-6M22 8v7" />
            </svg>Data Guru</a>
        <a href="{{ url('/operator/data-sekolah') }}"
            class="sidebar-nav-item {{ request()->is('operator/data-sekolah') ? 'active' : '' }}"
            @if (request()->is('operator/data-sekolah')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m3 10 9-7 9 7" />
                <path d="M5 9v12h14V9M9 21v-8h6v8" />
            </svg>Data Sekolah</a>
        <a href="{{ url('/operator/pengumuman') }}"
            class="sidebar-nav-item {{ request()->is('operator/pengumuman*') ? 'active' : '' }}"
            @if (request()->is('operator/pengumuman*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
            </svg>Pengumuman</a>
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
