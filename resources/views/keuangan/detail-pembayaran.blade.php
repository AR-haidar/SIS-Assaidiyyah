@extends('keuangan.layout')
@section('title', 'Detail Pembayaran')
@section('content')
    @php
        $tagihan = $pembayaran->tagihan;
        $siswa = $tagihan?->siswa;
        $rombelAktif = $siswa?->keanggotaanRombel?->sortByDesc('id')->first()?->rombel;
        $statusValue = $pembayaran->status_verifikasi?->value;
        $berkas = $pembayaran->buktiBerkas;
        $adalahGambar = $berkas && str_starts_with((string) $berkas->mime, 'image/');
    @endphp
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Detail Pembayaran</h1>
                <p class="text-sm text-slate-600">Peninjauan bukti transaksi dan penetapan status pembayaran</p>
            </div>
            <a href="{{ route('keuangan.tagihan.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition w-full sm:w-auto">
                &larr; Kembali ke Daftar
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
                <strong class="font-semibold">Periksa kembali input verifikasi:</strong>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
                    <header class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Informasi Siswa</h2>
                        <p class="text-xs text-slate-500">Data registrasi siswa yang mengajukan pembayaran</p>
                    </header>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">Nama Lengkap Siswa</span>
                            <strong
                                class="font-semibold text-slate-900 break-words">{{ $siswa?->nama_lengkap ?? '—' }}</strong>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">NISN</span>
                            <strong class="font-semibold text-slate-900">{{ $siswa?->nisn ?? '—' }}</strong>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">Kelas</span>
                            <strong class="font-semibold text-slate-900">{{ $rombelAktif?->nama ?? '—' }}</strong>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">Tahun Ajaran</span>
                            <strong class="font-semibold text-slate-900">{{ $tagihan?->tahunAjaran?->kode ?? '—' }}</strong>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
                    <header
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-3">
                        <div class="flex items-start gap-3">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M8 4h11v17H5V7z" />
                                    <path d="M8 4v4H5M9 12h7M9 16h7" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Rincian Pembayaran</h2>
                                <p class="text-xs text-slate-500">Parameter iuran serta nominal rincian tagihan</p>
                            </div>
                        </div>
                        <span
                            class="inline-flex self-start sm:self-auto items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-800">
                            Status: {{ $pembayaran->status_verifikasi?->label() ?? strtoupper((string) $statusValue) }}
                        </span>
                    </header>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">Jenis Tagihan</span>
                            <strong
                                class="font-semibold text-slate-900">{{ $tagihan?->jenisTagihan?->nama ?? '—' }}</strong>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">Periode Tagihan</span>
                            <strong
                                class="font-semibold text-slate-900">{{ $tagihan?->periode_bulan ? $tagihan->periode_bulan->translatedFormat('F Y') : 'Sekali Bayar' }}</strong>
                        </div>
                        <div class="sm:col-span-2 rounded-lg bg-slate-50 p-3.5 border border-slate-100 space-y-1">
                            <span class="block text-xs text-slate-500">Total Nominal Pembayaran</span>
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                                <strong class="text-lg sm:text-xl font-bold text-emerald-700">Rp
                                    {{ number_format((float) $pembayaran->nominal, 0, ',', '.') }}</strong>
                                <small class="text-xs text-slate-500">Nominal Tagihan: Rp
                                    {{ number_format((float) ($tagihan?->nominal ?? 0), 0, ',', '.') }}</small>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">Metode Pembayaran</span>
                            <strong
                                class="font-semibold text-slate-900">{{ $pembayaran->metodePembayaran?->label ?? '—' }}</strong>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500">Sumber Pengajuan</span>
                            <strong
                                class="font-semibold text-slate-900">{{ $pembayaran->sumber?->label() ?? ($pembayaran->sumber?->value ?? '—') }}</strong>
                        </div>
                        <div class="sm:col-span-2 space-y-1">
                            <span class="block text-xs text-slate-500">Tanggal &amp; Waktu Pengajuan</span>
                            <strong
                                class="font-semibold text-slate-900">{{ $pembayaran->tanggal_pengajuan?->format('d M Y H:i') ?? '—' }}</strong>
                        </div>
                        @if ($pembayaran->tanggal_pembayaran)
                            <div class="sm:col-span-2 space-y-1">
                                <span class="block text-xs text-slate-500">Tanggal Pembayaran Dikonfirmasi</span>
                                <strong
                                    class="font-semibold text-slate-900">{{ $pembayaran->tanggal_pembayaran->format('d M Y') }}</strong>
                            </div>
                        @endif
                        @if ($pembayaran->alasan_penolakan)
                            <div class="sm:col-span-2 rounded-lg bg-rose-50 border border-rose-200 p-3 space-y-1">
                                <span class="block text-xs font-medium text-rose-700">Alasan Penolakan</span>
                                <strong
                                    class="block font-semibold text-rose-800 break-words">{{ $pembayaran->alasan_penolakan }}</strong>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
                    <header class="flex items-start justify-between gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Bukti Transfer</h2>
                            <p class="text-xs text-slate-500">Pratinjau berkas digital dari pengirim</p>
                        </div>
                        <span class="shrink-0 rounded bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">
                            {{ $berkas ? strtoupper(pathinfo((string) $berkas->nama_asli, PATHINFO_EXTENSION)) : 'TIDAK ADA' }}
                        </span>
                    </header>

                    @if ($berkas)
                        @if ($adalahGambar)
                            <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-50 p-2 text-center">
                                <img src="{{ route('keuangan.pembayaran.bukti', $pembayaran) }}" alt="Bukti pembayaran"
                                    class="mx-auto max-h-64 w-auto rounded object-contain">
                            </div>
                        @else
                            <div
                                class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-center space-y-1">
                                <strong
                                    class="block text-sm font-semibold text-slate-800 break-all">{{ $berkas->nama_asli }}</strong>
                                <span class="block text-xs text-slate-500">Ukuran berkas:
                                    {{ number_format(($berkas->ukuran_byte ?? 0) / 1024, 1) }} KB</span>
                            </div>
                        @endif
                        <a href="{{ route('keuangan.pembayaran.bukti', $pembayaran) }}" target="_blank"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <path d="m7 10 5 5 5-5M12 15V3" />
                            </svg>
                            <span>Unduh / Lihat Berkas</span>
                        </a>
                    @else
                        <div
                            class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-center space-y-1">
                            <strong class="block text-sm font-semibold text-slate-700">Tidak ada lampiran bukti</strong>
                            <span class="block text-xs text-slate-500">Pembayaran dicatat tanpa unggahan berkas
                                digital</span>
                        </div>
                    @endif
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
                    <header class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Tindakan Verifikasi</h2>
                        <p class="text-xs text-slate-500">Otorisasi transaksi kas keuangan</p>
                    </header>

                    <form method="POST" action="{{ route('keuangan.pembayaran.verifikasi', $pembayaran) }}"
                        class="space-y-3">
                        @csrf
                        <input type="hidden" name="status_verifikasi" value="disetujui">
                        <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-700">
                            <span>Tanggal Pembayaran Sah</span>
                            <input type="date" name="tanggal_pembayaran"
                                value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        </label>
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="9" />
                                <path d="m8 12 3 3 5-6" />
                            </svg>
                            <span>Setujui Pembayaran</span>
                        </button>
                    </form>

                    <div class="border-t border-slate-100 pt-4">
                        <form method="POST" action="{{ route('keuangan.pembayaran.verifikasi', $pembayaran) }}"
                            class="space-y-3">
                            @csrf
                            <input type="hidden" name="status_verifikasi" value="ditolak">
                            <label class="flex flex-col gap-1.5 text-xs font-semibold text-slate-700">
                                <span>Alasan Penolakan / Revisi</span>
                                <textarea name="alasan_penolakan" rows="2" required
                                    placeholder="Tuliskan alasan penolakan atau catatan revisi..."
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal text-slate-900 focus:border-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-600/20">{{ old('alasan_penolakan', $pembayaran->alasan_penolakan) }}</textarea>
                            </label>
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-rose-300 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="m9 9 6 6m0-6-6 6" />
                                </svg>
                                <span>Tolak / Minta Revisi</span>
                            </button>
                        </form>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
