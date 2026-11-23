@extends('humas.layout')
@section('title', 'Kelola Konten Beranda')
@section('content')
<section class="humas-home-editor">
    <header class="humas-home-heading">
        <div>
            <h1>Kelola Konten Beranda</h1>
            <p>Kelola teks utama, banner sambutan, pengumuman highlight, dan tombol aksi pada halaman depan madrasah.</p>
        </div>
    </header>
    <form class="humas-home-form" data-humas-form="beranda">
        <section class="humas-home-copy humas-home-card">
            <header><strong>☷ Teks &amp; Narasi Hero Utama</strong><span>Bahasa Indonesia</span></header>
            <div class="humas-home-fields">
                <label>Badge / Label Informasi Atas
                    <input name="badge" maxlength="100" placeholder="Label informasi di atas judul">
                    <small>Label kecil yang tampil di atas tajuk utama dengan sorotan visual lembut.</small>
                </label>
                <label>Judul Utama Landing Page (H1 Headline)
                    <textarea name="headline" rows="2" maxlength="100" placeholder="Masukkan judul utama"></textarea>
                    <small class="humas-home-count"><span data-count-for="headline">0</span> / 100 Karakter</small>
                </label>
                <label>Subjudul / Teks Pengantar Singkat
                    <textarea name="intro" rows="4" maxlength="220" placeholder="Tuliskan pengantar singkat untuk pengunjung"></textarea>
                    <small class="humas-home-count"><span data-count-for="intro">0</span> / 220 Karakter</small>
                    <small>Ringkasan profil madrasah yang tampil di bawah judul hero.</small>
                </label>
            </div>
        </section>
        <aside class="humas-home-banner humas-home-card">
            <header><strong>▧ Banner Utama (Hero Media)</strong><span>Rasio 16:9</span></header>
            <p>Gambar latar belakang untuk menyambut calon santri dan wali murid pada halaman utama.</p>
            <div class="humas-home-image-wrap">
                <img data-home-image-preview hidden alt="Pratinjau banner beranda">
                <div data-home-image-empty>Belum ada banner dipilih</div>
                <div class="humas-home-image-caption" data-home-image-caption hidden></div>
            </div>
            <div class="humas-home-image-actions">
                <label>◎ Ganti Gambar<input type="file" accept="image/jpeg,image/png,image/webp" data-home-image hidden></label>
                <button type="button" data-home-remove>♜ Hapus</button>
            </div>
            <label class="humas-home-upload">
                <span class="humas-home-upload-icon" aria-hidden="true">☁</span>
                <strong>Tarik &amp; letakkan file baru ke sini</strong>
                <small>JPG, PNG, atau WebP (Maks. 5 MB). Disarankan resolusi 1920×1080px.</small>
                <span class="humas-home-browse">Atau telusuri dari perangkat →</span>
                <span class="humas-home-file-row">
                    <span class="humas-home-file-button">Choose File</span>
                    <span data-home-file-name>No file chosen</span>
                    <input type="file" accept="image/jpeg,image/png,image/webp" data-home-image-drop aria-label="Pilih file banner">
                </span>
            </label>
        </aside>
        <footer class="humas-home-actions">
            <a href="{{ url('/humas/dashboard') }}">Batal</a>
            <button type="button" data-home-save="Draft">▯ Simpan Draft</button>
            <button type="button" class="primary" data-home-save="Published">Publikasikan Konten</button>
        </footer>
    </form>
</section>
@endsection