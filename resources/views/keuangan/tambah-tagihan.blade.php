@extends('keuangan.layout')
@section('title', 'Input Tagihan')
@section('content')
    @php
        $daftarTahunAjaran = $daftarTahunAjaran ?? collect();
        $daftarJenisTagihan = $daftarJenisTagihan ?? collect();
        $daftarRombel = $daftarRombel ?? collect();
        $daftarSiswa = $daftarSiswa ?? collect();
        $taAktifId = old('tahun_ajaran_id', $daftarTahunAjaran->firstWhere('aktif', true)?->id);
        $periodeAwal = old('periode_bulan', now()->format('Y-m'));
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $periodeAwal)) {
            $periodeAwal = substr((string) $periodeAwal, 0, 7);
        }
    @endphp
    <section class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <header class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Input Tagihan</h1>
            <p class="text-sm text-slate-600">Buat tagihan siswa per individu maupun massal per rombel</p>
        </header>

        @if (session('status'))
            <div role="status"
                class="rounded-lg border-l-4 border-emerald-600 bg-emerald-50 p-4 text-sm text-emerald-900 shadow-sm">
                <strong class="font-semibold">{{ session('status') }}</strong>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="rounded-lg border-l-4 border-rose-600 bg-rose-50 p-4 text-sm text-rose-900 shadow-sm">
                <strong class="font-semibold">Periksa kembali isian formulir:</strong>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('keuangan.tagihan.massal') }}"
            class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                <label class="flex flex-col gap-1.5 text-sm font-medium text-slate-700">
                    <span>Tahun Ajaran</span>
                    <select name="tahun_ajaran_id" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Pilih tahun ajaran</option>
                        @foreach ($daftarTahunAjaran as $ta)
                            <option value="{{ $ta->id }}" @selected((string) $taAktifId === (string) $ta->id)>
                                {{ $ta->kode }} {{ $ta->aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-1.5 text-sm font-medium text-slate-700">
                    <span>Jenis Tagihan</span>
                    <select name="jenis_tagihan_id" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">Pilih jenis tagihan</option>
                        @foreach ($daftarJenisTagihan as $jenis)
                            <option value="{{ $jenis->id }}" @selected((string) old('jenis_tagihan_id') === (string) $jenis->id)>
                                {{ $jenis->nama }} ({{ $jenis->bulanan ? 'Bulanan' : 'Sekali Bayar' }})
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-1.5 text-sm font-medium text-slate-700">
                    <span>Target Rombel (Massal Satu Kelas)</span>
                    <select name="rombel_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">— Pilih rombel jika buat massal —</option>
                        @foreach ($daftarRombel as $rombel)
                            <option value="{{ $rombel->id }}" @selected((string) old('rombel_id') === (string) $rombel->id)>
                                {{ $rombel->nama }} {{ $rombel->tahunAjaran ? '— ' . $rombel->tahunAjaran->kode : '' }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-1.5 text-sm font-medium text-slate-700">
                    <span>Target Siswa Tertentu (Opsional jika Rombel dipilih)</span>
                    <select name="siswa_ids[]"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        <option value="">— Pilih satu siswa —</option>
                        @foreach ($daftarSiswa as $siswa)
                            @php
                                $namaRombel = $siswa->keanggotaanRombel->sortByDesc('id')->first()?->rombel?->nama;
                            @endphp
                            <option value="{{ $siswa->id }}" @selected(in_array((string) $siswa->id, array_map('strval', (array) old('siswa_ids', [])), true))>
                                {{ $siswa->nama_lengkap }}
                                ({{ $siswa->nisn }})
                                {{ $namaRombel ? ' — ' . $namaRombel : '' }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-1.5 text-sm font-medium text-slate-700">
                    <span>Periode Bulan (Wajib untuk tagihan bulanan)</span>
                    <input name="periode_bulan" type="month" value="{{ $periodeAwal }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                </label>

                <label class="flex flex-col gap-1.5 text-sm font-medium text-slate-700">
                    <span>Nominal Tagihan (Rp)</span>
                    <input name="nominal" type="number" min="1" step="1" required placeholder="Contoh: 250000"
                        value="{{ old('nominal') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                </label>

                <label class="flex flex-col gap-1.5 text-sm font-medium text-slate-700 md:col-span-2">
                    <span>Tanggal Jatuh Tempo</span>
                    <input name="jatuh_tempo" type="date" required
                        value="{{ old('jatuh_tempo', now()->addDays(14)->format('Y-m-d')) }}"
                        class="w-full md:max-w-sm rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                </label>
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('keuangan.tagihan.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    Simpan Tagihan
                </button>
            </div>
        </form>
    </section>
@endsection
