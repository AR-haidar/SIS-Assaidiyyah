<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- 2. Penempatan Rombel --}}
    <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
        <header class="border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900">Penempatan Rombel</h2>
            <p class="text-xs text-slate-500">Satu siswa hanya menempati satu rombel per tahun ajaran</p>
        </header>

        <form method="POST" action="{{ route('operator.siswa.rombel', $siswa) }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <select name="rombel_id" required
                class="flex-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                <option value="">Pilih Rombel Tujuan</option>
                @foreach ($daftarRombel as $rombel)
                    <option value="{{ $rombel->id }}">
                        {{ $rombel->nama }} {{ $rombel->tahunAjaran ? '(' . $rombel->tahunAjaran->kode . ')' : '' }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">
                Tetapkan
            </button>
        </form>

        <div class="divide-y divide-slate-100 border-t border-slate-100 pt-2">
            @forelse ($siswa->keanggotaanRombel as $anggota)
                <div class="flex items-center justify-between gap-2 py-2.5 text-sm">
                    <div>
                        <strong class="font-semibold text-slate-900">{{ $anggota->rombel?->nama ?? '—' }}</strong>
                        <span class="block text-xs text-slate-500">Tahun Ajaran:
                            {{ $anggota->rombel?->tahunAjaran?->kode ?? '—' }}</span>
                    </div>
                </div>
            @empty
                <p class="py-3 text-xs text-slate-500">Belum ada riwayat penempatan rombel.</p>
            @endforelse
        </div>
    </section>

    {{-- 3. Akun Login Siswa (NISN + PIN 6 Digit) --}}
    <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
        <header class="border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900">Akun Login &amp; PIN Siswa</h2>
            <p class="text-xs text-slate-500">Username memakai NISN siswa dan PIN 6 digit angka</p>
        </header>

        @if (session('pin_baru'))
            <div class="rounded-lg bg-sky-50 border border-sky-200 p-3 text-xs text-sky-900">
                PIN aktif saat ini: <strong class="font-mono text-sm">{{ session('pin_baru') }}</strong>
            </div>
        @endif

        @if ($siswa->pengguna)
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3.5 text-sm space-y-1">
                <p class="text-emerald-900">Username Aktif: <strong>{{ $siswa->pengguna->username }}</strong></p>
                <p class="text-xs text-emerald-700">Status Akun: {{ $siswa->pengguna->aktif ? 'Aktif' : 'Nonaktif' }}
                </p>
            </div>

            <form method="POST" action="{{ route('operator.siswa.akun.reset-pin', $siswa) }}"
                class="flex flex-col sm:flex-row gap-3">
                @csrf
                @method('PATCH')
                <input type="text" name="pin_baru" maxlength="6" pattern="[0-9]{6}" required
                    placeholder="PIN baru 6 digit"
                    class="flex-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700 transition">
                    Reset PIN
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('operator.siswa.akun.buat', $siswa) }}"
                class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="pin" maxlength="6" pattern="[0-9]{6}" required
                    placeholder="Buat PIN 6 digit angka"
                    class="flex-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    Buat Akun Siswa
                </button>
            </form>
        @endif
    </section>
</div>

{{-- 4. Berkas Dokumen Siswa --}}
<section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
    <header class="border-b border-slate-100 pb-3">
        <h2 class="text-base font-bold text-slate-900">Arsip Berkas Siswa</h2>
        <p class="text-xs text-slate-500">Perbarui Pasfoto, Kartu Keluarga, atau Akta Kelahiran siswa melalui formulir
            data siswa</p>
    </header>

    <div class="divide-y divide-slate-100">
        @forelse ($siswa->berkas as $itemBerkas)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-2.5 text-sm">
                <div class="min-w-0">
                    <strong class="font-semibold text-slate-900">{{ $itemBerkas->pivot?->jenis ?? 'Dokumen' }}</strong>
                    <span class="block text-xs text-slate-500 truncate">{{ $itemBerkas->nama_asli ?? '—' }}</span>
                </div>
            </div>
        @empty
            <p class="py-2 text-xs text-slate-500">Belum ada berkas siswa yang tersimpan.</p>
        @endforelse
    </div>
</section>
