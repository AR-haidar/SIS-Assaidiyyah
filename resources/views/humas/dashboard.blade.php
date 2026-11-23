@extends('humas.layout')

@section('title', 'Dashboard Humas')

@section('content')
<section class="humas-content">
    <header class="humas-dashboard-heading">
        <div>
            <h1>Dashboard Humas &amp; CMS Landing Page</h1>
            <p>Pusat tata kelola konten, publikasi informasi, dan media publik MI Assa'iyyah Cipanas.</p>
        </div>
        <div class="humas-heading-actions">
            <a href="{{ url('/') }}" aria-label="Lihat Landing Page Live">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 3h7v7m-1-6-9 9M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"/></svg>
                <span>Lihat Landing Page Live</span>
            </a>
        </div>
    </header>

    <aside class="humas-notice">
        <div>
            <strong>Catatan Batasan &amp; Kewenangan Humas</strong>
            <p>Peran Humas difokuskan pada pembaruan teks deskripsi, kurasi foto/galeri, publikasi pengumuman, dan transparansi prestasi landing page. Struktur tata letak grid dan desain visual antarmuka publik bersifat permanen. Modul Perpustakaan Digital dikelola secara mandiri oleh staf Admin Perpustakaan.</p>
        </div>
    </aside>

    <section class="humas-activity" aria-labelledby="humas-activity-title">
        <header>
            <div class="humas-activity-heading">
                <strong id="humas-activity-title">Aktivitas Pembaruan Terakhir</strong>
                <p>Log penyuntingan konten landing page oleh tim Humas</p>
            </div>
            <div class="humas-activity-tools">
                <input type="search" data-humas-activity-search placeholder="Cari konten..." aria-label="Cari aktivitas konten">
                <button type="button" data-humas-activity-filter aria-label="Filter status aktivitas" title="Filter status">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg>
                </button>
            </div>
        </header>
        <div class="humas-activity-scroll">
            <table>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>BAGIAN<br>HALAMAN</th>
                        <th>JUDUL / OBJEK<br>KONTEN</th>
                        <th>STATUS</th>
                        <th>TERAKHIR<br>DISUNTING</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody data-humas-activities></tbody>
            </table>
        </div>
        <footer>
            <span data-humas-activity-count>Menampilkan 5 dari 42 riwayat konten</span>
            <nav aria-label="Halaman aktivitas">
                <button type="button" data-humas-prev aria-label="Halaman sebelumnya">‹</button>
                <button type="button" class="active" data-humas-page aria-current="page">1</button>
                <button type="button" data-humas-next aria-label="Halaman berikutnya">›</button>
            </nav>
        </footer>
    </section>
</section>
@endsection
