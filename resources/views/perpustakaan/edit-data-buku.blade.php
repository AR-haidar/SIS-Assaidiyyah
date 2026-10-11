@extends('perpustakaan.layout')
@section('title', 'Edit Buku')
@section('content')
<section class="perpustakaan-edit-book-page">
    <header class="perpustakaan-edit-book-heading"><h1>Edit Buku</h1><p>Perbarui data, sampul, atau berkas naskah digital perpustakaan</p></header>
    <form class="perpustakaan-edit-book-grid">
        <div class="perpustakaan-edit-book-primary">
            <section class="perpustakaan-edit-info-card">
                <header class="perpustakaan-edit-card-heading"><h2>Informasi Pokok Buku</h2><span><b>*</b> Wajib diisi</span></header>
                <div class="perpustakaan-edit-book-fields">
                    <label class="perpustakaan-edit-field full"><span>Judul Buku <b>*</b></span><input type="text" placeholder="Masukkan judul lengkap buku..." required></label>
                    <label class="perpustakaan-edit-field"><span>Penulis <b>*</b></span><input type="text" placeholder="Nama penulis" required></label>
                    <label class="perpustakaan-edit-field"><span>Kategori <b>*</b></span><select required><option value="">Pilih kategori buku</option></select></label>
                    <label class="perpustakaan-edit-field"><span>Penerbit <small>Opsional</small></span><span class="perpustakaan-edit-input-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/><path d="M9 9h.01M9 13h.01M9 17h.01"/></svg><input type="text" placeholder="Nama penerbit resmi..."></span></label>
                    <label class="perpustakaan-edit-field"><span>Tahun Terbit <small>Opsional</small></span><span class="perpustakaan-edit-input-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><input type="number" placeholder="Contoh: 2024"></span></label>
                    <label class="perpustakaan-edit-field full"><span>Deskripsi / Sinopsis <b>*</b></span><textarea placeholder="Tuliskan ringkasan sinopsis dan pengantar buku di sini..." required></textarea></label>
                </div>
            </section>
            <div class="perpustakaan-edit-actions"><button type="button" class="delete"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2m-9 0 1 14h8l1-14M10 11v5M14 11v5"/></svg>Hapus Buku</button><a href="{{ url('/perpustakaan/data-buku') }}">Batal</a><button type="button" class="save"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h9"/><path d="m16 3 5 5-9 9-4 1 1-4z"/></svg>Simpan Perubahan Buku</button></div>
        </div>
        <aside class="perpustakaan-edit-book-side">
            <section class="perpustakaan-edit-cover-card">
                <header class="perpustakaan-edit-card-heading"><h2>Cover Buku</h2><span>Rasio 3:4</span></header>
                <label class="perpustakaan-edit-cover-upload"><span class="perpustakaan-edit-cover-preview">Pratinjau Sampul Buku (3:4)</span><span class="perpustakaan-edit-cover-button"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M20 16.5v2A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-2"/></svg>Ganti Berkas Sampul</span><small>Format disarankan: JPG, PNG, atau WEBP. Maksimum 2 MB.</small><input type="file" accept="image/jpeg,image/png,image/webp" aria-label="Ganti berkas sampul"></label>
            </section>
            <section class="perpustakaan-edit-digital-card">
                <header class="perpustakaan-edit-card-heading"><div><h2>File / Konten Buku Digital</h2></div><span><b>*</b> Wajib</span></header>
                <label class="perpustakaan-edit-digital-field"><span>Berkas Naskah Digital (PDF / ePub) <b>*</b></span><span class="perpustakaan-edit-digital-upload"><strong>Unggah Berkas Baru untuk Mengganti (Maks. 50MB)</strong><small>Format didukung: Dokumen PDF / ePUB</small><span>Pilih Berkas dari Perangkat</span><input type="file" accept=".pdf,.epub,application/pdf" aria-label="Pilih berkas naskah baru"></span></label>
                <fieldset class="perpustakaan-edit-publication"><legend>Status Publikasi Naskah</legend><label><input type="radio" name="edit_publication_status" value="published"><span><strong>Terbitkan Langsung</strong><small>Aktif Siap Baca</small></span></label><label><input type="radio" name="edit_publication_status" value="draft"><span><strong>Simpan Draf</strong><small>Hanya Administrator</small></span></label></fieldset>
            </section>
        </aside>
    </form>
</section>
@endsection