@extends('operator.layout')
@section('title', 'Dashboard Operator')
@section('content')
    <header class="operator-heading">
        <h1>Dashboard Administrasi Operator</h1>
        <p>Pusat kendali pengelolaan data pokok MI Assaidiyyah, kesiswaan, kepegawaian, dan pengumuman resmi lembaga</p>
    </header>

    <section class="operator-stats" aria-label="Ringkasan administrasi">
        <article class="operator-stat-card">
            <span>Total Siswa<br>Terdaftar</span>
            <strong>- Siswa</strong>
        </article>
        <article class="operator-stat-card">
            <span>Pendidik &amp; Tenaga<br>Kependidikan</span>
            <strong>-</strong>
        </article>
        <article class="operator-stat-card">
            <span>Kelas</span>
            <strong>- Kelas</strong>
        </article>
        <article class="operator-stat-card">
            <span>Pengumuman Aktif</span>
            <strong>- Pengumuman</strong>
        </article>
    </section>

    <div class="operator-columns">
        <section class="operator-card operator-activity">
            <h2>Aktivitas Administrasi Terbaru</h2>
            <div class="operator-filters">
                <label class="operator-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input type="search" placeholder="Cari berdasarkan kata kunci aktivitas..." aria-label="Cari aktivitas">
                </label>
                <select aria-label="Pilih modul"><option>Semua Modul</option></select>
                <select aria-label="Pilih periode"><option>Semua Periode</option></select>
            </div>

            <div class="operator-table-scroll">
                <table>
                    <thead>
                        <tr><th>No</th><th>Waktu</th><th>Aktivitas / Perubahan</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="operator-empty-cell">
                                <strong>Belum Ada Aktivitas Tercatat</strong>
                                <p>Seluruh riwayat penambahan siswa, perubahan GTK, dan penerbitan pengumuman akan tercatat secara kronologis di sini.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <small class="operator-count">Menampilkan 0</small>
        </section>

        <div class="operator-side">
            <section class="operator-card operator-announcement">
                <h2>Pengumuman MI Assaidiyyah</h2>
                <div class="operator-empty">
                    <strong>Belum Ada Pengumuman Aktif</strong>
                    <p>Pengumuman internal guru maupun siaran terbuka belum dirilis.</p>
                    <a href="{{ url('/operator/pengumuman') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                        Buat Pengumuman Baru
                    </a>
                </div>
            </section>

            <section class="operator-card operator-quick">
                <h2>Akses Ringkas Operasional</h2>
                <div class="operator-quick-links">
                    <a href="{{ url('/operator/data-siswa') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 20v-1a6 6 0 0 1 12 0v1M19 8v6M16 11h6"/></svg>Input Santri</a>
                    <a href="{{ url('/operator/data-guru') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 20v-1a6 6 0 0 1 12 0v1M16 4h5v5M18 10l3-3"/></svg>Input GTK</a>
                    <a href="{{ url('/operator/data-sekolah') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/><path d="M9 9v.01M9 13v.01M9 17v.01"/></svg>Atur Rombel</a>
                    <a href="{{ url('/operator/data-guru') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><circle cx="12" cy="11" r="3"/><path d="M7 21v-1a5 5 0 0 1 10 0v1M8 2v4M16 2v4"/></svg>Wali Kelas</a>
                </div>
            </section>
        </div>
    </div>
@endsection