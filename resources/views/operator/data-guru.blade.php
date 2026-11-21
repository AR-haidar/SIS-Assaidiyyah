@extends('operator.layout')
@section('title', 'Data Guru')
@section('content')
    <section class="operator-teachers">
        <header class="operator-teachers-heading">
            <div>
                <h1>Direktori &amp; Manajemen Data Guru</h1>
                <p>Pengelolaan data pendidik dan tenaga kependidikan</p>
            </div>
            <a class="operator-primary-button" href="{{ url('/operator/data-guru/tambah') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Guru Baru
            </a>
        </header>

        <section class="operator-student-import" aria-label="Impor guru dari Excel atau CSV">
            <div><strong>Impor data guru dari Excel atau CSV</strong><p>Pilih .xlsx atau .csv. Data dari sheet guru dan tenaga kependidikan akan ditambahkan; NIP/NUPTK duplikat dilewati.</p></div>
            <label class="operator-primary-button operator-student-import-button" style="margin-block:14px;min-height:40px;padding-inline:16px;">Pilih File XLSX / CSV<input type="file" accept=".xlsx,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv" data-teacher-file hidden></label>
            <p class="operator-student-import-message" data-teacher-import-message role="status" aria-live="polite"></p>
        </section>

        <section class="operator-teacher-stats" aria-label="Ringkasan data guru">
            <article><span>Total Guru &amp; Staff Terdaftar</span><strong>&mdash;</strong></article>
            <article><span>Tugas Tambahan / Wali Kelas</span><strong>&mdash;</strong></article>
        </section>

        <form class="operator-teacher-filters">
            <label class="operator-teacher-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" name="search" placeholder="Cari nama guru atau NIP" aria-label="Cari nama guru atau NIP">
            </label>
            <select aria-label="Pilih status kepegawaian"><option>Semua Status</option></select>
            <button class="operator-teacher-apply" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg>
                Terapkan Filter
            </button>
            <button class="operator-teacher-reset" type="reset">Reset</button>
        </form>

        <section class="operator-teacher-table-card" aria-label="Daftar data guru">
            <div class="operator-teacher-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No</th><th>Nama Lengkap</th><th>NIK</th><th>NUPTK</th><th>Status Kepegawaian</th><th>NIP</th><th>Jenis Kelamin</th><th>Tempat Lahir</th><th>Tanggal Lahir</th><th>Nomor Handphone</th><th>Email</th><th>Email Akun Madrasah</th><th>Tugas</th><th>Mata Pelajaran</th><th>Penempatan</th><th>Total JTM</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody data-operator-teachers>
                        <tr>
                            <td colspan="17" class="operator-teacher-empty">
                                <strong>Belum Ada Data Guru Dimuat</strong>
                                <p>Daftar pendidik dan tenaga kependidikan akan ditampilkan di sini.</p>
                                <a class="operator-primary-button" href="{{ url('/operator/data-guru/tambah') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                    Tambah Guru Baru
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <footer class="operator-teacher-pagination"><span>Menampilkan <strong data-teacher-visible>0</strong> dari <strong data-teacher-total>0</strong> guru</span><nav aria-label="Navigasi halaman guru"><button type="button" data-teacher-prev aria-label="Halaman sebelumnya">&lsaquo;</button><span data-teacher-page>1</span><button type="button" data-teacher-next aria-label="Halaman berikutnya">&rsaquo;</button></nav></footer>
        </section>
    </section>
@endsection
