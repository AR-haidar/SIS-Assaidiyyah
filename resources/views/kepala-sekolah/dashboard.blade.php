@extends('kepala-sekolah.layout')
@section('title', 'Dashboard Kepala Sekolah')
@section('page-class', 'ks-dashboard-page')
@section('content')
    @php
        $ringkasan = $ringkasan ?? [];
        $rekapPresensi = $rekapPresensi ?? [];
        $keuangan = $keuangan ?? [];
        $rekapPerTingkat = $rekapPerTingkat ?? collect();
        $daftarPengumuman = $daftarPengumuman ?? collect();
        $daftarBulan = $daftarBulan ?? [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $bulanTerpilih = (string) request('bulan', '');

        $dashboardUrl = \Illuminate\Support\Facades\Route::has('kepala.dashboard')
            ? route('kepala.dashboard')
            : url('/kepala-sekolah/dashboard');
        $pengumumanIndexUrl = \Illuminate\Support\Facades\Route::has('kepala.pengumuman.index')
            ? route('kepala.pengumuman.index')
            : url('/kepala-sekolah/pengumuman');
        $pengumumanCreateUrl = \Illuminate\Support\Facades\Route::has('kepala.pengumuman.create')
            ? route('kepala.pengumuman.create')
            : $pengumumanIndexUrl;

        $progressKeuangan = min(100, max(0, (float) ($keuangan['persentase'] ?? 0)));
    @endphp
    <header class="ks-page-heading">
        <h1>Dashboard Kepala MI Assaidiyyah</h1>
        <p>Ringkasan pemantauan terpadu presensi madrasah, ketercapaian pembayaran Infaq dan tagihan lain, serta edaran
            resmi.</p>
    </header>

    <section class="ks-summary-grid" aria-label="Ringkasan madrasah">
        <article class="ks-summary-card">
            <span>PRESENSI SISWA HARIAN</span>
            <strong>{{ isset($ringkasan['presensi_siswa']) ? number_format((float) $ringkasan['presensi_siswa'], 1) . ' %' : '- %' }}</strong>
        </article>
        <article class="ks-summary-card">
            <span>KEHADIRAN GURU &amp; STAF HARIAN</span>
            <strong>{{ isset($ringkasan['presensi_pendidik']) ? number_format((float) $ringkasan['presensi_pendidik'], 1) . ' %' : '- %' }}</strong>
        </article>
        <article class="ks-summary-card">
            <span>REALISASI INFAQ &amp; TAGIHAN SEMESTER INI</span>
            <strong>{{ isset($ringkasan['realisasi_keuangan']) ? 'Rp ' . number_format((float) $ringkasan['realisasi_keuangan'], 0, ',', '.') : 'Rp -' }}</strong>
        </article>
        <article class="ks-summary-card">
            <span>TUNGGAKAN TAGIHAN BERJALAN</span>
            <strong>{{ isset($ringkasan['tunggakan_keuangan']) ? 'Rp ' . number_format((float) $ringkasan['tunggakan_keuangan'], 0, ',', '.') : 'Rp -' }}</strong>
        </article>
    </section>

    <div class="ks-dashboard-grid">
        <div class="ks-dashboard-primary">
            <section class="ks-panel ks-attendance-panel">
                <div class="ks-panel-heading">
                    <div>
                        <h2>Rekapitulasi Presensi</h2>
                        <p>Pantauan komparatif harian siswa dan pendidik</p>
                    </div>
                    <form method="GET" action="{{ $dashboardUrl }}">
                        <label class="ks-compact-select">
                            <span class="sr-only">Pilih bulan</span>
                            <select name="bulan" aria-label="Pilih bulan" onchange="this.form.submit()">
                                <option value="">Bulan</option>
                                @foreach ($daftarBulan as $angkaBulan => $namaBulan)
                                    <option value="{{ $angkaBulan }}" @selected($bulanTerpilih === (string) $angkaBulan)>{{ $namaBulan }}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                    </form>
                </div>
                <div class="ks-attendance-chart" role="img" aria-label="Grafik rekap presensi harian">
                    <div class="ks-chart-caption">Grafik<br>Harian</div>
                    <div class="ks-chart-lines"><i></i><i></i><i></i><i></i></div>
                    <div class="ks-chart-weeks"><span>Minggu 1</span><span>Minggu 2</span><span>Minggu 3</span><span>Minggu
                            4</span></div>
                </div>
                <div class="ks-attendance-legend">
                    <span><i class="ks-dot hadir"></i>Hadir
                        (H)<b>{{ isset($rekapPresensi['hadir_persen']) ? number_format((float) $rekapPresensi['hadir_persen'], 1) . ' %' : '- %' }}</b></span>
                    <span><i class="ks-dot izin"></i>Izin
                        (I)<b>{{ isset($rekapPresensi['izin_persen']) ? number_format((float) $rekapPresensi['izin_persen'], 1) . ' %' : '- %' }}</b></span>
                    <span><i
                            class="ks-dot sakit"></i>Sakit<b>{{ isset($rekapPresensi['sakit_persen']) ? number_format((float) $rekapPresensi['sakit_persen'], 1) . ' %' : '- %' }}</b></span>
                    <span><i
                            class="ks-dot alpa"></i>Alpa<b>{{ isset($rekapPresensi['alpa_persen']) ? number_format((float) $rekapPresensi['alpa_persen'], 1) . ' %' : '- %' }}</b></span>
                </div>
            </section>

            <section class="ks-panel ks-finance-summary-panel">
                <h2>Rekapitulasi Keuangan</h2>
                <div class="ks-budget-progress">
                    <div>
                        <span>Target RAPBM:
                            <b>{{ isset($keuangan['target']) ? 'Rp ' . number_format((float) $keuangan['target'], 0, ',', '.') : 'Rp -' }}</b></span>
                        <span>Realisasi Kas:
                            <b>{{ isset($keuangan['realisasi']) ? 'Rp ' . number_format((float) $keuangan['realisasi'], 0, ',', '.') : 'Rp -' }}</b></span>
                    </div>
                    <div class="ks-progress-track"><i style="width: {{ $progressKeuangan }}%;"></i></div>
                    <div>
                        <span>Progress Pengumpulan:
                            {{ isset($keuangan['persentase']) ? number_format((float) $keuangan['persentase'], 1) . ' %' : '- %' }}</span>
                        <span>Sisa Defisit:
                            {{ isset($keuangan['defisit']) ? 'Rp ' . number_format((float) $keuangan['defisit'], 0, ',', '.') : 'Rp -' }}</span>
                    </div>
                </div>
                <div class="ks-mini-table-wrap">
                    <table class="ks-mini-table">
                        <thead>
                            <tr>
                                <th>Tingkat Kelas</th>
                                <th>Total Santri</th>
                                <th>Tercapai</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rekapPerTingkat->isNotEmpty())
                                @foreach ($rekapPerTingkat as $baris)
                                    @php
                                        $tingkat = is_array($baris) ? $baris['tingkat'] ?? '-' : $baris->tingkat ?? '-';
                                        $totalSantri = is_array($baris)
                                            ? $baris['total_santri'] ?? 0
                                            : $baris->total_santri ?? 0;
                                        $tercapai = is_array($baris) ? $baris['tercapai'] ?? 0 : $baris->tercapai ?? 0;
                                        $persen = is_array($baris)
                                            ? $baris['persentase'] ?? 0
                                            : $baris->persentase ?? 0;
                                    @endphp
                                    <tr>
                                        <td>Kelas {{ $tingkat }}</td>
                                        <td>{{ $totalSantri }}</td>
                                        <td>Rp {{ number_format((float) $tercapai, 0, ',', '.') }}</td>
                                        <td>{{ number_format((float) $persen, 1) }} %</td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach (['I', 'II', 'III', 'IV', 'V', 'VI'] as $kelas)
                                    <tr>
                                        <td>Kelas {{ $kelas }}</td>
                                        <td>-</td>
                                        <td>Rp -</td>
                                        <td>- %</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <section class="ks-panel ks-announcement-summary">
            <div class="ks-panel-heading">
                <h2>Pengumuman</h2>
                <a href="{{ $pengumumanIndexUrl }}">Kelola</a>
            </div>

            @if ($daftarPengumuman->isNotEmpty())
                <div class="ks-announcement-list">
                    @foreach ($daftarPengumuman as $item)
                        <article>
                            <strong>{{ $item->judul }}</strong>
                            <small>{{ !empty($item->tanggal_terbit) ? \Illuminate\Support\Carbon::parse($item->tanggal_terbit)->translatedFormat('d M Y') : '-' }}
                                &bull;
                                {{ $item->status?->label() ?? ucfirst((string) ($item->status ?? 'Terbit')) }}</small>
                        </article>
                    @endforeach
                </div>
                <a class="ks-primary-button" href="{{ $pengumumanCreateUrl }}"><span>+</span> Buat Pengumuman Baru</a>
            @else
                <div class="ks-announcement-empty">
                    <span class="shared-announcement-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg"
                            width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z" />
                            <path d="M18 9a4 4 0 0 1 0 6" />
                        </svg></span>
                    <strong>Belum Ada Pengumuman Diterbitkan</strong>
                    <p>Pengumuman dan instruksi resmi Kepala Madrasah akan tampil di sini untuk siswa dan dewan guru.</p>
                    <a class="ks-primary-button" href="{{ $pengumumanCreateUrl }}"><span>+</span> Buat Pengumuman Baru</a>
                </div>
            @endif
        </section>
    </div>
@endsection
