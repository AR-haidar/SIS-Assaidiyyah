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
                            <th>No</th><th>Nama Guru</th><th>NIP / ID Pegawai</th><th>NUPTK</th><th>Jabatan / Tugas</th><th>Status Kepegawaian</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="operator-teacher-empty">
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
            <footer>Menampilkan <strong>0</strong> dari <strong>0</strong> guru</footer>
        </section>
    </section>
@endsection