@extends('perpustakaan.layout')
@use(App\Models\Buku)
@use(App\Enums\StatusPublikasiBuku)
@section('title', 'Edit Buku')
@section('content')
<section class="perpustakaan-book-form-page perpustakaan-book-form-page--edit">
    <header class="perpustakaan-book-page-heading">
        <h1>Edit Buku</h1>
        <p>Perbarui data, sampul, atau berkas naskah digital perpustakaan</p>
    </header>

    @include('perpustakaan.partials.book-form-errors')

    <form class="perpustakaan-book-grid" action="{{ route('pustakawan.data-buku.update', $buku) }}" method="POST"
        enctype="multipart/form-data" data-confirm-dialog="konfirmasi-edit-buku" data-inline-validation>
        @csrf
        @method('PUT')
        <section class="perpustakaan-book-card perpustakaan-book-info-card">
            <header class="perpustakaan-book-card-heading">
                <h2>Informasi Pokok Buku</h2>
                <span class="perpustakaan-book-badge">* Wajib diisi</span>
            </header>
            <div class="perpustakaan-book-fields">
                <div class="perpustakaan-book-field perpustakaan-book-field--full">
                    <div class="perpustakaan-book-field-caption">
                        <label for="edit-judul">Judul Buku <b aria-hidden="true">*</b></label>
                        <small id="edit-judul-counter" class="form-field-counter">Maks. {{ Buku::MAKS_JUDUL_KARAKTER }} karakter</small>
                    </div>
                    <input id="edit-judul" type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                        maxlength="{{ Buku::MAKS_JUDUL_KARAKTER }}" placeholder="Masukkan judul lengkap buku..." required
                        @error('judul') aria-invalid="true" @enderror
                        aria-describedby="edit-judul-counter edit-judul-limit edit-judul-error"
                        data-validation-error="edit-judul-error" data-required-message="Judul buku wajib diisi."
                        data-character-counter="edit-judul-counter" data-character-feedback="edit-judul-limit">
                    <x-form-field-message id="edit-judul-error" :message="$errors->first('judul')" />
                    <x-form-field-message id="edit-judul-limit" kind="limit" role="status" />
                </div>
                <div class="perpustakaan-book-field">
                    <label for="edit-penulis">Penulis <b aria-hidden="true">*</b></label>
                    <input id="edit-penulis" type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}"
                        maxlength="{{ Buku::MAKS_NAMA_KARAKTER }}" placeholder="Nama penulis" required
                        @error('penulis') aria-invalid="true" @enderror
                        aria-describedby="edit-penulis-error" data-validation-error="edit-penulis-error"
                        data-required-message="Nama penulis wajib diisi.">
                    <x-form-field-message id="edit-penulis-error" :message="$errors->first('penulis')" />
                </div>
                <div class="perpustakaan-book-field">
                    <label for="edit-kategori">Kategori <b aria-hidden="true">*</b></label>
                    <span class="perpustakaan-book-control">
                        <select id="edit-kategori" name="kategori_buku_id" required aria-describedby="edit-kategori-error"
                            @error('kategori_buku_id') aria-invalid="true" @enderror
                            data-validation-error="edit-kategori-error" data-required-message="Pilih kategori buku.">
                            <option value="">Pilih kategori buku</option>
                            @foreach ($kategoriBuku as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori_buku_id', $kategoriSaatIni) == $kategori->id)>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        <x-perpustakaan.icon name="chevron-down" size="14" class="perpustakaan-book-select-chevron" />
                    </span>
                    <x-form-field-message id="edit-kategori-error" :message="$errors->first('kategori_buku_id')" />
                </div>
                <div class="perpustakaan-book-field">
                    <label for="edit-penerbit" class="perpustakaan-book-field-caption"><span>Penerbit</span><small>Opsional</small></label>
                    <span class="perpustakaan-book-control perpustakaan-book-control--icon">
                        <x-perpustakaan.icon name="publisher" class="perpustakaan-book-control-icon" />
                        <input id="edit-penerbit" type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                            maxlength="{{ Buku::MAKS_NAMA_KARAKTER }}" placeholder="Nama penerbit resmi..."
                            @error('penerbit') aria-invalid="true" @enderror
                            aria-describedby="edit-penerbit-error" data-validation-error="edit-penerbit-error">
                    </span>
                    <x-form-field-message id="edit-penerbit-error" :message="$errors->first('penerbit')" />
                </div>
                <div class="perpustakaan-book-field">
                    <label for="edit-tahun" class="perpustakaan-book-field-caption"><span>Tahun Terbit</span><small>Opsional</small></label>
                    <span class="perpustakaan-book-control perpustakaan-book-control--icon">
                        <x-perpustakaan.icon name="calendar" class="perpustakaan-book-control-icon" />
                        <input id="edit-tahun" type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                            min="1" max="9999" placeholder="Contoh: 2024" @error('tahun_terbit') aria-invalid="true" @enderror
                            aria-describedby="edit-tahun-error" data-validation-error="edit-tahun-error"
                            data-invalid-message="Masukkan tahun terbit dalam angka bulat antara 1 dan 9999.">
                    </span>
                    <x-form-field-message id="edit-tahun-error" :message="$errors->first('tahun_terbit')" />
                </div>
                <div class="perpustakaan-book-field perpustakaan-book-field--full">
                    <div class="perpustakaan-book-field-caption">
                        <label for="edit-sinopsis">Deskripsi / Sinopsis <b aria-hidden="true">*</b></label>
                        <small id="edit-sinopsis-counter" class="form-field-counter">Maks. {{ number_format(Buku::MAKS_SINOPSIS_KARAKTER, 0, ',', '.') }} karakter</small>
                    </div>
                    <textarea id="edit-sinopsis" name="sinopsis" maxlength="{{ Buku::MAKS_SINOPSIS_KARAKTER }}"
                        placeholder="Tuliskan ringkasan sinopsis dan pengantar buku di sini..." required
                        @error('sinopsis') aria-invalid="true" @enderror
                        aria-describedby="edit-sinopsis-counter edit-sinopsis-limit edit-sinopsis-error"
                        data-validation-error="edit-sinopsis-error" data-required-message="Deskripsi atau sinopsis wajib diisi."
                        data-character-counter="edit-sinopsis-counter" data-character-feedback="edit-sinopsis-limit">{{ old('sinopsis', $buku->sinopsis) }}</textarea>
                    <x-form-field-message id="edit-sinopsis-error" :message="$errors->first('sinopsis')" />
                    <x-form-field-message id="edit-sinopsis-limit" kind="limit" role="status" />
                </div>
            </div>
        </section>

        <aside class="perpustakaan-book-media">
            <section class="perpustakaan-book-card">
                <header class="perpustakaan-book-card-heading">
                    <h2>Cover Buku <small class="perpustakaan-book-optional">(opsional)</small></h2>
                    <span class="perpustakaan-book-badge">Rasio 3:4</span>
                </header>
                <div class="perpustakaan-book-media-body">
                    <label class="perpustakaan-book-upload perpustakaan-cover-upload" data-validation-surface>
                        <span class="perpustakaan-cover-preview" data-cover-preview>
                            <img data-cover-image alt="Sampul {{ $buku->judul }}"
                                @if ($buku->sampul) src="{{ route('pustakawan.data-buku.sampul', $buku) }}" @else hidden @endif>
                            <span data-cover-placeholder @if ($buku->sampul) hidden @endif>Pratinjau Sampul Buku (3:4)</span>
                        </span>
                        <span class="perpustakaan-cover-button action-button">
                            <x-perpustakaan.icon name="image-plus" size="14" />
                            Ganti Berkas Sampul
                        </span>
                        <small id="edit-sampul-keterangan">Format JPG, PNG, atau WEBP. Maksimum {{ Buku::MAKS_SAMPUL_MB }} MB.</small>
                        <small id="edit-sampul-pilihan" class="perpustakaan-selected-file" hidden></small>
                        <input type="file" name="sampul" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            aria-label="Ganti berkas sampul" aria-describedby="edit-sampul-keterangan edit-sampul-error"
                            @error('sampul') aria-invalid="true" @enderror
                            data-file-max-bytes="{{ Buku::MAKS_SAMPUL_MB * 1024 * 1024 }}"
                            data-file-type-message="Pilih sampul dalam format JPG, PNG, atau WEBP."
                            data-file-size-message="Ukuran sampul maksimal {{ Buku::MAKS_SAMPUL_MB }} MB."
                            data-file-selection="edit-sampul-pilihan" data-validation-error="edit-sampul-error">
                    </label>
                    <x-form-field-message id="edit-sampul-error" class="perpustakaan-book-error" :message="$errors->first('sampul')" />
                </div>
            </section>

            <section class="perpustakaan-book-card">
                <header class="perpustakaan-book-card-heading">
                    <h2>File / Konten Buku Digital</h2>
                    <span class="perpustakaan-book-badge">* Wajib</span>
                </header>
                <div class="perpustakaan-book-media-body">
                    <label class="perpustakaan-book-field">
                        <span>
                            Berkas Naskah Digital (PDF / ePub)
                            @if ($buku->naskah)
                                <small>(penggantian opsional)</small>
                            @else
                                <b aria-hidden="true">*</b>
                            @endif
                        </span>
                        @if ($buku->naskah)
                            <small class="perpustakaan-selected-file">Berkas saat ini: {{ $buku->naskah->nama_asli }}</small>
                        @endif
                        <span class="perpustakaan-book-upload perpustakaan-digital-upload" data-validation-surface>
                            <strong>Unggah Berkas Baru untuk Mengganti (Maks. {{ Buku::MAKS_NASKAH_MB }} MB)</strong>
                            <small>Format didukung: Dokumen PDF / ePub</small>
                            <span class="perpustakaan-file-picker action-button">Pilih Berkas dari Perangkat</span>
                            <small id="edit-naskah-pilihan" class="perpustakaan-selected-file" hidden></small>
                            <input type="file" name="naskah" accept=".pdf,.epub,application/pdf,application/epub+zip"
                                aria-label="Pilih berkas naskah baru" aria-describedby="edit-naskah-error"
                                @required(!$buku->naskah) @error('naskah') aria-invalid="true" @enderror
                                data-file-max-bytes="{{ Buku::MAKS_NASKAH_MB * 1024 * 1024 }}"
                                data-file-type-message="Pilih naskah dalam format PDF atau ePub."
                                data-file-size-message="Ukuran naskah maksimal {{ Buku::MAKS_NASKAH_MB }} MB."
                                data-file-selection="edit-naskah-pilihan"
                                data-validation-error="edit-naskah-error" data-required-message="Unggah berkas naskah digital.">
                        </span>
                    </label>
                    <x-form-field-message id="edit-naskah-error" class="perpustakaan-book-error" :message="$errors->first('naskah')" />
                    <fieldset class="perpustakaan-publication-status">
                        <legend>Status Publikasi Naskah <b aria-hidden="true">*</b></legend>
                        <div class="perpustakaan-publication-options" data-validation-surface>
                            <label>
                                <input type="radio" name="status_publikasi" value="{{ StatusPublikasiBuku::Aktif->value }}" required
                                    @checked(old('status_publikasi', $buku->status_publikasi->value) === StatusPublikasiBuku::Aktif->value)
                                    @error('status_publikasi') aria-invalid="true" @enderror
                                    aria-describedby="edit-publikasi-error" data-validation-error="edit-publikasi-error"
                                    data-required-message="Pilih status publikasi buku.">
                                <span><strong>Terbitkan Langsung</strong><small>Aktif Siap Baca</small></span>
                            </label>
                            <label>
                                <input type="radio" name="status_publikasi" value="{{ StatusPublikasiBuku::Draft->value }}"
                                    @checked(old('status_publikasi', $buku->status_publikasi->value) === StatusPublikasiBuku::Draft->value)>
                                <span><strong>Simpan Draf</strong><small>Hanya Administrator</small></span>
                            </label>
                        </div>
                        <x-form-field-message id="edit-publikasi-error" class="perpustakaan-book-error" :message="$errors->first('status_publikasi')" />
                    </fieldset>
                </div>
            </section>
        </aside>

        <div class="perpustakaan-book-actions">
            <button type="submit" form="hapus-buku" class="perpustakaan-book-delete action-button action-button--danger">
                <x-perpustakaan.icon name="trash" />
                Hapus Buku
            </button>
            <a href="{{ route('pustakawan.data-buku.index') }}" class="action-button" data-confirm-dialog="batalkan-edit-buku">Batal</a>
            <button type="submit" class="perpustakaan-book-save action-button action-button--primary">
                <x-perpustakaan.icon name="save" />
                Simpan Perubahan Buku
            </button>
        </div>
    </form>
    <form id="hapus-buku" action="{{ route('pustakawan.data-buku.destroy', $buku) }}" method="POST"
        data-confirm-dialog="konfirmasi-hapus-buku">
        @csrf
        @method('DELETE')
    </form>
</section>
<x-confirmation-dialog
    id="konfirmasi-edit-buku"
    title="Simpan perubahan buku?"
    message="Periksa kembali informasi buku dan pilihan berkas sebelum menyimpan perubahan."
    confirm-label="Simpan Perubahan"
    cancel-label="Periksa Kembali"
/>
<x-confirmation-dialog
    id="batalkan-edit-buku"
    title="Batalkan perubahan buku?"
    message="Jika Anda keluar, perubahan pada formulir akan hilang."
    confirm-label="Batalkan Perubahan"
    cancel-label="Lanjut Mengisi"
    tone="danger"
/>
<x-confirmation-dialog
    id="konfirmasi-hapus-buku"
    title="Hapus buku ini?"
    message="Buku akan dikeluarkan dari daftar koleksi perpustakaan."
    confirm-label="Hapus Buku"
    cancel-label="Kembali"
    tone="danger"
/>
@endsection
