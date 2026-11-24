@extends('humas.layout')
@section('title', 'Rekap Pembayaran')
@section('page-class', 'ks-payments-page')
@section('content')
    @php
        $daftarTahunAjaran = $daftarTahunAjaran ?? collect();
        $daftarRombel = $daftarRombel ?? collect();
        $rincianKelas = $rincianKelas ?? collect();
        $isPaginator = $rincianKelas instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $ringkasan = $ringkasan ?? [
            'target' => null,
            'tunggakan' => null,
            'per_kategori' => [],
        ];
        $perKategori = $ringkasan['per_kategori'] ?? [];
        $urlIndex = \Illuminate\Support\Facades\Route::has('humas.rekap-pembayaran.index')
            ? route('humas.rekap-pembayaran.index')
            : (\Illuminate\Support\Facades\Route::has('kepala.rekap-pembayaran.index')
                ? route('kepala.rekap-pembayaran.index')
                : url('/humas/rekap-pembayaran'));
        $urlPdf = \Illuminate\Support\Facades\Route::has('humas.rekap-pembayaran.pdf')
            ? route('humas.rekap-pembayaran.pdf', request()->query())
            : (\Illuminate\Support\Facades\Route::has('kepala.rekap-pembayaran.pdf')
                ? route('kepala.rekap-pembayaran.pdf', request()->query())
                : url('/humas/rekap-pembayaran/pdf'));
    @endphp
    <header class="ks-page-heading ks-heading-with-action">
        <div>
            <h1>Rekapitulasi Pembayaran MI Assaidiyyah</h1>
            <p>Rekap seluruh kategori tagihan siswa</p>
        </div>
        <a href="{{ $urlPdf }}" class="ks-outline-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            Cetak Rekap Finansial (PDF)
        </a>
    </header>

    <form method="GET" action="{{ $urlIndex }}" class="ks-panel ks-payment-filters">
        <label>
            <span class="ks-payment-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                Periode Akademik
            </span>
            <select name="tahun_ajaran_id" aria-label="Periode akademik">
                <option value="">Pilih periode</option>
                @foreach ($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id }}" @selected((string) request('tahun_ajaran_id') === (string) $ta->id)>{{ $ta->kode }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <span class="ks-payment-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21a8 8 0 0 1 16 0" />
                </svg>
                Tingkat
            </span>
            <select name="rombel_id" aria-label="Tingkat">
                <option value="">Semua Tingkat</option>
                @foreach ($daftarRombel as $rombel)
                    <option value="{{ $rombel->id }}" @selected((string) request('rombel_id') === (string) $rombel->id)>{{ $rombel->nama }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <span class="ks-payment-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="12" r="9" />
                    <path d="m8 12 2.5 2.5L16 9" />
                </svg>
                Status Pembayaran
            </span>
            <select name="status" aria-label="Status pembayaran">
                <option value="">Semua Status</option>
                <option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
                <option value="belum_lunas" @selected(request('status') === 'belum_lunas')>Belum Lunas</option>
            </select>
        </label>
        <button class="ks-filter-button" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z" />
            </svg>
            Terapkan Filter
        </button>
    </form>

    <section class="ks-payment-kpis">
        <article>
            <span>TARGET PAGU ANGGARAN</span>
            <strong>{{ isset($ringkasan['target']) ? 'Rp ' . number_format((float) $ringkasan['target'], 0, ',', '.') : 'Rp -' }}</strong>
        </article>
        <article>
            <span>TOTAL TUNGGAKAN</span>
            <strong>{{ isset($ringkasan['tunggakan']) ? 'Rp ' . number_format((float) $ringkasan['tunggakan'], 0, ',', '.') : 'Rp -' }}</strong>
        </article>
    </section>

    <section class="ks-payment-kpis">
        @foreach (['Infaq', 'Uang Buku', 'Wisata', 'Seragam', 'Tagihan Lainnya'] as $labelKat)
            @php $nominalKat = $perKategori[$labelKat] ?? null; @endphp
            <article>
                <span>{{ $labelKat }}</span>
                <strong>{{ isset($nominalKat) ? 'Rp ' . number_format((float) $nominalKat, 0, ',', '.') : 'Rp -' }}</strong>
            </article>
        @endforeach
    </section>

    <section class="ks-panel ks-payment-table-card">
        <div class="ks-payment-table-heading">
            <div>
                <strong>Rincian Kolektivitas per Kelas</strong>
                <span>{{ $daftarTahunAjaran->firstWhere('id', request('tahun_ajaran_id'))?->kode ?? ($daftarTahunAjaran->firstWhere('aktif', true)?->kode ?? 'Periode Akademik') }}</span>
            </div>
            <small><i class="ks-sync-dot"></i> Sinkronisasi EMIS: Siap Diproses</small>
        </div>
        <div class="ks-table-scroll">
            <table class="ks-data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tingkat / Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Target (Rp)</th>
                        <th>Realisasi Terkumpul (Rp)</th>
                        <th>Sisa Tunggakan (Rp)</th>
                        <th>Rasio (%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rincianKelas as $index => $baris)
                        @php
                            $nomor = $isPaginator ? $rincianKelas->firstItem() + $index : $index + 1;
                            $kelas = is_array($baris) ? $baris['kelas'] ?? '—' : $baris->kelas ?? ($baris->nama ?? '—');
                            $jmlSiswa = is_array($baris) ? $baris['jumlah_siswa'] ?? 0 : $baris->jumlah_siswa ?? 0;
                            $target = is_array($baris) ? $baris['target'] ?? 0 : $baris->target ?? 0;
                            $realisasi = is_array($baris) ? $baris['realisasi'] ?? 0 : $baris->realisasi ?? 0;
                            $tunggakan = is_array($baris)
                                ? $baris['tunggakan'] ?? max(0, (float) $target - (float) $realisasi)
                                : $baris->tunggakan ?? max(0, (float) $target - (float) $realisasi);
                            $rasio = is_array($baris) ? $baris['rasio'] ?? 0 : $baris->rasio ?? 0;
                            $statusLabel = is_array($baris) ? $baris['status'] ?? '—' : $baris->status ?? '—';
                        @endphp
                        <tr>
                            <td>{{ $nomor }}</td>
                            <td><strong>{{ $kelas }}</strong></td>
                            <td>{{ $jmlSiswa }}</td>
                            <td>Rp {{ number_format((float) $target, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format((float) $realisasi, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format((float) $tunggakan, 0, ',', '.') }}</td>
                            <td>{{ number_format((float) $rasio, 1) }}%</td>
                            <td>{{ $statusLabel }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="ks-empty-state">
                                    <strong>Belum Ada Data Rekapitulasi Finansial</strong>
                                    <p>Pilih periode tahun ajaran dan klik tombol filter untuk memuat agregat seluruh
                                        kategori tagihan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
