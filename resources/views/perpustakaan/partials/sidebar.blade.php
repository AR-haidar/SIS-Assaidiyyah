<aside class="siswa-sidebar" id="perpustakaanSidebar">
    <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda"><svg
            xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 18-6-6 6-6" />
            <path d="M20 12H9" />
            <path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4" />
        </svg><span>Kembali ke Beranda</span></a>
    <nav class="sidebar-nav" aria-label="Navigasi Perpustakaan">
        <a href="{{ url('/perpustakaan/dashboard') }}"
            class="sidebar-nav-item {{ request()->is('perpustakaan/dashboard') ? 'active' : '' }}"
            @if (request()->is('perpustakaan/dashboard')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>Dashboard</a>
        <a href="{{ url('/perpustakaan/data-buku') }}"
            class="sidebar-nav-item {{ request()->is('perpustakaan/data-buku*') ? 'active' : '' }}"
            @if (request()->is('perpustakaan/data-buku*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
            </svg>Data Buku</a>
        <a href="{{ route('pustakawan.kategori-buku.index') }}"
            class="sidebar-nav-item {{ request()->routeIs('pustakawan.kategori-buku.*') ? 'active' : '' }}"
            @if (request()->routeIs('pustakawan.kategori-buku.*')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 13 11 4H4v7l9 9a2 2 0 0 0 3 0l4-4a2 2 0 0 0 0-3z" />
                <circle cx="7.5" cy="7.5" r=".5" />
            </svg>Kategori Buku</a>
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
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
