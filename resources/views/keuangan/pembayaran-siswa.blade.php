@extends('keuangan.layout')
@section('title', 'Pembayaran Siswa')
@section('content')
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Rekapitulasi Pembayaran Siswa</h1>
                <p class="text-sm text-slate-600">Daftar seluruh tagihan dan status pembayaran siswa</p>
            </div>
            <a href="{{ route('keuangan.tagihan.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition w-full sm:w-auto">
                + Buat Tagihan
            </a>
        </header>

        @if (session('status'))
            <div role="status"
                class="rounded-lg border-l-4 border-emerald-600 bg-emerald-50 p-4 text-sm text-emerald-900 shadow-sm">
                <strong class="font-semibold">{{ session('status') }}</strong>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert"
                class="rounded-lg border-l-4 border-rose-600 bg-rose-50 p-4 text-sm text-rose-900 shadow-sm">
                <strong class="font-semibold">Gagal memproses tindakan:</strong>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section aria-label="Ringkasan pembayaran" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <article class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M8 4h11v17H5V7z" />
                        <path d="M8 4v4H5M9 12h7M9 16h7M9 8h4" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">Semua Pengajuan</p>
                    <p class="text-lg font-bold text-slate-900">{{ $statistik['semua_pengajuan'] ?? 0 }}</p>
                </div>
            </article>
            <article class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path
                            d="M5 2h14M5 22h14M7 2v4.5a2 2 0 0 0 .59 1.41L12 12l-4.41 4.09A2 2 0 0 0 7 17.5V22M17 2v4.5a2 2 0 0 1-.59 1.41L12 12l4.41 4.09A2 2 0 0 1 17 17.5V22" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">Menunggu Verifikasi</p>
                    <p class="text-lg font-bold text-amber-700">{{ $statistik['menunggu'] ?? 0 }}</p>
                </div>
            </article>
            <article class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <span
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path
                            d="m12 3 2 1.5 2.5-.2 1 2.3 2 1.5-.8 2.4.8 2.5-2 1.5-1 2.3-2.5-.2L12 19l-2-1.5-2.5.2-1-2.3-2-1.5.8-2.5-.8-2.4 2-1.5 1-2.3 2.5.2z" />
                        <path d="m9 11 2 2 4-4" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">Disetujui / Lunas</p>
                    <p class="text-lg font-bold text-emerald-700">{{ $statistik['disetujui'] ?? 0 }}</p>
                </div>
            </article>
        </section>

        <form method="GET" action="{{ route('keuangan.tagihan.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Pencarian Santri / Tagihan</span>
                    <div class="relative flex items-center">
                        <svg class="pointer-events-none absolute left-3 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                            width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-4-4" />
                        </svg>
                        <input type="search" name="q" value="{{ $filter['q'] ?? '' }}"
                            placeholder="Cari nama atau NISN siswa..." aria-label="Cari nama siswa"
                            class="w-full rounded-lg border border-slate-300 bg-white pl-9 pr-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </div>
                </label>
                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Jenis Tagihan</span>
                    <select name="jenis_tagihan_id" aria-label="Pilih jenis tagihan"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Semua Jenis Tagihan</option>
                        @foreach ($daftarJenisTagihan as $jenis)
                            <option value="{{ $jenis->id }}" @selected(($filter['jenis_tagihan_id'] ?? null) === $jenis->id)>
                                {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Kelas/Rombel</span>
                    <select name="rombel_id" aria-label="Pilih kelas dan rombel"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Semua Kelas</option>
                        @foreach ($daftarRombel as $rombel)
                            <option value="{{ $rombel->id }}" @selected(($filter['rombel_id'] ?? null) === $rombel->id)>
                                {{ $rombel->nama }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-600">
                    <span>Status Pembayaran</span>
                    <select name="status" aria-label="Pilih status pembayaran"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar" @selected(($filter['status'] ?? '') === 'belum_bayar')>Belum Dibayar</option>
                        <option value="menunggu" @selected(($filter['status'] ?? '') === 'menunggu')>Menunggu Verifikasi</option>
                        <option value="disetujui" @selected(($filter['status'] ?? '') === 'disetujui')>Disetujui / Lunas</option>
                        <option value="ditolak" @selected(($filter['status'] ?? '') === 'ditolak')>Ditolak</option>
                    </select>
                </label>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 pt-2 border-t border-slate-100">
                <a href="{{ route('keuangan.tagihan.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    <span>Reset Filter</span>
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 4h16l-6 7v6l-4 3v-9z" />
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>

        <section aria-label="Daftar pembayaran siswa"
            class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">No</th>
                            <th class="px-4 py-3 whitespace-nowrap">Nama Siswa</th>
                            <th class="px-4 py-3 whitespace-nowrap">NISN</th>
                            <th class="px-4 py-3 whitespace-nowrap">Kelas/Rombel</th>
                            <th class="px-4 py-3 whitespace-nowrap">Jenis Tagihan</th>
                            <th class="px-4 py-3 whitespace-nowrap">Nominal</th>
                            <th class="px-4 py-3 whitespace-nowrap">Jatuh Tempo</th>
                            <th class="px-4 py-3 whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($daftarTagihan as $index => $tagihan)
                            @php
                                $siswa = $tagihan->siswa;
                                $rombelAktif = $siswa?->keanggotaanRombel?->sortByDesc('id')->first()?->rombel;
                                $pembayaranAktif =
                                    $tagihan->pembayaran instanceof \Illuminate\Support\Collection
                                        ? $tagihan->pembayaran->sortByDesc('id')->first()
                                        : $tagihan->pembayaran;
                                $statusVerifikasi = $pembayaranAktif?->status_verifikasi?->value;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 whitespace-nowrap text-slate-500">
                                    {{ $daftarTagihan->firstItem() + $index }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-900">
                                    {{ $siswa?->nama_lengkap ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600">{{ $siswa?->nisn ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600">{{ $rombelAktif?->nama ?? '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">
                                    <span class="font-medium">{{ $tagihan->jenisTagihan?->nama ?? '—' }}</span>
                                    @if ($tagihan->periode_bulan)
                                        <span
                                            class="block text-xs text-slate-500">{{ $tagihan->periode_bulan->translatedFormat('F Y') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900">Rp
                                    {{ number_format((float) $tagihan->nominal, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                    {{ $tagihan->jatuh_tempo?->format('d/m/Y') ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($statusVerifikasi === 'disetujui')
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Lunas
                                            / Disetujui</span>
                                    @elseif ($statusVerifikasi === 'menunggu')
                                        <span
                                            class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Menunggu
                                            Verifikasi</span>
                                    @elseif ($statusVerifikasi === 'ditolak')
                                        <span
                                            class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">Ditolak</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">Belum
                                            Dibayar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($pembayaranAktif && $statusVerifikasi !== 'ditolak')
                                        <a href="{{ route('keuangan.pembayaran.show', $pembayaranAktif) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                            Detail
                                        </a>
                                    @else
                                        <form method="POST"
                                            action="{{ route('keuangan.tagihan.pembayaran.store', $tagihan) }}"
                                            class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 min-w-44">
                                            @csrf
                                            <input type="hidden" name="nominal"
                                                value="{{ (float) $tagihan->nominal }}">
                                            <input type="hidden" name="langsung_setujui" value="1">
                                            <select name="metode_pembayaran_id" required aria-label="Metode pembayaran"
                                                class="w-full sm:w-auto rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-800 focus:border-emerald-600 focus:outline-none">
                                                @foreach ($opsiMetodePembayaran as $metode)
                                                    <option value="{{ $metode->id }}">{{ $metode->label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                                                Lunasi
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-sm text-slate-500">
                                    <strong class="font-semibold text-slate-700">Belum ada data tagihan yang sesuai
                                        filter.</strong>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($daftarTagihan->hasPages())
                <div class="border-t border-slate-200 px-4 py-3">
                    {{ $daftarTagihan->links() }}
                </div>
            @endif
        </section>
    </section>
@endsection
