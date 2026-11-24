@extends('kepala-sekolah.layout')
@section('title', 'Dashboard Kepala Sekolah')
@section('page-class', 'ks-dashboard-page')
@section('content')
    <header class="ks-page-heading">
        <h1>Dashboard Kepala MI Assaidiyyah</h1>
        <p>Ringkasan pemantauan terpadu presensi madrasah, ketercapaian pembayaran Infaq dan tagihan lain, serta edaran resmi.</p>
    </header>
    <section class="ks-summary-grid" aria-label="Ringkasan madrasah">
        <article class="ks-summary-card"><span>PRESENSI SISWA HARIAN</span><strong>- %</strong></article>
        <article class="ks-summary-card"><span>KEHADIRAN GURU &amp; STAF HARIAN</span><strong>- %</strong></article>
        <article class="ks-summary-card"><span>REALISASI INFAQ &amp; TAGIHAN SEMESTER INI</span><strong>Rp -</strong></article>
        <article class="ks-summary-card"><span>TUNGGAKAN TAGIHAN BERJALAN</span><strong>Rp -</strong></article>
    </section>
    <div class="ks-dashboard-grid">
        <div class="ks-dashboard-primary">
            <section class="ks-panel ks-attendance-panel">
                <div class="ks-panel-heading">
                    <div><h2>Rekapitulasi Presensi</h2><p>Pantauan komparatif harian siswa dan pendidik</p></div>
                    <label class="ks-compact-select"><span class="sr-only">Pilih bulan</span><select aria-label="Pilih bulan"><option>Bulan</option><option>Januari</option><option>Februari</option></select></label>
                </div>
                <div class="ks-attendance-chart" role="img" aria-label="Grafik rekap presensi harian belum tersedia">
                    <div class="ks-chart-caption">Grafik<br>Harian</div>
                    <div class="ks-chart-lines"><i></i><i></i><i></i><i></i></div>
                    <div class="ks-chart-weeks"><span>Minggu 1</span><span>Minggu 2</span><span>Minggu 3</span><span>Minggu 4</span></div>
                </div>
                <div class="ks-attendance-legend">
                    <span><i class="ks-dot hadir"></i>Hadir (H)<b>- %</b></span><span><i class="ks-dot izin"></i>Izin (I)<b>- %</b></span><span><i class="ks-dot sakit"></i>Sakit<b>- %</b></span><span><i class="ks-dot alpa"></i>Alpa<b>- %</b></span>
                </div>
            </section>
            <section class="ks-panel ks-finance-summary-panel">
                <h2>Rekapitulasi Keuangan</h2>
                <div class="ks-budget-progress">
                    <div><span>Target RAPBM: <b>Rp -</b></span><span>Realisasi Kas: <b>Rp -</b></span></div>
                    <div class="ks-progress-track"><i></i></div>
                    <div><span>Progress Pengumpulan: - %</span><span>Sisa Defisit: Rp -</span></div>
                </div>
                <div class="ks-mini-table-wrap">
                    <table class="ks-mini-table"><thead><tr><th>Tingkat Kelas</th><th>Total Santri</th><th>Tercapai</th><th>Persentase</th></tr></thead><tbody>
                        @foreach (['I', 'II', 'III', 'IV', 'V', 'VI'] as $kelas)
                            <tr><td>Kelas {{ $kelas }}</td><td>-</td><td>Rp -</td><td>- %</td></tr>
                        @endforeach
                    </tbody></table>
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
        </section>
    </div>
@endsection
