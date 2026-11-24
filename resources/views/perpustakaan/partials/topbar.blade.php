<header class="siswa-topbar">
    <div class="topbar-left">
        <a href="{{ url('/') }}" class="topbar-brand-wrap">
            <img class="perpustakaan-logo" src="{{ asset('images/logo-assaidiyyah.png') }}" width="1269" height="542" alt="">
            <span class="topbar-brand-text">
                <span class="topbar-school-name">MI Assaidiyyah</span>
                <span class="topbar-system-name">Sistem Informasi Madrasah</span>
            </span>
        </a>
        <button type="button" class="sidebar-toggle-btn action-button action-button--quiet" id="sidebarToggle" aria-controls="perpustakaanSidebar"
            aria-label="Menu perpustakaan" aria-expanded="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M3 6h18M3 12h18M3 18h18" />
            </svg>
        </button>
    </div>
    <div class="topbar-right">
        <div class="topbar-semester-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="m2 10 10-5 10 5-10 5zM6 12v5c3 3 9 3 12 0v-5" />
            </svg>
            <span>Tahun Ajaran</span>
            <span class="semester-badge">Semester Ganjil</span>
        </div>
        <div class="topbar-divider"></div>
        <span class="topbar-user-role">Pustakawan</span>
        <span class="topbar-avatar-box" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 20a8 8 0 0 1 16 0" />
            </svg>
        </span>
    </div>
</header>
