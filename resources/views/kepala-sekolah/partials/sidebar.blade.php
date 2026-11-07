<aside class="siswa-sidebar" id="kepalaSidebar">
    <a href="{{ url('/') }}" class="sidebar-home-link" aria-label="Kembali ke beranda">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/><path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4"/></svg>
        <span>Kembali ke Beranda</span>
    </a>
    <nav class="sidebar-nav" aria-label="Navigasi kepala sekolah">
        <a href="{{ url('/kepala-sekolah/dashboard') }}" class="sidebar-nav-item {{ request()->is('kepala-sekolah/dashboard') ? 'active' : '' }}" @if(request()->is('kepala-sekolah/dashboard')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard
        </a>
        <a href="{{ url('/kepala-sekolah/rekap-absensi') }}" class="sidebar-nav-item {{ request()->is('kepala-sekolah/rekap-absensi') ? 'active' : '' }}" @if(request()->is('kepala-sekolah/rekap-absensi')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>Rekap Absensi
        </a>
        <a href="{{ url('/kepala-sekolah/rekap-pembayaran') }}" class="sidebar-nav-item {{ request()->is('kepala-sekolah/rekap-pembayaran') ? 'active' : '' }}" @if(request()->is('kepala-sekolah/rekap-pembayaran')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><path d="M16 15h2"/></svg>Rekap Pembayaran
        </a>
        <a href="{{ url('/kepala-sekolah/data-sekolah') }}" class="sidebar-nav-item {{ request()->is('kepala-sekolah/data-sekolah') ? 'active' : '' }}" @if(request()->is('kepala-sekolah/data-sekolah')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 10 9-7 9 7"/><path d="M5 9v12h14V9"/><path d="M9 21v-8h6v8"/></svg>Data Sekolah
        </a>
        <a href="{{ url('/kepala-sekolah/pengumuman') }}" class="sidebar-nav-item {{ request()->is('kepala-sekolah/pengumuman') ? 'active' : '' }}" @if(request()->is('kepala-sekolah/pengumuman')) aria-current="page" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z"/><path d="M7 15l1.5 5h3L10 15"/><path d="M18 9a4 4 0 0 1 0 6"/><path d="M20 6a8 8 0 0 1 0 12"/></svg>Pengumuman
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user-card">
            <div class="sidebar-user-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></div>
            <div><div class="sidebar-user-name">Kepala Sekolah</div><div class="sidebar-user-meta">MI Assaidiyyah</div></div>
        </div>
    </div>
</aside>