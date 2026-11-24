@extends('operator.layout')
@section('title', 'Data Guru')
@section('content')
    @php
        $daftarPegawai = $pegawaiList ?? collect();
        $isPaginator = $daftarPegawai instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $totalGuru = $statistik['total'] ?? ($isPaginator ? $daftarPegawai->total() : $daftarPegawai->count());
        $totalWaliKelas = $statistik['wali_kelas'] ?? 0;
        $opsiStatus = $opsiStatusKepegawaian ?? collect();
    @endphp

    <section class="operator-teachers">
        @if (session('status'))
            <div
                class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <header class="operator-teachers-heading">
            <div>
                <h1>Direktori &amp; Manajemen Data Guru</h1>
                <p>Pengelolaan data pendidik dan tenaga kependidikan</p>
            </div>
            <a class="operator-primary-button" href="{{ route('operator.pegawai.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah Guru Baru
            </a>
        </header>

        <section class="operator-student-import" aria-label="Impor guru dari Excel atau CSV">
            <div><strong>Impor data guru dari Excel atau CSV</strong>
                <p>Pilih .xlsx atau .csv. Data dari sheet guru dan tenaga kependidikan akan ditambahkan; NIP/NUPTK duplikat
                    dilewati.</p>
            </div>
            <label class="operator-primary-button operator-student-import-button"
                style="margin-block:14px;min-height:40px;padding-inline:16px;">Pilih File XLSX / CSV<input type="file"
                    accept=".xlsx,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv"
                    data-teacher-file hidden></label>
            <p class="operator-student-import-message" data-teacher-import-message role="status" aria-live="polite"></p>
        </section>

        <section class="operator-teacher-stats" aria-label="Ringkasan data guru">
            <article><span>Total Guru &amp; Staff Terdaftar</span><strong>{{ $totalGuru }}</strong></article>
            <article><span>Tugas Tambahan / Wali Kelas</span><strong>{{ $totalWaliKelas }}</strong></article>
        </section>

        <form method="GET" action="{{ route('operator.pegawai.index') }}" class="operator-teacher-filters">
            <label class="operator-teacher-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-4-4" />
                </svg>
                <input type="search" name="search" value="{{ request('search', request('cari')) }}"
                    placeholder="Cari nama guru atau NIP" aria-label="Cari nama guru atau NIP">
            </label>
            <select name="status_kepegawaian_id" aria-label="Pilih status kepegawaian">
                <option value="">Semua Status</option>
                @foreach ($opsiStatus as $statusItem)
                    <option value="{{ $statusItem->id }}" @selected((string) request('status_kepegawaian_id') === (string) $statusItem->id)>
                        {{ $statusItem->label }}
                    </option>
                @endforeach
            </select>
            <button class="operator-teacher-apply" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 6h16M7 12h10m-7 6h4" />
                </svg>
                Terapkan Filter
            </button>
            <a class="operator-teacher-reset" href="{{ route('operator.pegawai.index') }}">Reset</a>
        </form>

        <section class="operator-teacher-table-card" aria-label="Daftar data guru">
            <div class="operator-teacher-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>NIK</th>
                            <th>NUPTK</th>
                            <th>Status Kepegawaian</th>
                            <th>NIP</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Nomor Handphone</th>
                            <th>Email</th>
                            <th>Email Akun Madrasah</th>
                            <th>Tugas</th>
                            <th>Mata Pelajaran</th>
                            <th>Penempatan</th>
                            <th>Total JTM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody data-operator-teachers>
                        @forelse ($daftarPegawai as $index => $pegawai)
                            @php
                                $nomor = $isPaginator ? $daftarPegawai->firstItem() + $index : $index + 1;
                                $jkKode = $pegawai->jenis_kelamin?->value ?? (string) ($pegawai->jenis_kelamin ?? '');
                                $jkLabel =
                                    $jkKode === 'L' ? 'Laki-laki' : ($jkKode === 'P' ? 'Perempuan' : ($jkKode ?: '—'));
                            @endphp
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td><strong>{{ $pegawai->nama_lengkap }}</strong></td>
                                <td>{{ $pegawai->nik ?? '—' }}</td>
                                <td>{{ $pegawai->nuptk ?? '—' }}</td>
                                <td>{{ $pegawai->statusKepegawaian?->label ?? '—' }}</td>
                                <td>{{ $pegawai->nip ?? '—' }}</td>
                                <td>{{ $jkLabel }}</td>
                                <td>{{ $pegawai->tempat_lahir ?? '—' }}</td>
                                <td>{{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d/m/Y') : '—' }}
                                </td>
                                <td>{{ $pegawai->no_hp ?? '—' }}</td>
                                <td>{{ $pegawai->email ?? '—' }}</td>
                                <td>{{ $pegawai->pengguna?->username ?? '—' }}</td>
                                <td>{{ $pegawai->jenisGtk?->label ?? '—' }}</td>
                                <td>{{ $pegawai->mata_pelajaran ?? '—' }}</td>
                                <td>{{ $pegawai->penempatan ?? 'MI Assaidiyyah' }}</td>
                                <td>{{ $pegawai->total_jtm ?? '—' }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('operator.pegawai.edit', $pegawai) }}"
                                            class="text-xs font-semibold text-emerald-700 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('operator.pegawai.destroy', $pegawai) }}"
                                            onsubmit="return confirm('Arsipkan data pegawai ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs font-semibold text-rose-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="operator-teacher-empty">
                                    <strong>Belum Ada Data Guru Dimuat</strong>
                                    <p>Daftar pendidik dan tenaga kependidikan akan ditampilkan di sini.</p>
                                    <a class="operator-primary-button" href="{{ route('operator.pegawai.create') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" aria-hidden="true">
                                            <path d="M12 5v14M5 12h14" />
                                        </svg>
                                        Tambah Guru Baru
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <footer class="operator-teacher-pagination">
                <span>Menampilkan <strong data-teacher-visible>{{ $daftarPegawai->count() }}</strong> dari <strong
                        data-teacher-total>{{ $totalGuru }}</strong> guru</span>
                <nav aria-label="Navigasi halaman guru">
                    @if ($isPaginator && $daftarPegawai->previousPageUrl())
                        <a href="{{ $daftarPegawai->previousPageUrl() }}" aria-label="Halaman sebelumnya">&lsaquo;</a>
                    @else
                        <button type="button" data-teacher-prev aria-label="Halaman sebelumnya" disabled>&lsaquo;</button>
                    @endif

                    <span data-teacher-page>{{ $isPaginator ? $daftarPegawai->currentPage() : 1 }}</span>

                    @if ($isPaginator && $daftarPegawai->nextPageUrl())
                        <a href="{{ $daftarPegawai->nextPageUrl() }}" aria-label="Halaman berikutnya">&rsaquo;</a>
                    @else
                        <button type="button" data-teacher-next aria-label="Halaman berikutnya" disabled>&rsaquo;</button>
                    @endif
                </nav>
            </footer>
        </section>
    </section>
@endsection
