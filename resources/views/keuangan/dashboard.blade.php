@extends('keuangan.layout')
@section('title', 'Dashboard Keuangan')
@section('content')
    @php
        $statistik = $statistik ?? [];
        $pembayaranMenunggu = $pembayaranMenunggu ?? collect();
        $daftarJenisTagihan = $daftarJenisTagihan ?? collect();
        $jenisTerpilih = (string) request('jenis', '');

        $dashboardUrl = \Illuminate\Support\Facades\Route::has('keuangan.dashboard')
            ? route('keuangan.dashboard')
            : url('/keuangan/dashboard');
        $tambahTagihanUrl = \Illuminate\Support\Facades\Route::has('keuangan.tagihan.create')
            ? route('keuangan.tagihan.create')
            : url('/keuangan/tagihan/tambah');
        $pembayaranIndexUrl = \Illuminate\Support\Facades\Route::has('keuangan.pembayaran.index')
            ? route('keuangan.pembayaran.index')
            : url('/keuangan/pembayaran-siswa');
    @endphp
    <section class="keuangan-dashboard">
        <header class="keuangan-dashboard-heading">
            <div>
                <h1>Dashboard Keuangan</h1>
                <p>Ringkasan pemantauan transaksi seluruh kategori tagihan</p>
            </div>
            <div class="keuangan-dashboard-actions">
                <a href="{{ $tambahTagihanUrl }}" class="keuangan-button keuangan-button-light">Input Tagihan</a>
            </div>
        </header>

        <div class="keuangan-status-cards" aria-label="Ringkasan status pembayaran">
            <article class="keuangan-status-card">
                <span>Status Antrean</span>
                <h2>Menunggu Verifikasi</h2>
                <p><strong>{{ $statistik['menunggu'] ?? '—' }}</strong> Transaksi</p>
            </article>
            <article class="keuangan-status-card">
                <span>Status Valid</span>
                <h2>Pembayaran Disetujui</h2>
                <p><strong>{{ $statistik['disetujui'] ?? '—' }}</strong> Transaksi</p>
            </article>
            <article class="keuangan-status-card">
                <span>Status Retur</span>
                <h2>Pembayaran Ditolak</h2>
                <p><strong>{{ $statistik['ditolak'] ?? '—' }}</strong> Transaksi</p>
            </article>
        </div>

        <section class="keuangan-verification-card">
            <header class="keuangan-verification-heading">
                <div>
                    <h2>Pembayaran Menunggu Verifikasi</h2>
                    <p>Daftar pengajuan pembayaran santri yang membutuhkan konfirmasi petugas keuangan.</p>
                </div>
                <a href="{{ $pembayaranIndexUrl }}">Lihat Semua Pembayaran <span aria-hidden="true">&rarr;</span></a>
            </header>

            <div class="keuangan-quick-filters">
                <form method="GET" action="{{ $dashboardUrl }}" class="keuangan-filter-pills">
                    <span>Filter Cepat:</span>
                    <button type="submit" name="jenis" value=""
                        class="{{ $jenisTerpilih === '' ? 'selected' : '' }}">Semua Tingkat</button>
                    @if ($daftarJenisTagihan->isNotEmpty())
                        @foreach ($daftarJenisTagihan as $jenis)
                            @php
                                $jenisVal = is_object($jenis) ? $jenis->id ?? $jenis->nama : $jenis;
                                $jenisLbl = is_object($jenis) ? $jenis->nama : $jenis;
                            @endphp
                            <button type="submit" name="jenis" value="{{ $jenisVal }}"
                                class="{{ $jenisTerpilih === (string) $jenisVal ? 'selected' : '' }}">{{ $jenisLbl }}</button>
                        @endforeach
                    @else
                        @foreach (['Infaq', 'Uang Buku', 'Wisata', 'Seragam', 'Lainnya'] as $kategoriDefault)
                            <button type="submit" name="jenis" value="{{ $kategoriDefault }}"
                                class="{{ $jenisTerpilih === $kategoriDefault ? 'selected' : '' }}">{{ $kategoriDefault }}</button>
                        @endforeach
                    @endif
                </form>
                <span class="keuangan-sync">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M20 7v5h-5M4 17v-5h5" />
                        <path d="M5.6 9A7 7 0 0 1 18 6l2 2M4 16l2 2a7 7 0 0 0 12.4-3" />
                    </svg>
                    Sinkronisasi otomatis aktif
                </span>
            </div>

            <div class="keuangan-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Tagihan</th>
                            <th>Nominal</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayaranMenunggu as $index => $pembayaran)
                            @php
                                $nomor = method_exists($pembayaranMenunggu, 'firstItem')
                                    ? $pembayaranMenunggu->firstItem() + $index
                                    : $index + 1;
                                $detailUrl = \Illuminate\Support\Facades\Route::has('keuangan.pembayaran.show')
                                    ? route('keuangan.pembayaran.show', $pembayaran)
                                    : (\Illuminate\Support\Facades\Route::has('keuangan.pembayaran.detail')
                                        ? route('keuangan.pembayaran.detail', ['id' => $pembayaran->getKey()])
                                        : url('/keuangan/pembayaran-siswa/detail?id=' . $pembayaran->getKey()));
                                $namaSiswa =
                                    $pembayaran->tagihan?->siswa?->nama_lengkap ?? ($pembayaran->siswa_nama ?? '-');
                                $kelasSiswa =
                                    $pembayaran->kelas_nama ??
                                    (optional($pembayaran->tagihan?->siswa?->keanggotaanRombel?->first()?->rombel)
                                        ->nama ??
                                        '-');
                                $jenisTagihanNama =
                                    $pembayaran->tagihan?->jenisTagihan?->nama ??
                                    ($pembayaran->jenis_tagihan_nama ?? '-');
                                $nominal = $pembayaran->nominal_dibayar ?? ($pembayaran->nominal ?? 0);
                                $tanggalPengajuan = !empty($pembayaran->tanggal_bayar ?? $pembayaran->created_at)
                                    ? \Illuminate\Support\Carbon::parse(
                                        $pembayaran->tanggal_bayar ?? $pembayaran->created_at,
                                    )->translatedFormat('d M Y')
                                    : '-';
                                $statusLabel =
                                    $pembayaran->status_verifikasi?->label() ??
                                    ($pembayaran->status?->label() ?? 'Menunggu Verifikasi');
                            @endphp
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td><a href="{{ $detailUrl }}"><strong>{{ $namaSiswa }}</strong></a></td>
                                <td>{{ $kelasSiswa }}</td>
                                <td>{{ $jenisTagihanNama }}</td>
                                <td>Rp {{ number_format((float) $nominal, 0, ',', '.') }}</td>
                                <td>{{ $tanggalPengajuan }}</td>
                                <td>{{ $statusLabel }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="keuangan-empty-state">
                                    <strong>Belum Ada Pengajuan Pembayaran Menunggu Verifikasi</strong>
                                    <p>Seluruh kiriman bukti pembayaran santri telah diverifikasi atau belum<br
                                            class="keuangan-desktop-break"> ada entri baru yang masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
