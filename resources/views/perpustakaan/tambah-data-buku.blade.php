@extends('perpustakaan.layout')
@use(App\Models\Buku)
@use(App\Enums\StatusPublikasiBuku)
@section('title', 'Tambah Data Buku')
@section('content')
<section class="perpustakaan-book-form-page">
    <header class="perpustakaan-book-page-heading">
        <a href="{{ route('pustakawan.data-buku.index') }}" class="perpustakaan-form-back"
            data-confirm-dialog="batalkan-tambah-buku">
            <x-perpustakaan.icon name="back" />
            Kembali ke Data Buku
        </a>
        <h1>Data Buku</h1>
    </header>

    @include('perpustakaan.partials.book-form-errors')

    <form id="form-tambah-buku" class="perpustakaan-book-grid" method="POST" enctype="multipart/form-data"
        action="{{ route('pustakawan.data-buku.store') }}"
        data-confirm-dialog="konfirmasi-simpan-buku" data-inline-validation>
        @csrf
        <section class="perpustakaan-book-card perpustakaan-book-info-card">
            <header class="perpustakaan-book-card-heading">
                <div>
                    <h2>Informasi Pokok Buku</h2>
                    <p>Data bibliografi dasar untuk pengindeksan perpustakaan</p>
                </div>
                <span class="perpustakaan-book-badge">* Wajib Diisi</span>
            </header>
            <div class="perpustakaan-book-fields perpustakaan-book-fields--add">
                <div class="perpustakaan-book-field perpustakaan-book-field--full">
                    <div class="perpustakaan-book-field-caption">
                        <label for="tambah-judul">Judul Buku <b aria-hidden="true">*</b></label>
                        <small id="tambah-judul-counter" class="form-field-counter">0 / {{ Buku::MAKS_JUDUL_KARAKTER }} karakter</small>
                    </div>
                    <input id="tambah-judul" type="text" name="judul" value="{{ old('judul') }}" maxlength="{{ Buku::MAKS_JUDUL_KARAKTER }}"
                        placeholder="Masukkan judul lengkap buku..." required @error('judul') aria-invalid="true" @enderror
                        aria-describedby="tambah-judul-counter tambah-judul-limit tambah-judul-error"
                        data-validation-error="tambah-judul-error" data-required-message="Judul buku wajib diisi."
                        data-character-counter="tambah-judul-counter" data-character-feedback="tambah-judul-limit">
                    <x-form-field-message id="tambah-judul-error" :message="$errors->first('judul')" />
                    <x-form-field-message id="tambah-judul-limit" kind="limit" role="status" />
                </div>
                <div class="perpustakaan-book-field perpustakaan-book-field--full">
                    <label for="tambah-penulis">Penulis <b aria-hidden="true">*</b></label>
                    <span class="perpustakaan-book-control perpustakaan-book-control--icon">
                        <x-perpustakaan.icon name="user" class="perpustakaan-book-control-icon" />
                        <input id="tambah-penulis" type="text" name="penulis" value="{{ old('penulis') }}" maxlength="{{ Buku::MAKS_NAMA_KARAKTER }}"
                            placeholder="Nama penulis atau penyusun naskah..." required @error('penulis') aria-invalid="true" @enderror
                            aria-describedby="tambah-penulis-error" data-validation-error="tambah-penulis-error"
                            data-required-message="Nama penulis wajib diisi.">
                    </span>
                    <x-form-field-message id="tambah-penulis-error" :message="$errors->first('penulis')" />
                </div>
                <div class="perpustakaan-book-field perpustakaan-book-field--full">
                    <label for="tambah-kategori">Kategori <b aria-hidden="true">*</b></label>
                    <span class="perpustakaan-book-control perpustakaan-book-control--icon">
                        <x-perpustakaan.icon name="category" class="perpustakaan-book-control-icon" />
                        <select id="tambah-kategori" name="kategori_buku_id" required aria-describedby="tambah-kategori-error"
                            @error('kategori_buku_id') aria-invalid="true" @enderror
                            data-validation-error="tambah-kategori-error" data-required-message="Pilih kategori buku.">
                            <option value="">Pilih Kategori Buku</option>
                            @foreach ($kategoriBuku as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori_buku_id') == $kategori->id)>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        <x-perpustakaan.icon name="chevron-down" size="14" class="perpustakaan-book-select-chevron" />
                    </span>
                    <x-form-field-message id="tambah-kategori-error" :message="$errors->first('kategori_buku_id')" />
                </div>
                <label class="perpustakaan-book-field">
                    <span>Penerbit <small>(opsional)</small></span>
                    <span class="perpustakaan-book-control perpustakaan-book-control--icon">
                        <x-perpustakaan.icon name="publisher" class="perpustakaan-book-control-icon" />
                        <input type="text" name="penerbit" value="{{ old('penerbit') }}" maxlength="{{ Buku::MAKS_NAMA_KARAKTER }}"
                            placeholder="Nama penerbit resmi..." aria-describedby="tambah-penerbit-error"
                            data-validation-error="tambah-penerbit-error" @error('penerbit') aria-invalid="true" @enderror>
                    </span>
                    <x-form-field-message id="tambah-penerbit-error" :message="$errors->first('penerbit')" />
                </label>
                <div class="perpustakaan-book-field">
                    <label for="tambah-tahun">Tahun Terbit <small>(opsional)</small></label>
                    <span class="perpustakaan-book-control perpustakaan-book-control--icon">
                        <x-perpustakaan.icon name="calendar" class="perpustakaan-book-control-icon" />
                        <input id="tambah-tahun" type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" min="1" max="9999"
                            placeholder="Contoh: 2024" aria-describedby="tambah-tahun-error" @error('tahun_terbit') aria-invalid="true" @enderror
                            data-validation-error="tambah-tahun-error" data-invalid-message="Masukkan tahun terbit dalam angka bulat antara 1 dan 9999.">
                    </span>
                    <x-form-field-message id="tambah-tahun-error" :message="$errors->first('tahun_terbit')" />
                </div>
                <div class="perpustakaan-book-field perpustakaan-book-field--full">
                    <div class="perpustakaan-book-field-caption">
                        <label for="perpustakaanSynopsis">Deskripsi / Sinopsis <b aria-hidden="true">*</b></label>
                        <small id="tambah-sinopsis-counter" class="form-field-counter">0 / {{ number_format(Buku::MAKS_SINOPSIS_KARAKTER, 0, ',', '.') }} karakter</small>
                    </div>
                    <textarea id="perpustakaanSynopsis" name="sinopsis" maxlength="{{ Buku::MAKS_SINOPSIS_KARAKTER }}"
                        aria-describedby="tambah-sinopsis-counter tambah-sinopsis-limit tambah-sinopsis-error"
                        placeholder="Tuliskan ringkasan sinopsis dan pengantar buku di sini..." required
                        @error('sinopsis') aria-invalid="true" @enderror
                        data-validation-error="tambah-sinopsis-error" data-required-message="Deskripsi atau sinopsis wajib diisi."
                        data-character-counter="tambah-sinopsis-counter" data-character-feedback="tambah-sinopsis-limit">{{ old('sinopsis') }}</textarea>
                    <x-form-field-message id="tambah-sinopsis-error" :message="$errors->first('sinopsis')" />
                    <x-form-field-message id="tambah-sinopsis-limit" kind="limit" role="status" />
                </div>
            </div>
        </section>

        <aside class="perpustakaan-book-media">
            <section class="perpustakaan-book-card">
                <header class="perpustakaan-book-card-heading">
                    <h2>Cover Buku <small class="perpustakaan-book-optional">(opsional)</small></h2>
                    <span class="perpustakaan-cover-ratio">Rasio 3:4</span>
                </header>
                <div class="perpustakaan-book-media-body">
                    <label class="perpustakaan-book-upload perpustakaan-cover-upload" data-validation-surface>
                        <span class="perpustakaan-cover-preview" data-cover-preview>
                            <img data-cover-image alt="Pratinjau sampul buku" hidden>
                            <strong data-cover-placeholder>Pratinjau Sampul</strong>
                            <small data-cover-placeholder>Belum ada gambar</small>
                        </span>
                        <small id="tambah-sampul-keterangan">Pratinjau Sampul Buku (3:4) · Format JPG / PNG maks. {{ Buku::MAKS_SAMPUL_MB }} MB</small>
                        <span class="perpustakaan-cover-button action-button">
                            <x-perpustakaan.icon name="upload-cloud" />
                            Pilih Berkas Sampul
                        </span>
                        <small id="tambah-sampul-pilihan" class="perpustakaan-selected-file" hidden></small>
                        <input type="file" name="sampul" accept=".jpg,.jpeg,.png,image/jpeg,image/png" aria-label="Pilih berkas sampul buku"
                            aria-describedby="tambah-sampul-keterangan tambah-sampul-error"
                            @error('sampul') aria-invalid="true" @enderror
                            data-file-max-bytes="{{ Buku::MAKS_SAMPUL_MB * 1024 * 1024 }}"
                            data-file-type-message="Pilih sampul dalam format JPG atau PNG."
                            data-file-size-message="Ukuran sampul maksimal {{ Buku::MAKS_SAMPUL_MB }} MB."
                            data-file-selection="tambah-sampul-pilihan" data-validation-error="tambah-sampul-error">
                    </label>
                    <x-form-field-message id="tambah-sampul-error" class="perpustakaan-book-error" role="alert" :message="$errors->first('sampul')" />
                </div>
            </section>

            <section class="perpustakaan-book-card">
                <header class="perpustakaan-book-card-heading">
                    <div>
                        <h2>File / Konten Buku Digital</h2>
                        <p>Dokumen terbitan elektronik</p>
                    </div>
                </header>
                <div class="perpustakaan-book-media-body">
                    <label class="perpustakaan-book-field">
                        <span>Berkas Naskah Digital (PDF / ePub) <b>*</b></span>
                        <span class="perpustakaan-book-upload perpustakaan-digital-upload" data-validation-surface>
                            <span class="perpustakaan-digital-icon"><x-perpustakaan.icon name="file-up" size="20" /></span>
                            <strong>Unggah Berkas Naskah Buku (Maks. {{ Buku::MAKS_NASKAH_MB }} MB)</strong>
                            <small>Tarik file ke sini atau klik untuk menelusuri folder</small>
                            <span class="perpustakaan-file-types" aria-hidden="true"><span>.PDF</span><span>.EPUB</span></span>
                            <small id="tambah-naskah-pilihan" class="perpustakaan-selected-file" hidden></small>
                            <input type="file" name="naskah" accept=".pdf,.epub,application/pdf,application/epub+zip"
                                aria-label="Unggah naskah digital" aria-describedby="tambah-naskah-error" required
                                @error('naskah') aria-invalid="true" @enderror
                                data-file-max-bytes="{{ Buku::MAKS_NASKAH_MB * 1024 * 1024 }}"
                                data-file-type-message="Pilih naskah dalam format PDF atau ePub."
                                data-file-size-message="Ukuran naskah maksimal {{ Buku::MAKS_NASKAH_MB }} MB."
                                data-file-selection="tambah-naskah-pilihan"
                                data-validation-error="tambah-naskah-error"
                                data-required-message="Unggah berkas naskah digital.">
                        </span>
                    </label>
                    <x-form-field-message id="tambah-naskah-error" class="perpustakaan-book-error" :message="$errors->first('naskah')" />
                    <fieldset class="perpustakaan-publication-status">
                        <legend>Status Publikasi <b aria-hidden="true">*</b></legend>
                        <div class="perpustakaan-publication-options" data-validation-surface>
                            <label>
                                <input type="radio" name="status_publikasi" value="{{ StatusPublikasiBuku::Aktif->value }}" required
                                    @checked(old('status_publikasi') === StatusPublikasiBuku::Aktif->value)
                                    @error('status_publikasi') aria-invalid="true" @enderror
                                    aria-describedby="tambah-publikasi-error" data-validation-error="tambah-publikasi-error"
                                    data-required-message="Pilih status publikasi buku.">
                                <span><strong>Terbitkan Langsung</strong><small>Aktif Siap Baca</small></span>
                            </label>
                            <label>
                                <input type="radio" name="status_publikasi" value="{{ StatusPublikasiBuku::Draft->value }}"
                                    @checked(old('status_publikasi', StatusPublikasiBuku::Draft->value) === StatusPublikasiBuku::Draft->value)>
                                <span><strong>Simpan sebagai Draf</strong><small>Akses Khusus Admin</small></span>
                            </label>
                        </div>
                        <x-form-field-message id="tambah-publikasi-error" class="perpustakaan-book-error" :message="$errors->first('status_publikasi')" />
                    </fieldset>
                </div>
            </section>
        </aside>

        <div class="perpustakaan-book-actions">
            <a href="{{ route('pustakawan.data-buku.index') }}" class="action-button" data-confirm-dialog="batalkan-tambah-buku">Batal</a>
            <button type="submit" class="perpustakaan-book-save action-button action-button--primary">
                <x-perpustakaan.icon name="save" />
                Simpan Buku
            </button>
        </div>
    </form>
</section>
<x-confirmation-dialog
    id="konfirmasi-simpan-buku"
    title="Simpan buku ini?"
    message="Pastikan informasi buku, pilihan status, dan berkas sudah sesuai sebelum melanjutkan."
    confirm-label="Konfirmasi Simpan"
    cancel-label="Periksa Kembali"
/>
<x-confirmation-dialog
    id="batalkan-tambah-buku"
    title="Batalkan penambahan buku?"
    message="Jika Anda keluar, isian dan berkas yang sudah dipilih akan hilang."
    confirm-label="Batalkan Penambahan"
    cancel-label="Lanjut Mengisi"
    tone="danger"
/>
@endsection
