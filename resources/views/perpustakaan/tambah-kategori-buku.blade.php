@extends('perpustakaan.layout')
@section('title', 'Tambah Kategori Buku')
@section('content')
<section class="perpustakaan-add-category-page">
    <a href="{{ route('pustakawan.kategori-buku.index') }}" class="perpustakaan-category-back"><x-perpustakaan.icon name="back" size="15" />Kembali ke Kategori Buku</a>
    <h1>Tambah Kategori Buku</h1>

    @error('simpan')
        <p role="alert">{{ $message }}</p>
    @enderror

    <form action="{{ route('pustakawan.kategori-buku.store') }}" method="POST" class="perpustakaan-category-form">
        @csrf
        <section class="perpustakaan-category-form-card">
            <header class="perpustakaan-category-form-heading"><div><h2>Formulir Kategori Pustaka</h2><p>Isi rincian klasifikasi buku di bawah ini secara lengkap</p></div><span><b>*</b> Bidang Wajib</span></header>
            <div class="perpustakaan-category-fields">
                <label class="perpustakaan-category-field">
                    <span><span>Nama Kategori <b>*</b></span><small>Maks. 100 Karakter</small></span>
                    <span class="perpustakaan-category-input-icon"><x-perpustakaan.icon name="category" size="16" /><input type="text" name="nama" value="{{ old('nama') }}" maxlength="100" placeholder="Masukkan nama kategori pustaka (contoh: Akidah Akhlak, Fiqih, Cerita Islami)..." required @error('nama') aria-invalid="true" aria-describedby="nama-error" @enderror></span>
                    @error('nama')
                        <small id="nama-error" role="alert">{{ $message }}</small>
                    @enderror
                </label>
                <label class="perpustakaan-category-field">
                    <span><span>Deskripsi Kategori <b>*</b></span><small>Ringkasan cakupan pustaka</small></span>
                    <textarea name="deskripsi" placeholder="Tuliskan lingkup dan penjelasan naskah yang termasuk dalam kategori ini..." required @error('deskripsi') aria-invalid="true" aria-describedby="deskripsi-error" @enderror>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <small id="deskripsi-error" role="alert">{{ $message }}</small>
                    @enderror
                </label>
                <fieldset class="perpustakaan-category-status"><legend>Status Kategori <b>*</b></legend>
                    <label><input type="radio" name="category_status" value="active" @checked(old('category_status', 'active') === 'active') @error('category_status') aria-invalid="true" aria-describedby="category_status-error" @enderror><span><strong>Aktif</strong><small>Dapat dipilih saat input buku baru &amp; pencarian katalog digital.</small></span></label>
                    <label><input type="radio" name="category_status" value="inactive" @checked(old('category_status') === 'inactive') @error('category_status') aria-invalid="true" aria-describedby="category_status-error" @enderror><span><strong>Nonaktif</strong><small>Disembunyikan sementara dari penambahan naskah dan filter umum.</small></span></label>
                    @error('category_status')
                        <small id="category_status-error" role="alert">{{ $message }}</small>
                    @enderror
                </fieldset>
                <footer class="perpustakaan-category-form-actions"><a href="{{ route('pustakawan.kategori-buku.index') }}" class="action-button">Batal</a><button type="submit" class="action-button action-button--primary"><x-perpustakaan.icon name="save" size="14" />Simpan Kategori</button></footer>
            </div>
        </section>
    </form>
</section>
@endsection
