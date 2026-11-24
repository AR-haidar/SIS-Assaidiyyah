@extends('humas.layout')

@section('title', 'Dashboard Humas')

@section('content')
    @php
        $daftarAktivitas = $daftarAktivitas ?? collect();
        $isPaginator = $daftarAktivitas instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $urlIndex = \Illuminate\Support\Facades\Route::has('humas.dashboard')
            ? route('humas.dashboard')
            : url('/humas/dashboard');
    @endphp
    <section class="humas-content">
        <header class="humas-dashboard-heading">
            <div>
                <h1>Dashboard Humas &amp; CMS Landing Page</h1>
                <p>Pusat tata kelola konten, publikasi informasi, dan media publik MI Assa'iyyah Cipanas.</p>
            </div>
            <div class="humas-heading-actions">
                <a href="{{ url('/') }}" aria-label="Lihat Landing Page Live">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14 3h7v7m-1-6-9 9M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6" />
                    </svg>
                    <span>Lihat Landing Page Live</span>
                </a>
            </div>
        </header>

        <aside class="humas-notice">
            <div>
                <strong>Catatan Batasan &amp; Kewenangan Humas</strong>
                <p>Peran Humas difokuskan pada pembaruan teks deskripsi, kurasi foto/galeri, publikasi pengumuman, dan
                    transparansi prestasi landing page. Struktur tata letak grid dan desain visual antarmuka publik bersifat
                    permanen. Modul Perpustakaan Digital dikelola secara mandiri oleh staf Admin Perpustakaan.</p>
            </div>
        </aside>

        <section class="humas-activity" aria-labelledby="humas-activity-title">
            <header>
                <div class="humas-activity-heading">
                    <strong id="humas-activity-title">Aktivitas Pembaruan Terakhir</strong>
                    <p>Log penyuntingan konten landing page oleh tim Humas</p>
                </div>
            </section>
        </div>
        <section class="ks-panel ks-announcement-summary">
            <div class="ks-panel-heading"><h2>Pengumuman</h2><a href="{{ route('humas.pengumuman.index') }}">Kelola</a></div>
            <div class="ks-announcement-empty">
                <span class="shared-announcement-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z"/><path d="M18 9a4 4 0 0 1 0 6"/></svg></span>
                <strong>Belum Ada Pengumuman Diterbitkan</strong>
                <p>Pengumuman dan instruksi resmi Kepala Madrasah akan tampil di sini untuk siswa dan dewan guru.</p>
                <a class="ks-primary-button" href="{{ route('humas.pengumuman.index') }}"><span>+</span> Buat Pengumuman Baru</a>
            </div>
            <footer>
                <span data-humas-activity-count>Menampilkan {{ $daftarAktivitas->count() }} dari
                    {{ $isPaginator ? $daftarAktivitas->total() : $daftarAktivitas->count() }} riwayat konten</span>
                <nav aria-label="Halaman aktivitas">
                    @if ($isPaginator && $daftarAktivitas->hasPages())
                        {{ $daftarAktivitas->links() }}
                    @else
                        <button type="button" data-humas-prev disabled aria-label="Halaman sebelumnya">&lsaquo;</button>
                        <button type="button" class="active" data-humas-page aria-current="page">1</button>
                        <button type="button" data-humas-next disabled aria-label="Halaman berikutnya">&rsaquo;</button>
                    @endif
                </nav>
            </footer>
        </section>
    </section>
@endsection
