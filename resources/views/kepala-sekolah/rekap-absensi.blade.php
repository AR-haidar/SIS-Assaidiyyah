@extends('kepala-sekolah.layout')
@section('title', 'Rekap Absensi')
@section('page-class', 'ks-attendance-page')
@section('content')
    <header class="ks-page-heading"><h1>Rekapitulasi Presensi MI Assaidiyyah</h1><p>Pemantauan komprehensif tingkat kehadiran peserta didik dan tenaga pendidik</p></header>
    <section class="ks-panel ks-attendance-filters">
        <div class="ks-filter-heading"><span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z"/></svg> Parameter Analisis Presensi</span><small>Juknis Ditjen Pendis Kemenag RI</small></div>
        <div class="ks-attendance-filter-row">
            <label>Periode Kalender <b>*</b><select aria-label="Periode Kalender"><option value="">Pilih periode</option><option>Januari 2025</option></select></label>
            <label>Tingkat / Kelas<select aria-label="Tingkat atau kelas"><option value="">Semua tingkat</option><option>Kelas I</option><option>Kelas II</option></select></label>
            <button type="button" class="ks-filter-button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z"/></svg> Terapkan Filter</button>
        </div>
    </section>
    <section class="ks-attendance-kpis" aria-label="Ringkasan presensi">
        <article><span>PRESENSI SISWA</span><small>Rata-Rata Kehadiran<br>Siswa</small><strong>-<small>%</small></strong></article>
        <article><span>PRESENSI PENDIDIK</span><small>Rata-Rata Kehadiran<br>Guru</small><strong>-<small>%</small></strong></article>
        <article><span>KALENDER AKADEMIK</span><small>Hari Efektif Belajar</small><strong>-<small>Hari</small></strong></article>
    </section>
    <section class="ks-panel ks-matrix-card">
        <div class="ks-matrix-title"><h2>Matriks Presensi Siswa</h2><span><i></i> Siklus Real-Time</span></div>
        <div class="ks-table-scroll"><table class="ks-data-table ks-attendance-matrix"><thead><tr><th>No</th><th>Tingkat / Kelas</th><th>Wali Kelas</th><th>Total Siswa</th><th>Hadir (%)</th><th>Izin (%)</th><th>Sakit (%)</th><th>Alpa (%)</th></tr></thead></table></div>
        <div class="ks-empty-state"><strong>Belum Ada Data Rekapitulasi Terpilih</strong><p>Pilih rentang periode dan parameter filter di atas untuk<br> mengkalkulasi rekapitulasi kehadiran madrasah.</p></div>
        <footer class="ks-table-footer"><span>Menampilkan 0 dari 0 rombel</span><div class="ks-pagination"><button disabled><</button><span>0</span><button disabled>></button></div></footer>
    </section>
@endsection