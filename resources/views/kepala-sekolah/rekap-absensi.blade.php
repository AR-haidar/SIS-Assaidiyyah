@extends('kepala-sekolah.layout')
@section('title', 'Rekap Absensi')
@section('page-class', 'ks-attendance-page')
@section('content')
    @php
        $daftarPeriode = $daftarPeriode ?? collect();
        $daftarRombel = $daftarRombel ?? collect();
        $matriksRombel = $matriksRombel ?? collect();
        $isPaginator = $matriksRombel instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $ringkasan = $ringkasan ?? [
            'presensi_siswa' => null,
            'presensi_pendidik' => null,
            'hari_efektif' => null,
        ];
        $urlIndex = \Illuminate\Support\Facades\Route::has('kepala.rekap-absensi.index')
            ? route('kepala.rekap-absensi.index')
            : url('/kepala-sekolah/rekap-absensi');
    @endphp
    <header class="ks-page-heading">
        <h1>Rekapitulasi Presensi MI Assaidiyyah</h1>
        <p>Pemantauan komprehensif tingkat kehadiran peserta didik dan tenaga pendidik</p>
    </header>

    <form method="GET" action="{{ $urlIndex }}" class="ks-panel ks-attendance-filters">
        <div class="ks-filter-heading">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z" />
                </svg>
                Parameter Analisis Presensi
            </span>
            <small>Juknis Ditjen Pendis Kemenag RI</small>
        </div>
        <div class="ks-attendance-filter-row">
            <label>
                Periode Kalender <b>*</b>
                <select name="periode" aria-label="Periode Kalender">
                    <option value="">Pilih periode</option>
                    @foreach ($daftarPeriode as $periode)
                        @php
                            $val = is_object($periode) ? $periode->nilai ?? ($periode->kode ?? $periode->id) : $periode;
                            $lbl = is_object($periode) ? $periode->label ?? ($periode->nama ?? $val) : $periode;
                        @endphp
                        <option value="{{ $val }}" @selected((string) request('periode') === (string) $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Tingkat / Kelas
                <select name="rombel_id" aria-label="Tingkat atau kelas">
                    <option value="">Semua tingkat</option>
                    @foreach ($daftarRombel as $rombel)
                        <option value="{{ $rombel->id }}" @selected((string) request('rombel_id') === (string) $rombel->id)>{{ $rombel->nama }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit" class="ks-filter-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z" />
                </svg>
                Terapkan Filter
            </button>
        </div>
    </form>

    <section class="ks-attendance-kpis" aria-label="Ringkasan presensi">
        <article>
            <span>PRESENSI SISWA</span>
            <small>Rata-Rata Kehadiran<br>Siswa</small>
            <strong>{{ isset($ringkasan['presensi_siswa']) ? number_format((float) $ringkasan['presensi_siswa'], 1) : '-' }}<small>%</small></strong>
        </article>
        <article>
            <span>PRESENSI PENDIDIK</span>
            <small>Rata-Rata Kehadiran<br>Guru</small>
            <strong>{{ isset($ringkasan['presensi_pendidik']) ? number_format((float) $ringkasan['presensi_pendidik'], 1) : '-' }}<small>%</small></strong>
        </article>
        <article>
            <span>KALENDER AKADEMIK</span>
            <small>Hari Efektif Belajar</small>
            <strong>{{ $ringkasan['hari_efektif'] ?? '-' }}<small>Hari</small></strong>
        </article>
    </section>

    <section class="ks-panel ks-matrix-card">
        <div class="ks-matrix-title">
            <h2>Matriks Presensi Siswa</h2>
            <span><i></i> Siklus Real-Time</span>
        </div>
        <div class="ks-table-scroll">
            <table class="ks-data-table ks-attendance-matrix">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tingkat / Kelas</th>
                        <th>Wali Kelas</th>
                        <th>Total Siswa</th>
                        <th>Hadir (%)</th>
                        <th>Izin (%)</th>
                        <th>Sakit (%)</th>
                        <th>Alpa (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matriksRombel as $index => $baris)
                        @php
                            $nomor = $isPaginator ? $matriksRombel->firstItem() + $index : $index + 1;
                            $namaKelas = is_array($baris)
                                ? $baris['kelas'] ?? '—'
                                : $baris->kelas ?? ($baris->nama ?? '—');
                            $wali = is_array($baris) ? $baris['wali_kelas'] ?? '—' : $baris->wali_kelas ?? '—';
                            $totalSiswa = is_array($baris) ? $baris['total_siswa'] ?? 0 : $baris->total_siswa ?? 0;
                            $hadirPct = is_array($baris) ? $baris['hadir_persen'] ?? 0 : $baris->hadir_persen ?? 0;
                            $izinPct = is_array($baris) ? $baris['izin_persen'] ?? 0 : $baris->izin_persen ?? 0;
                            $sakitPct = is_array($baris) ? $baris['sakit_persen'] ?? 0 : $baris->sakit_persen ?? 0;
                            $alpaPct = is_array($baris) ? $baris['alpa_persen'] ?? 0 : $baris->alpa_persen ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $nomor }}</td>
                            <td><strong>{{ $namaKelas }}</strong></td>
                            <td>{{ $wali }}</td>
                            <td>{{ $totalSiswa }}</td>
                            <td>{{ number_format((float) $hadirPct, 1) }}%</td>
                            <td>{{ number_format((float) $izinPct, 1) }}%</td>
                            <td>{{ number_format((float) $sakitPct, 1) }}%</td>
                            <td>{{ number_format((float) $alpaPct, 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="ks-empty-state">
                                    <strong>Belum Ada Data Rekapitulasi Terpilih</strong>
                                    <p>Pilih rentang periode dan parameter filter di atas untuk<br> mengkalkulasi
                                        rekapitulasi kehadiran madrasah.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <footer class="ks-table-footer">
            <span>Menampilkan {{ $matriksRombel->count() }} dari
                {{ $isPaginator ? $matriksRombel->total() : $matriksRombel->count() }} rombel</span>
            @if ($isPaginator && $matriksRombel->hasPages())
                <div class="ks-pagination">{{ $matriksRombel->links() }}</div>
            @else
                <div class="ks-pagination"><button type="button"
                        disabled>&lt;</button><span>{{ $matriksRombel->isEmpty() ? 0 : 1 }}</span><button type="button"
                        disabled>&gt;</button></div>
            @endif
        </footer>
    </section>
@endsection
