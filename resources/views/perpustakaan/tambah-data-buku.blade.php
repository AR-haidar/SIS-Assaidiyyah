@extends('perpustakaan.layout')
@section('title', 'Tambah Data Buku')
@section('content')
<section class="perpustakaan-add-book-page">
    <a href="{{ url('/perpustakaan/data-buku') }}" class="perpustakaan-form-back"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>Kembali ke Data Buku</a>
    <h1>Data Buku</h1>

    <form class="perpustakaan-add-book-form">
        <div class="perpustakaan-add-book-grid">
            <div class="perpustakaan-book-primary-column">
                <section class="perpustakaan-book-info-card">
                    <header class="perpustakaan-book-form-heading"><div><h2>Informasi Pokok Buku</h2><p>Data bibliografi dasar untuk pengindeksan perpustakaan</p></div><span><b>*</b> Wajib Diisi</span></header>
                    <div class="perpustakaan-book-fields">
                        <label class="perpustakaan-book-field full"><span>Judul Buku <b>*</b><small>Maks. 250 karakter</small></span><input type="text" maxlength="250" placeholder="Masukkan judul lengkap buku..." required></label>
                        <label class="perpustakaan-book-field full"><span>Penulis <b>*</b></span><span class="perpustakaan-book-input-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg><input type="text" placeholder="Nama penulis atau penyusun naskah..." required></span></label>
                        <label class="perpustakaan-book-field full"><span>Kategori <b>*</b></span><span class="perpustakaan-book-input-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13 11 4H4v7l9 9a2 2 0 0 0 3 0l4-4a2 2 0 0 0 0-3z"/><circle cx="7.5" cy="7.5" r=".5"/></svg><select required><option value="">Pilih Kategori Buku</option></select></span></label>
                        <label class="perpustakaan-book-field"><span>Penerbit <small>(opsional)</small></span><span class="perpustakaan-book-input-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/><path d="M9 9h.01M9 13h.01M9 17h.01"/></svg><input type="text" placeholder="Nama penerbit resmi..."></span></label>
                        <label class="perpustakaan-book-field"><span>Tahun Terbit <small>(opsional)</small></span><span class="perpustakaan-book-input-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><input type="number" placeholder="Contoh: 2024"></span></label>
                        <label class="perpustakaan-book-field full"><span>Deskripsi / Sinopsis <b>*</b><small><span id="perpustakaanSynopsisCount">0</span> / 1000 kata</small></span><textarea maxlength="1000" placeholder="Tuliskan ringkasan sinopsis dan pengantar buku di sini..." required></textarea></label>
                    </div>
                </section>
                <div class="perpustakaan-book-form-actions"><a href="{{ url('/perpustakaan/data-buku') }}">Batal</a><button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h9"/><path d="m16 3 5 5-9 9-4 1 1-4z"/></svg>Simpan Buku</button></div>
            </div>

            <div class="perpustakaan-book-secondary-column">
                <section class="perpustakaan-book-cover-card">
                    <header class="perpustakaan-book-side-heading"><h2>Cover Buku</h2><span>Rasio 3:4</span></header>
                    <label class="perpustakaan-cover-upload">
                        <span class="perpustakaan-cover-preview"><strong>Pratinjau Sampul</strong><small>Belum ada gambar</small></span>
                        <small class="perpustakaan-cover-help">Pratinjau Sampul Buku (3:4) — Format JPG / PNG maks. 10MB</small>
                        <span class="perpustakaan-cover-button"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M20 16.5v2A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-2"/></svg>Pilih Berkas Sampul</span>
                        <input type="file" accept="image/jpeg,image/png" aria-label="Pilih berkas sampul buku">
                    </label>
                </section>

                <section class="perpustakaan-book-digital-card">
                    <header class="perpustakaan-book-side-heading"><div><h2>File / Konten Buku Digital</h2><p>Dokumen terbitan elektronik</p></div></header>
                    <label class="perpustakaan-digital-field"><span>Berkas Naskah Digital (PDF) <b>*</b></span><span class="perpustakaan-digital-upload">
                        <span class="perpustakaan-digital-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M12 18v-6M9 15l3-3 3 3"/></svg></span>
                        <strong>Unggah Berkas Naskah Buku (Maks. 50MB)</strong><small>Tarik file ke sini atau klik untuk menelusuri folder</small><span class="perpustakaan-file-types"><i>.PDF</i><i>.EPUB</i></span><input type="file" accept=".pdf,.epub,application/pdf" aria-label="Unggah naskah digital">
                    </span></label>
                    <fieldset class="perpustakaan-publication-status"><legend>Status Publikasi</legend>
                        <label><input type="radio" name="publication_status" value="published"><span><strong>Terbitkan Langsung</strong><small>Aktif Siap Baca</small></span></label>
                        <label><input type="radio" name="publication_status" value="draft" checked><span><strong>Simpan sebagai Draf</strong><small>Akses Khusus Admin</small></span></label>
                    </fieldset>
                </section>
            </div>
        </div>
    </form>
</section>
<script>
const synopsisField=document.querySelector('.perpustakaan-book-field textarea');
const synopsisCount=document.getElementById('perpustakaanSynopsisCount');
synopsisField.addEventListener('input',function(){synopsisCount.textContent=this.value.trim()?this.value.trim().split(/\s+/).length:0;});
</script>
@endsection