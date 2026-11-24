@extends('operator.layout')
@section('title', 'Tambah Siswa')
@section('content')
    <section class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <div class="space-y-2">
            <a href="{{ route('operator.siswa.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>
                <span>Kembali ke Data Siswa</span>
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Formulir Tambah Data Siswa</h1>
            <p class="text-sm text-slate-600">Anda dapat menyimpan sebagai <strong>Draf</strong> terlebih dahulu atau langsung menandai <strong>Terverifikasi</strong> bila seluruh data wajib lengkap.</p>
        </div>

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

        <form method="POST" action="{{ route('operator.siswa.store') }}" class="space-y-6">
            @csrf

            {{-- Langkah 1: Identitas Akademik --}}
            <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
                <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Identitas Akademik &amp; Registrasi</h2>
                        <p class="text-xs text-slate-500">Nomor induk nasional, tahun masuk, dan status kelengkapan data</p>
                    </div>
                    <span class="self-start sm:self-auto rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Bagian 1/3</span>
                </header>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>NISN (10 digit) <span class="text-rose-600">*</span></span>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="10" placeholder="10 digit nomor NISN" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Tahun Masuk / Angkatan</span>
                        <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', now()->year) }}" min="2000" max="2100" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Nomor KIP / PIP (Opsional)</span>
                        <input type="text" name="nomor_kip" value="{{ old('nomor_kip') }}" placeholder="Nomor KIP jika ada" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>
                </div>
            </section>

            {{-- Langkah 2: Biodata Diri Siswa --}}
            <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
                <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Biodata Diri Siswa</h2>
                        <p class="text-xs text-slate-500">Data kependudukan sipil berdasarkan Kartu Keluarga (KK) &amp; Akta Kelahiran</p>
                    </div>
                    <span class="self-start sm:self-auto rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Bagian 2/3</span>
                </header>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Nama Lengkap Sesuai Akta <span class="text-rose-600">*</span></span>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Masukkan nama lengkap siswa..." class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>NIK Siswa (16 digit) <span class="text-rose-600">*</span></span>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit NIK sesuai KK" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Tempat Lahir <span class="text-rose-600">*</span></span>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota / Kabupaten" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Tanggal Lahir <span class="text-rose-600">*</span></span>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Jenis Kelamin <span class="text-rose-600">*</span></span>
                        <select name="jenis_kelamin" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                            <option value="">Pilih Jenis Kelamin</option>
                            @foreach ($opsiJenisKelamin as $kodeJk => $labelJk)
                                <option value="{{ $kodeJk }}" @selected(old('jenis_kelamin') === $kodeJk)>{{ $labelJk }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Agama</span>
                        <select name="agama_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                            <option value="">Pilih Agama</option>
                            @foreach ($opsiAgama as $agama)
                                <option value="{{ $agama->id }}" @selected((string) old('agama_id') === (string) $agama->id)>{{ $agama->label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Kebutuhan Khusus</span>
                        <select name="kebutuhan_khusus_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                            <option value="">Tidak Ada / Pilih</option>
                            @foreach ($opsiKebutuhanKhusus as $item)
                                <option value="{{ $item->id }}" @selected((string) old('kebutuhan_khusus_id') === (string) $item->id)>{{ $item->label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Disabilitas</span>
                        <select name="disabilitas_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                            <option value="">Tidak Ada / Pilih</option>
                            @foreach ($opsiDisabilitas as $item)
                                <option value="{{ $item->id }}" @selected((string) old('disabilitas_id') === (string) $item->id)>{{ $item->label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700 sm:col-span-2">
                        <span>Alamat Tempat Tinggal Lengkap</span>
                        <textarea name="alamat" rows="2" placeholder="Nama jalan, RT/RW, Dusun, Desa/Kelurahan, Kecamatan" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">{{ old('alamat') }}</textarea>
                    </label>
                </div>
            </section>

            {{-- Langkah 3: Data Orang Tua / Wali --}}
            <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm space-y-4">
                <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Data Orang Tua / Wali Murid</h2>
                        <p class="text-xs text-slate-500">Data penanggung jawab siswa untuk pelaporan dan kontak resmi</p>
                    </div>
                    <span class="self-start sm:self-auto rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Bagian 3/3</span>
                </header>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Nama Lengkap Ayah Kandung</span>
                        <input type="text" name="nama_ayah_kandung" value="{{ old('nama_ayah_kandung') }}" placeholder="Nama ayah..." class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Nama Lengkap Ibu Kandung <span class="text-rose-600">*</span></span>
                        <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung') }}" placeholder="Nama ibu..." class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Nama Wali (Opsional)</span>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali') }}" placeholder="Nama wali jika ada" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>

                    <label class="flex flex-col gap-1.5 font-medium text-slate-700">
                        <span>Nomor WhatsApp / HP Orang Tua</span>
                        <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Contoh: 081234567890" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </label>
                </div>
            </section>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
                <a href="{{ route('operator.siswa.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" name="status_data" value="draf" class="inline-flex items-center justify-center rounded-lg border border-amber-300 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-800 hover:bg-amber-100 transition">
                    Simpan sebagai Draf
                </button>
                <button type="submit" name="status_data" value="terverifikasi" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h9"/><path d="m16 3 5 5-9 9-4 1 1-4z"/></svg>
                    <span>Simpan &amp; Verifikasi Data Siswa</span>
                </button>
            </div>
        </form>
    </section>
@endsection
