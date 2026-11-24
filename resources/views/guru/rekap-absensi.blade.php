@extends('guru.layout')
@section('title', 'Rekap Absensi Guru')
@section('page-class', 'guru-attendance-page')
@section('content')
    <header class="guru-page-heading"><h1>Dashboard Guru &amp; Staff</h1></header>
    <form class="guru-attendance-filter" action="{{ route('guru.rekap_absensi.index') }}" method="GET" aria-label="Filter rekap kehadiran">
        <label>Pilih Periode / Bulan
            <select name="bulan" aria-label="Pilih periode atau bulan">
                <option value="">Pilih periode / bulan</option>
                @foreach (range(1, 12) as $bulan)
                    <option value="{{ $bulan }}" @selected((int) ($filter['bulan'] ?? 0) === $bulan)>{{ \Illuminate\Support\Carbon::create(2000, $bulan, 1)->locale('id')->monthName }}</option>
                @endforeach
            </select>
        </label>
        <label>Tahun Akademik
            <select name="tahun_ajaran_id" aria-label="Tahun akademik">
                <option value="">Pilih tahun akademik</option>
                @foreach ($tahunAjaran as $tahun)
                    <option value="{{ $tahun->id }}" @selected((int) ($filter['tahun_ajaran_id'] ?? 0) === $tahun->id)>{{ $tahun->kode }}</option>
                @endforeach
            </select>
        </label>
        <label>Filter Status
            <select name="status" aria-label="Filter status">
                <option value="">Semua Status</option>
                @foreach (\App\Enums\StatusPresensiPegawai::opsi() as $nilai => $label)
                    <option value="{{ $nilai }}" @selected(($filter['status'] ?? null) === $nilai)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <button type="submit" class="guru-filter-button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z"/></svg>Terapkan Filter</button>
    </form>
    <section class="guru-attendance-stats" aria-label="Ringkasan kehadiran">
        <article><div><span>Rata-Rata Kehadiran</span><b class="guru-stat-icon">%</b></div><strong>{{ $rataKehadiran ?? '-' }} %</strong><small>Target madrasah: 95% @if ($rataKehadiran !== null) · {{ $targetTercapai ? 'Tercapai' : 'Belum tercapai' }} @endif</small></article>
        <article><div><span>Hari Hadir Kerja</span><b class="guru-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg></b></div><strong>{{ $hariHadir }} Hari</strong><small>Presensi tercatat</small></article>
        <article><div><span>Izin / Tugas Luar</span><b class="guru-stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h6"/></svg></b></div><strong>{{ $hariIzin }} Hari</strong><small>Catatan izin atau tugas luar</small></article>
        <article><div><span>Sakit / Medis</span><b class="guru-stat-icon">+</b></div><strong>{{ $hariSakit }} Hari</strong><small>Catatan sakit</small></article>
    </section>
    <section class="guru-attendance-table-card">
        <div class="guru-attendance-table-heading"><h2>Riwayat Presensi Mandiri Pendidik</h2><span>{{ $riwayat->count() }} data dimuat</span></div>
        <div class="guru-table-scroll"><table class="guru-attendance-table">
            <thead><tr><th>No</th><th>Tanggal</th><th>Status Kehadiran</th><th>Keterangan</th></tr></thead>
            <tbody>
                @foreach ($riwayat as $catatan)
                    <tr class="[&>td]:px-4 [&>td]:py-3">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $catatan->tanggal->format('d-m-Y') }}</td>
                        <td>{{ $catatan->status->label() }}</td>
                        <td>{{ $catatan->keterangan ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
        @if ($riwayat->isEmpty())
            <div class="guru-attendance-empty"><strong>Belum Ada Catatan Kehadiran untuk Periode Terpilih</strong><p>Catatan presensi yang sesuai akan ditampilkan di sini ketika tersedia.</p></div>
        @endif
    </section>
@endsection
