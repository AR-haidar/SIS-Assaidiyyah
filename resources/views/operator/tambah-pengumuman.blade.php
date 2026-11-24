@extends('operator.layout')
@section('title', ($pengumuman->exists ?? false) ? 'Edit Pengumuman' : 'Buat Pengumuman')
@section('content')
    @php
        $isEdit = $pengumuman->exists ?? false;
        $daftarKategori = $daftarKategori ?? collect();
        $daftarRombel = $daftarRombel ?? collect();
        $urlIndex = \Illuminate\Support\Facades\Route::has('operator.pengumuman.index')
            ? route('operator.pengumuman.index')
            : url('/operator/pengumuman');
        $formAction = $isEdit
            ? (\Illuminate\Support\Facades\Route::has('operator.pengumuman.update')
                ? route('operator.pengumuman.update', $pengumuman)
                : url('/operator/pengumuman/' . $pengumuman->id))
            : (\Illuminate\Support\Facades\Route::has('operator.pengumuman.store')
                ? route('operator.pengumuman.store')
                : url('/operator/pengumuman'));
        $statusSaatIni = old('status', $isEdit ? $pengumuman->status?->value ?? (string) $pengumuman->status : 'draft');
        $sasaranTerpilih = old('sasaran', $isEdit ? $pengumuman->sasaran ?? [] : []);
        $sasaranTerpilih = is_array($sasaranTerpilih) ? $sasaranTerpilih : [$sasaranTerpilih];
    @endphp
    <section class="operator-announcement-create-page">
        <header class="operator-announcement-create-heading">
            <h1>{{ $isEdit ? 'Edit Pengumuman & Edaran' : 'Formulir Pembuatan & Publikasi Pengumuman' }}</h1>
            <p>Susun naskah edaran resmi, tetapkan target penerima pengumuman, dan atur waktu penayangan</p>
        </header>

        @if ($errors->any())
            <div role="alert" class="operator-form-alert">
                <strong>Periksa kembali isian formulir:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data"
            class="operator-announcement-create-form">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <div class="operator-announcement-create-grid">
                <section class="operator-announcement-content-card">
                    <header class="operator-announcement-form-card-heading">
                        <h2>Konten Surat &amp; Sasaran Distribusi</h2>
                        <span><b>*</b> Kolom wajib dilengkapi</span>
                    </header>
                    <div class="operator-announcement-content-fields">
                        <label class="operator-announcement-field operator-announcement-field-wide">
                            <span>Judul Pengumuman <b>*</b><small>Maks. 120 Karakter</small></span>
                            <input type="text" name="judul" maxlength="120"
                                value="{{ old('judul', $pengumuman->judul ?? '') }}"
                                placeholder="Masukkan judul pengumuman / surat edaran resmi..." required>
                        </label>
                        <label class="operator-announcement-field">
                            <span>Kategori Pengumuman <b>*</b></span>
                            <select name="kategori_id" required>
                                <option value="">Pilih Kategori (Akademik, Kesiswaan,...)</option>
                                @forelse ($daftarKategori as $kat)
                                    <option value="{{ $kat->id }}" @selected((string) old('kategori_id', $pengumuman->kategori_id ?? '') === (string) $kat->id)>
                                        {{ $kat->label ?? $kat->nama }}
                                    </option>
                                @empty
                                    <option value="Akademik" @selected(old('kategori_id') === 'Akademik')>Akademik</option>
                                    <option value="Kesiswaan" @selected(old('kategori_id') === 'Kesiswaan')>Kesiswaan</option>
                                    <option value="Keuangan" @selected(old('kategori_id') === 'Keuangan')>Keuangan</option>
                                    <option value="Informasi Umum" @selected(old('kategori_id') === 'Informasi Umum')>Informasi Umum</option>
                                @endforelse
                            </select>
                        </label>
                        <label class="operator-announcement-field">
                            <span>Nomor Surat / Edaran Resmi <small>Opsional</small></span>
                            <input type="text" name="nomor_surat"
                                value="{{ old('nomor_surat', $pengumuman->nomor_surat ?? '') }}"
                                placeholder="Contoh: 045/MI-ASD/PP.00/IX/2024">
                        </label>
                        <fieldset class="operator-announcement-recipients">
                            <legend>Target Penerima Pengumuman <b>*</b></legend>
                            <div class="operator-announcement-recipient-options">
                                <label><input type="checkbox" name="sasaran[]" value="semua"
                                        @checked(in_array('semua', $sasaranTerpilih, true))><span><strong>Semua</strong><small>Seluruh civitas
                                            akademika</small></span></label>
                                <label><input type="checkbox" name="sasaran[]" value="guru"
                                        @checked(in_array('guru', $sasaranTerpilih, true))><span><strong>Dewan Guru &amp;
                                            Staff</strong><small>Pendidik &amp; tenaga pendidik</small></span></label>
                                <label><input type="checkbox" name="sasaran[]" value="murid"
                                        @checked(in_array('murid', $sasaranTerpilih, true))><span><strong>Murid &amp; Wali
                                            Murid</strong><small>Siswa kelas 1 s.d. 6</small></span></label>
                                @foreach ($daftarRombel as $rombel)
                                    <label><input type="checkbox" name="sasaran[]" value="rombel:{{ $rombel->id }}"
                                            @checked(in_array('rombel:' . $rombel->id, $sasaranTerpilih, true))><span><strong>{{ $rombel->nama }}</strong><small>Rombongan
                                                belajar</small></span></label>
                                @endforeach
                            </div>
                        </fieldset>
                        <label class="operator-announcement-field operator-announcement-editor">
                            <span>Editor Naskah Pengumuman <b>*</b></span>
                            <textarea name="isi" placeholder="Ketikkan isi lengkap pengumuman resmi di sini..." required>{{ old('isi', $pengumuman->isi ?? '') }}</textarea>
                        </label>
                        <label class="operator-announcement-field operator-announcement-attachment">
                            <span>Unggah Dokumen Lampiran Resmi</span>
                            <span class="operator-announcement-upload-box">
                                <span class="operator-announcement-upload-icon" aria-hidden="true"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M12 16V4" />
                                        <path d="m7 9 5-5 5 5" />
                                        <path d="M20 16.5v2A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-2" />
                                    </svg></span>
                                <strong>Unggah lampiran (JPG, PNG, PDF, DOC, DOCX)</strong>
                                <small data-file-name>Foto atau dokumen, maks. 15MB</small>
                                <input type="file" name="lampiran" accept="image/jpeg,image/png,.pdf,.doc,.docx"
                                    aria-label="Unggah foto atau dokumen lampiran">
                            </span>
                        </label>
                    </div>
                </section>
                <aside class="operator-announcement-publish-card">
                    <header class="operator-announcement-form-card-heading">
                        <h2>Atribut Publikasi</h2>
                    </header>
                    <fieldset class="operator-announcement-status">
                        <legend>Status Naskah</legend>
                        <label><input type="radio" name="status" value="draft"
                                @checked($statusSaatIni === 'draft')><span><strong>Simpan sebagai Draf</strong><small>Hanya dapat
                                    dilihat oleh penyusun</small></span></label>
                        <label><input type="radio" name="status" value="terbit"
                                @checked($statusSaatIni === 'terbit')><span><strong>Langsung Terbitkan</strong><small>Tayang
                                    langsung tanpa persetujuan berjenjang</small></span></label>
                        <label><input type="radio" name="status" value="arsip"
                                @checked($statusSaatIni === 'arsip')><span><strong>Arsipkan</strong><small>Simpan ke arsip
                                    dokumen</small></span></label>
                    </fieldset>
                    <div class="operator-announcement-schedule">
                        <label class="operator-announcement-field">
                            <span>Tanggal Mulai Tayang</span>
                            <input type="date" name="tanggal_terbit"
                                value="{{ old('tanggal_terbit', isset($pengumuman->tanggal_terbit) ? \Carbon\Carbon::parse($pengumuman->tanggal_terbit)->format('Y-m-d') : '') }}"
                                aria-label="Tanggal mulai tayang">
                        </label>
                        <label class="operator-announcement-field">
                            <span>Tanggal Berakhir Tayang</span>
                            <input type="date" name="tayang_selesai"
                                value="{{ old('tayang_selesai', isset($pengumuman->tayang_selesai) ? \Carbon\Carbon::parse($pengumuman->tayang_selesai)->format('Y-m-d') : '') }}"
                                aria-label="Tanggal berakhir tayang">
                        </label>
                    </div>
                    <label class="operator-announcement-pin">
                        <input type="checkbox" name="disematkan" value="1" @checked((bool) old('disematkan', $pengumuman->disematkan ?? false))>
                        <span><strong>Sematkan Pengumuman</strong><small>Prioritas tinggi pada pinboard atas</small></span>
                    </label>
                </aside>
            </div>
            <footer class="operator-announcement-create-actions">
                <a href="{{ $urlIndex }}">Batal</a>
                <button type="submit" name="status" value="draft" class="secondary">Simpan Draf</button>
                <button type="submit" name="status" value="terbit" class="primary">Publikasikan Pengumuman <span
                        aria-hidden="true">&#9655;</span></button>
            </footer>
        </form>
    </section>
@endsection
