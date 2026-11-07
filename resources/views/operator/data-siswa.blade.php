@extends('operator.layout')
@section('title', 'Data Siswa')
@section('content')
    <section class="operator-students">
        <header class="operator-students-heading">
            <h1>Direktori &amp; Manajemen Data Siswa</h1>
            <a class="operator-primary-button" href="{{ url('/operator/data-siswa/tambah') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Siswa Baru
            </a>
        </header>

        <section class="operator-student-stats" aria-label="Ringkasan siswa">
            <article><span>Total Siswa Aktif</span><strong>&mdash; Siswa</strong></article>
            <article><span>Siswa Laki-laki</span><strong>&mdash; Siswa</strong></article>
            <article><span>Siswi Perempuan</span><strong>&mdash; Siswi</strong></article>
        </section>

        <form class="operator-student-filters">
            <label class="operator-student-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" name="search" placeholder="Cari nama siswa atau NISN" aria-label="Cari nama siswa atau NISN">
            </label>
            <div class="operator-student-filter-actions">
                <button class="operator-student-reset" type="reset">Reset</button>
                <button class="operator-primary-button" type="submit">Terapkan Filter</button>
            </div>
        </form>

        <section class="operator-student-table-card" aria-label="Daftar data siswa">
            <div class="operator-student-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No</th><th>Nama Siswa</th><th>NIS / NISN</th><th>Kelas</th><th>Jenis Kelamin</th><th>Status Kesiswaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="operator-student-empty">
                                <strong>Belum Ada Data Siswa Dimuat</strong>
                                <p>Gunakan tombol di atas untuk menambah santri baru secara manual<br class="operator-student-desktop-break"> atau sinkronkan data dari pangkalan data EMIS Kemenag.</p>
                                <a class="operator-primary-button" href="{{ url('/operator/data-siswa/tambah') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 20v-1a6 6 0 0 1 12 0v1M19 8v6M16 11h6"/></svg>
                                    Tambah Siswa Baru
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <footer class="operator-student-table-footer">
                <span>Menampilkan <strong>0</strong> dari <strong>0</strong> siswa</span>
                <nav aria-label="Navigasi halaman">
                    <button type="button" aria-label="Halaman sebelumnya" disabled>&lsaquo;</button>
                    <span class="operator-current-page">1</span>
                    <button type="button" aria-label="Halaman berikutnya" disabled>&rsaquo;</button>
                </nav>
            </footer>
        </section>
    </section>
@endsection