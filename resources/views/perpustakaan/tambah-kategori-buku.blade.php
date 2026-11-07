@extends('perpustakaan.layout')
@section('title', 'Tambah Kategori Buku')
@section('content')
<section class="perpustakaan-add-category-page">
    <a href="{{ url('/perpustakaan/kategori-buku') }}" class="perpustakaan-category-back"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>Kembali ke Kategori Buku</a>
    <h1>Tambah Kategori Buku</h1>

    <form class="perpustakaan-category-form">
        <section class="perpustakaan-category-form-card">
            <header class="perpustakaan-category-form-heading"><div><h2>Formulir Kategori Pustaka</h2><p>Isi rincian klasifikasi buku di bawah ini secara lengkap</p></div><span><b>*</b> Bidang Wajib</span></header>
            <div class="perpustakaan-category-fields">
                <label class="perpustakaan-category-field">
                    <span>Nama Kategori <b>*</b><small>Maks. 100 Karakter</small></span>
                    <span class="perpustakaan-category-input-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13 11 4H4v7l9 9a2 2 0 0 0 3 0l4-4a2 2 0 0 0 0-3z"/><circle cx="7.5" cy="7.5" r=".5"/></svg><input type="text" maxlength="100" placeholder="Masukkan nama kategori pustaka (contoh: Akidah Akhlak, Fiqih, Cerita Islami)..." required></span>
                </label>
                <label class="perpustakaan-category-field">
                    <span>Deskripsi Kategori <b>*</b><small>Ringkasan cakupan pustaka</small></span>
                    <textarea placeholder="Tuliskan lingkup dan penjelasan naskah yang termasuk dalam kategori ini..." required></textarea>
                </label>
                <fieldset class="perpustakaan-category-status"><legend>Status Kategori <b>*</b></legend>
                    <label><input type="radio" name="category_status" value="active" checked><span><strong>Aktif</strong><small>Dapat dipilih saat input buku baru &amp; pencarian katalog digital.</small></span></label>
                    <label><input type="radio" name="category_status" value="inactive"><span><strong>Nonaktif</strong><small>Disembunyikan sementara dari penambahan naskah dan filter umum.</small></span></label>
                </fieldset>
                <footer class="perpustakaan-category-form-actions"><a href="{{ url('/perpustakaan/kategori-buku') }}">Batal</a><button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h9"/><path d="m16 3 5 5-9 9-4 1 1-4z"/></svg>Simpan Kategori</button></footer>
            </div>
        </section>
    </form>
</section>
@endsection