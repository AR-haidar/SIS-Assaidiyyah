@extends('operator.layout')
@section('title', 'Data Siswa')
@section('content')
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Direktori &amp; Manajemen Data Siswa</h1>
                <p class="text-sm text-slate-600">Kelola biodata siswa, status verifikasi, penempatan rombel, dan akun login
                    siswa</p>
            </div>
            <a href="{{ route('operator.siswa.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                <span>Tambah Siswa Baru</span>
            </a>
        </header>

        @if (session('status'))
            <div role="status"
                class="rounded-lg border-l-4 border-emerald-600 bg-emerald-50 p-4 text-sm text-emerald-900 shadow-sm">
                <strong class="font-semibold">{{ session('status') }}</strong>
            </div>
        @endif

        <section aria-label="Ringkasan siswa" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="block text-xs font-medium text-slate-500">Total Siswa Aktif</span>
                <strong class="mt-1 block text-xl font-bold text-slate-900">{{ $statistik['total'] ?? 0 }} Siswa</strong>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="block text-xs font-medium text-slate-500">Siswa Laki-laki</span>
                <strong class="mt-1 block text-xl font-bold text-sky-700">{{ $statistik['laki_laki'] ?? 0 }} Siswa</strong>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="block text-xs font-medium text-slate-500">Siswi Perempuan</span>
                <strong class="mt-1 block text-xl font-bold text-emerald-700">{{ $statistik['perempuan'] ?? 0 }}
                    Siswi</strong>
            </article>
        </section>

        <form method="GET" action="{{ route('operator.siswa.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Pencarian Siswa</span>
                    <div class="relative flex items-center">
                        <svg class="pointer-events-none absolute left-3 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                            width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-4-4" />
                        </svg>
                        <input type="search" name="q" value="{{ $filter['q'] ?? '' }}"
                            placeholder="Cari nama, NISN, atau NIK" aria-label="Cari nama siswa atau NISN"
                            class="w-full rounded-lg border border-slate-300 bg-white pl-9 pr-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </div>
                </label>

                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Tingkat / Kelas</span>
                    <select name="kelas" aria-label="Filter Kelas"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Semua Kelas</option>
                        @foreach (range(1, 6) as $kelas)
                            <option value="{{ $kelas }}" @selected((string) ($filter['kelas'] ?? '') === (string) $kelas)>Kelas {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Rombel</span>
                    <select name="rombel" aria-label="Filter Rombel"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Semua Rombel</option>
                        @foreach (['A', 'B', 'C'] as $kodeRombel)
                            <option value="{{ $kodeRombel }}" @selected(($filter['rombel'] ?? '') === $kodeRombel)>Rombel {{ $kodeRombel }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Status Verifikasi</span>
                    <select name="status_data" aria-label="Filter Status Data"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Semua Status</option>
                        @foreach ($opsiStatusData as $nilaiStatus => $labelStatus)
                            <option value="{{ $nilaiStatus }}" @selected(($filter['status_data'] ?? '') === $nilaiStatus)>{{ $labelStatus }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 border-t border-slate-100 pt-3">
                <a href="{{ route('operator.siswa.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Reset
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                    Terapkan Filter
                </button>
            </div>
        </form>

        <section aria-label="Daftar data siswa"
            class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">No</th>
                            <th class="px-4 py-3 whitespace-nowrap">Nama Lengkap</th>
                            <th class="px-4 py-3 whitespace-nowrap">NISN / NIK</th>
                            <th class="px-4 py-3 whitespace-nowrap">Tempat, Tgl Lahir</th>
                            <th class="px-4 py-3 whitespace-nowrap">L/P</th>
                            <th class="px-4 py-3 whitespace-nowrap">Rombel Aktif</th>
                            <th class="px-4 py-3 whitespace-nowrap">Status Data</th>
                            <th class="px-4 py-3 whitespace-nowrap">Akun Siswa</th>
                            <th class="px-4 py-3 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($daftarSiswa as $index => $siswa)
                            @php
                                $anggotaAktif = $siswa->keanggotaanRombel->sortByDesc('id')->first();
                                $rombelAktif = $anggotaAktif?->rombel;
                                $statusValue = $siswa->status_data?->value;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 whitespace-nowrap text-slate-500">
                                    {{ $daftarSiswa->firstItem() + $index }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <strong class="font-semibold text-slate-900">{{ $siswa->nama_lengkap }}</strong>
                                    @if ($siswa->nama_ibu_kandung)
                                        <span class="block text-xs text-slate-500">Ibu:
                                            {{ $siswa->nama_ibu_kandung }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-600">
                                    <span class="block font-medium text-slate-800">NISN: {{ $siswa->nisn ?? '—' }}</span>
                                    <span class="block text-slate-500">NIK: {{ $siswa->nik ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                    {{ $siswa->tempat_lahir ?? '—' }}{{ $siswa->tanggal_lahir ? ', ' . $siswa->tanggal_lahir->format('d/m/Y') : '' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">
                                    {{ $siswa->jenis_kelamin?->value ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($rombelAktif)
                                        <span
                                            class="inline-flex items-center rounded-md bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-800">
                                            {{ $rombelAktif->nama }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum ditempatkan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($statusValue === 'terverifikasi')
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Terverifikasi</span>
                                    @elseif ($statusValue === 'draf')
                                        <span
                                            class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Draf</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">{{ $siswa->status_data?->label() ?? '—' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs">
                                    @if ($siswa->pengguna)
                                        <span class="font-medium text-emerald-700">Aktif
                                            ({{ $siswa->pengguna->username }})</span>
                                    @else
                                        <span class="text-slate-400">Belum dibuat</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('operator.siswa.edit', $siswa) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                            Kelola / Edit
                                        </a>
                                        <form method="POST" action="{{ route('operator.siswa.destroy', $siswa) }}"
                                            onsubmit="return confirm('Arsipkan data siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                                                Arsipkan
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-md space-y-2">
                                        <strong class="block text-base font-bold text-slate-800">Belum Ada Data Siswa
                                            Dimuat</strong>
                                        <p class="text-sm text-slate-500">Gunakan tombol di bawah untuk menambah siswa baru
                                            secara manual atau periksa filter pencarian.</p>
                                        <div class="pt-2">
                                            <a href="{{ route('operator.siswa.create') }}"
                                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    aria-hidden="true">
                                                    <circle cx="9" cy="8" r="3" />
                                                    <path d="M3 20v-1a6 6 0 0 1 12 0v1M19 8v6M16 11h6" />
                                                </svg>
                                                <span>Tambah Siswa Baru</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <footer
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-slate-200 px-4 py-3 text-xs text-slate-600">
                <span>
                    Menampilkan <strong>{{ $daftarSiswa->count() }}</strong> dari
                    <strong>{{ $daftarSiswa->total() }}</strong> siswa
                </span>
                @if ($daftarSiswa->hasPages())
                    <div>{{ $daftarSiswa->links() }}</div>
                @endif
            </footer>
        </section>
    </section>
@endsection
