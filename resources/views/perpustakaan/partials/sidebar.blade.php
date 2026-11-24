<aside class="siswa-sidebar" id="perpustakaanSidebar">
    <nav class="sidebar-nav" aria-label="Navigasi Perpustakaan">
        <a href="{{ route('pustakawan.dashboard') }}"
            class="sidebar-nav-item {{ request()->routeIs('pustakawan.dashboard') ? 'active' : '' }}"
            @if (request()->routeIs('pustakawan.dashboard')) aria-current="page" @endif><svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>Dashboard</a>
        <a href="{{ route('pustakawan.data-buku.index') }}"
            class="sidebar-nav-item {{ request()->routeIs('pustakawan.data-buku.*') ? 'active' : '' }}"
            @if (request()->routeIs('pustakawan.data-buku.*')) aria-current="page" @endif>
            <x-perpustakaan.icon name="book-open" size="18" />Data Buku</a>
        <a href="{{ route('pustakawan.kategori-buku.index') }}"
            class="sidebar-nav-item {{ request()->routeIs('pustakawan.kategori-buku.*') ? 'active' : '' }}"
            @if (request()->routeIs('pustakawan.kategori-buku.*')) aria-current="page" @endif>
            <x-perpustakaan.icon name="category" size="18" />Kategori Buku</a>
    </nav>
    <a href="{{ url('/') }}" class="sidebar-home-link action-button action-button--quiet"><svg
            xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 18-6-6 6-6" />
            <path d="M20 12H9" />
            <path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4" />
        </svg><span>Kembali ke Beranda</span></a>
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
                data-confirm-dialog="konfirmasi-keluar-pustakawan">
                @csrf
                <button type="submit" title="Keluar dari sistem" aria-label="Keluar"
                    class="perpustakaan-logout action-button action-button--danger inline-flex h-8 w-8 items-center justify-center rounded-lg">
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
<x-confirmation-dialog
    id="konfirmasi-keluar-pustakawan"
    title="Keluar dari sistem?"
    message="Anda akan kembali ke halaman masuk."
    confirm-label="Keluar"
    cancel-label="Tetap di Sini"
    tone="danger"
/>
