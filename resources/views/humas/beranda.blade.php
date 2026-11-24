@extends('humas.layout')
@section('title', 'Kelola Konten Beranda')
@section('content')
    @php
        $kontenBeranda = $kontenBeranda ?? null;
        $urlDashboard = \Illuminate\Support\Facades\Route::has('humas.dashboard')
            ? route('humas.dashboard')
            : url('/humas/dashboard');
        $formAction = \Illuminate\Support\Facades\Route::has('humas.beranda.update')
            ? route('humas.beranda.update')
            : url('/humas/beranda');
        $badgeVal = old('badge', $kontenBeranda->badge ?? '');
        $headlineVal = old('headline', $kontenBeranda->headline ?? '');
        $introVal = old('intro', $kontenBeranda->intro ?? '');
    @endphp
    <section class="humas-home-editor">
        <header class="humas-home-heading">
            <div>
                <h1>Kelola Konten Beranda</h1>
                <p>Kelola teks utama, banner sambutan, pengumuman highlight, dan tombol aksi pada halaman depan madrasah.
                </p>
            </div>
        </header>

        @if (session('status'))
            <div role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="humas-home-form"
            data-humas-form="beranda">
            @csrf
            @method('PUT')
            <section class="humas-home-copy humas-home-card">
                <header><strong>&#9779; Teks &amp; Narasi Hero Utama</strong><span>Bahasa Indonesia</span></header>
                <div class="humas-home-fields">
                    <label>
                        Badge / Label Informasi Atas
                        <input name="badge" maxlength="100" value="{{ $badgeVal }}"
                            placeholder="Label informasi di atas judul">
                        <small>Label kecil yang tampil di atas tajuk utama dengan sorotan visual lembut.</small>
                    </label>
                    <label>
                        Judul Utama Landing Page (H1 Headline)
                        <textarea name="headline" rows="2" maxlength="100" placeholder="Masukkan judul utama">{{ $headlineVal }}</textarea>
                        <small class="humas-home-count"><span
                                data-count-for="headline">{{ mb_strlen((string) $headlineVal) }}</span> / 100
                            Karakter</small>
                    </label>
                    <label>
                        Subjudul / Teks Pengantar Singkat
                        <textarea name="intro" rows="4" maxlength="220" placeholder="Tuliskan pengantar singkat untuk pengunjung">{{ $introVal }}</textarea>
                        <small class="humas-home-count"><span
                                data-count-for="intro">{{ mb_strlen((string) $introVal) }}</span> / 220 Karakter</small>
                        <small>Ringkasan profil madrasah yang tampil di bawah judul hero.</small>
                    </label>
                </div>
            </section>
            <aside class="humas-home-banner humas-home-card">
                <header><strong>&#9784; Banner Utama (Hero Media)</strong><span>Rasio 16:9</span></header>
                <p>Gambar latar belakang untuk menyambut calon santri dan wali murid pada halaman utama.</p>
                <div class="humas-home-image-wrap">
                    <img data-home-image-preview hidden alt="Pratinjau banner beranda">
                    <div data-home-image-empty>
                        {{ $kontenBeranda->banner_berkas_id ?? null ? 'Banner aktif tersimpan' : 'Belum ada banner dipilih' }}
                    </div>
                    <div class="humas-home-image-caption" data-home-image-caption hidden></div>
                </div>
                <div class="humas-home-image-actions">
                    <label>&#9678; Ganti Gambar<input type="file" name="banner" accept="image/jpeg,image/png,image/webp"
                            data-home-image hidden></label>
                    <button type="button" data-home-remove>&#9820; Hapus</button>
                </div>
                <label class="humas-home-upload">
                    <span class="humas-home-upload-icon" aria-hidden="true">&#9729;</span>
                    <strong>Tarik &amp; letakkan file baru ke sini</strong>
                    <small>JPG, PNG, atau WebP (Maks. 5 MB). Disarankan resolusi 1920&times;1080px.</small>
                    <span class="humas-home-browse">Atau telusuri dari perangkat &rarr;</span>
                    <span class="humas-home-file-row">
                        <span class="humas-home-file-button">Choose File</span>
                        <span data-home-file-name>No file chosen</span>
                        <input type="file" name="banner_drop" accept="image/jpeg,image/png,image/webp"
                            data-home-image-drop aria-label="Pilih file banner">
                    </span>
                </label>
            </aside>
            <footer class="humas-home-actions">
                <a href="{{ $urlDashboard }}">Batal</a>
                <button type="submit" name="status" value="draft" data-home-save="Draft">&#9647; Simpan Draft</button>
                <button type="submit" name="status" value="terbit" class="primary" data-home-save="Published">Publikasikan
                    Konten</button>
            </footer>
        </form>
    </section>
@endsection
