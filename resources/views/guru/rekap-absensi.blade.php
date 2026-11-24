@extends('guru.layout')
@section('title', 'Rekap Absensi Guru')
@section('page-class', 'guru-attendance-page')
@section('content')
    @php
        $riwayatPegawai = collect($riwayatPegawai ?? ($daftarPresensi ?? []));
        $catatanValid = $riwayatPegawai->filter(function ($item) {
            $val = $item->status_validasi?->value ?? (string) ($item->status_validasi ?? 'valid');
            return $val === 'valid';
        });
        $totalValid = $catatanValid->count();
        $totalHadir = $catatanValid
            ->filter(fn($item) => ($item->status?->value ?? (string) $item->status) === 'hadir')
            ->count();
        $totalIzin = $catatanValid
            ->filter(
                fn($item) => in_array(
                    $item->status?->value ?? (string) $item->status,
                    ['izin', 'izin_tugas_luar', 'dinas_luar'],
                    true,
                ),
            )
            ->count();
        $totalSakit = $catatanValid
            ->filter(fn($item) => ($item->status?->value ?? (string) $item->status) === 'sakit')
            ->count();
        $persenKehadiran = $totalValid > 0 ? number_format(($totalHadir / $totalValid) * 100, 1) : null;
        $targetMadrasah = $targetKehadiran ?? 95.0;
    @endphp

    <header class="guru-page-heading">
        <h1>Dashboard Guru &amp; Staff</h1>
    </header>
    <form method="GET" action="{{ url('/guru/rekap-absensi') }}" class="guru-attendance-filter"
        aria-label="Filter rekap kehadiran">
        <label>Pilih Periode / Bulan
            <select name="bulan" aria-label="Pilih periode atau bulan">
                <option value="">Pilih periode / bulan</option>
                @foreach (['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $m => $labelBulan)
                    <option value="{{ $m }}" @selected(request('bulan') === $m)>{{ $labelBulan }}</option>
                @endforeach
            </select>
        </label>
        <label>Tahun Akademik
            <select name="tahun" aria-label="Tahun akademik">
                <option value="">Pilih tahun akademik</option>
                <option value="2024/2025" @selected(request('tahun') === '2024/2025')>2024/2025</option>
                <option value="2025/2026" @selected(request('tahun') === '2025/2026')>2025/2026</option>
                <option value="2026/2027" @selected(request('tahun') === '2026/2027')>2026/2027</option>
            </select>
        </label>
        <label>Filter Status
            <select name="status" aria-label="Filter status">
                <option value="">Semua Status</option>
                <option value="hadir" @selected(request('status') === 'hadir')>Hadir</option>
                <option value="izin" @selected(request('status') === 'izin')>Izin</option>
                <option value="sakit" @selected(request('status') === 'sakit')>Sakit</option>
            </select>
        </label>
        <button type="submit" class="guru-filter-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z" />
            </svg>
            Terapkan Filter
        </button>
    </form>

    <section class="guru-attendance-stats" aria-label="Ringkasan kehadiran">
        <article>
            <div><span>Rata-Rata Kehadiran</span><b class="guru-stat-icon">%</b></div>
            <strong>{{ $persenKehadiran !== null ? $persenKehadiran . ' %' : '- %' }}</strong><small>Target madrasah: &ge;
                {{ number_format((float) $targetMadrasah, 1) }}%</small>
        </article>
        <article>
            <div><span>Hari Hadir Kerja</span><b class="guru-stat-icon"><svg xmlns="http://www.w3.org/2000/svg"
                        width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path d="m8 12 2.5 2.5L16 9" />
                    </svg></b></div><strong>{{ $totalValid > 0 ? $totalHadir . ' Hari' : '- Hari' }}</strong><small>Presensi
                terverifikasi</small>
        </article>
        <article>
            <div><span>Izin / Tugas Luar</span><b class="guru-stat-icon"><svg xmlns="http://www.w3.org/2000/svg"
                        width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <path d="M14 2v6h6" />
                        <path d="M8 13h8M8 17h6" />
                    </svg></b></div><strong>{{ $totalValid > 0 ? $totalIzin . ' Hari' : '- Hari' }}</strong><small>Surat dinas
                terbit</small>
        </article>
        <article>
            <div><span>Sakit / Medis</span><b class="guru-stat-icon">+</b></div>
            <strong>{{ $totalValid > 0 ? $totalSakit . ' Hari' : '- Hari' }}</strong><small>Keterangan dokter</small>
        </article>
    </section>

    <section class="guru-attendance-table-card">
        <div class="guru-attendance-table-heading">
            <h2>Riwayat Presensi Mandiri Pendidik</h2><span>{{ $riwayatPegawai->count() }} data dimuat</span>
        </div>
        <div class="guru-table-scroll">
            <table class="guru-attendance-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Status Kehadiran</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                @if ($riwayatPegawai->isNotEmpty())
                    <tbody>
                        @foreach ($riwayatPegawai as $i => $row)
                            @php
                                $st = $row->status?->value ?? (string) $row->status;
                                $stLabel = method_exists($row->status, 'label')
                                    ? $row->status->label()
                                    : ucfirst(str_replace('_', ' ', $st));
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</td>
                                <td>{{ $stLabel }}</td>
                                <td>{{ $row->keterangan ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        @if ($riwayatPegawai->isEmpty())
            <div class="guru-attendance-empty">
                <strong>Belum Ada Catatan Kehadiran untuk Periode Terpilih</strong>
                <p>Data log kehadiran ditarik secara otomatis dari mesin presensi<br class="guru-desktop-break"> biometrik
                    madrasah setelah rekonsiliasi harian oleh bagian Tata<br class="guru-desktop-break"> Usaha.</p>
            </div>
        @endif
    </section>
@endsection
