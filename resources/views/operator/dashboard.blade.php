@extends('operator.layout')
@section('title', 'Dashboard Operator')
@section('content')
    @php
        $statistik = $statistik ?? [];
        $aktivitasTerbaru = $aktivitasTerbaru ?? collect();
        $pengumumanAktif = $pengumumanAktif ?? collect();
        $daftarModul = $daftarModul ?? [
            'siswa' => 'Kesiswaan',
            'pegawai' => 'Kepegawaian',
            'akademik' => 'Akademik',
            'pengumuman' => 'Pengumuman',
            'sekolah' => 'Data Sekolah',
        ];
        $daftarPeriode = $daftarPeriode ?? [
            'hari_ini' => 'Hari Ini',
            '7_hari' => '7 Hari Terakhir',
            '30_hari' => '30 Hari Terakhir',
        ];

        $dashboardUrl = \Illuminate\Support\Facades\Route::has('operator.dashboard')
            ? route('operator.dashboard')
            : url('/operator/dashboard');
        $siswaCreateUrl = \Illuminate\Support\Facades\Route::has('operator.siswa.create')
            ? route('operator.siswa.create')
            : (\Illuminate\Support\Facades\Route::has('operator.siswa.index')
                ? route('operator.siswa.index')
                : url('/operator/data-siswa'));
        $pegawaiCreateUrl = \Illuminate\Support\Facades\Route::has('operator.pegawai.create')
            ? route('operator.pegawai.create')
            : (\Illuminate\Support\Facades\Route::has('operator.pegawai.index')
                ? route('operator.pegawai.index')
                : url('/operator/data-guru'));
        $pegawaiIndexUrl = \Illuminate\Support\Facades\Route::has('operator.pegawai.index')
            ? route('operator.pegawai.index')
            : url('/operator/data-guru');
        $sekolahUrl = \Illuminate\Support\Facades\Route::has('operator.rombel.index')
            ? route('operator.rombel.index')
            : (\Illuminate\Support\Facades\Route::has('operator.sekolah.edit')
                ? route('operator.sekolah.edit')
                : url('/operator/data-sekolah'));
        $pengumumanIndexUrl = \Illuminate\Support\Facades\Route::has('operator.pengumuman.index')
            ? route('operator.pengumuman.index')
            : url('/operator/pengumuman');
        $pengumumanCreateUrl = \Illuminate\Support\Facades\Route::has('operator.pengumuman.create')
            ? route('operator.pengumuman.create')
            : $pengumumanIndexUrl;

        $isPaginator = $aktivitasTerbaru instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    @endphp
    <header class="operator-heading">
        <h1>Dashboard Administrasi Operator</h1>
        <p>Pusat kendali pengelolaan data pokok MI Assaidiyyah, kesiswaan, kepegawaian, dan pengumuman resmi lembaga</p>
    </header>

    <section class="operator-stats" aria-label="Ringkasan administrasi">
        <article class="operator-stat-card">
            <span>Total Siswa<br>Terdaftar</span>
            <strong>{{ isset($statistik['total_siswa']) ? $statistik['total_siswa'] . ' Siswa' : '- Siswa' }}</strong>
        </article>
        <article class="operator-stat-card">
            <span>Pendidik &amp; Tenaga<br>Kependidikan</span>
            <strong>{{ $statistik['total_gtk'] ?? '-' }}</strong>
        </article>
        <article class="operator-stat-card">
            <span>Kelas</span>
            <strong>{{ isset($statistik['total_kelas']) ? $statistik['total_kelas'] . ' Kelas' : '- Kelas' }}</strong>
        </article>
        <article class="operator-stat-card">
            <span>Pengumuman Aktif</span>
            <strong>{{ isset($statistik['pengumuman_aktif']) ? $statistik['pengumuman_aktif'] . ' Pengumuman' : '- Pengumuman' }}</strong>
        </article>
    </section>

    <div class="operator-columns">
        <section class="operator-card operator-activity">
            <h2>Aktivitas Administrasi Terbaru</h2>
            <form method="GET" action="{{ $dashboardUrl }}" class="operator-filters">
                <label class="operator-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-4-4" />
                    </svg>
                    <input type="search" name="search" value="{{ request('search', '') }}"
                        placeholder="Cari berdasarkan kata kunci aktivitas..." aria-label="Cari aktivitas">
                </label>
                <select name="modul" aria-label="Pilih modul" onchange="this.form.submit()">
                    <option value="">Semua Modul</option>
                    @foreach ($daftarModul as $kodeModul => $labelModul)
                        <option value="{{ $kodeModul }}" @selected((string) request('modul') === (string) $kodeModul)>{{ $labelModul }}</option>
                    @endforeach
                </select>
                <select name="periode" aria-label="Pilih periode" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    @foreach ($daftarPeriode as $kodePeriode => $labelPeriode)
                        <option value="{{ $kodePeriode }}" @selected((string) request('periode') === (string) $kodePeriode)>{{ $labelPeriode }}</option>
                    @endforeach
                </select>
            </form>

            <div class="operator-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>Aktivitas / Perubahan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aktivitasTerbaru as $index => $log)
                            @php
                                $nomor = $isPaginator ? $aktivitasTerbaru->firstItem() + $index : $index + 1;
                            @endphp
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td>{{ !empty($log->created_at) ? \Illuminate\Support\Carbon::parse($log->created_at)->translatedFormat('d M Y H:i') : '-' }}
                                </td>
                                <td>{{ $log->ringkasan ?? ($log->deskripsi ?? '-') }}</td>
                                <td>{{ $log->status ?? ucfirst(str_replace('_', ' ', (string) ($log->aksi ?? 'Tercatat'))) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="operator-empty-cell">
                                    <strong>Belum Ada Aktivitas Tercatat</strong>
                                    <p>Seluruh riwayat penambahan siswa, perubahan GTK, dan penerbitan pengumuman akan
                                        tercatat secara kronologis di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <small class="operator-count">Menampilkan
                {{ $aktivitasTerbaru->count() }}{{ $isPaginator ? ' dari ' . $aktivitasTerbaru->total() : '' }}</small>
        </section>

        <div class="operator-side">
            <section class="operator-card operator-announcement">
                <h2>Pengumuman MI Assaidiyyah</h2>
                @if ($pengumumanAktif->isNotEmpty())
                    <div class="operator-announcement-list">
                        @foreach ($pengumumanAktif as $item)
                            <article>
                                <strong>{{ $item->judul }}</strong>
                                <small>{{ !empty($item->tanggal_terbit) ? \Illuminate\Support\Carbon::parse($item->tanggal_terbit)->translatedFormat('d M Y') : '-' }}</small>
                            </article>
                        @endforeach
                    </div>
                    <a href="{{ $pengumumanIndexUrl }}">Kelola Semua Pengumuman</a>
                @else
                    <div class="operator-empty">
                        <span class="shared-announcement-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg"
                                width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z" />
                                <path d="M18 9a4 4 0 0 1 0 6" />
                            </svg></span>
                        <strong>Belum Ada Pengumuman Aktif</strong>
                        <p>Pengumuman internal guru maupun siaran terbuka belum dirilis.</p>
                        <a href="{{ $pengumumanCreateUrl }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                aria-hidden="true">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Buat Pengumuman Baru
                        </a>
                    </div>
                @endif
            </section>

            <section class="operator-card operator-quick">
                <h2>Akses Ringkas Operasional</h2>
                <div class="operator-quick-links">
                    <a href="{{ $siswaCreateUrl }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="8" r="3" />
                            <path d="M3 20v-1a6 6 0 0 1 12 0v1M19 8v6M16 11h6" />
                        </svg>Input Siswa</a>
                    <a href="{{ $pegawaiCreateUrl }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="8" r="3" />
                            <path d="M3 20v-1a6 6 0 0 1 12 0v1M16 4h5v5M18 10l3-3" />
                        </svg>Input GTK</a>
                    <a href="{{ $sekolahUrl }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4" />
                            <path d="M9 9v.01M9 13v.01M9 17v.01" />
                        </svg>Atur Rombel</a>
                    <a href="{{ $pegawaiIndexUrl }}"><svg xmlns="http://www.w3.org/2000/svg" width="14"
                            height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="17" rx="2" />
                            <circle cx="12" cy="11" r="3" />
                            <path d="M7 21v-1a5 5 0 0 1 10 0v1M8 2v4M16 2v4" />
                        </svg>Wali Kelas</a>
                </div>
            </section>
        </div>
    </div>
@endsection
