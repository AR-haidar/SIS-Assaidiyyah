<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital - MI Assaidiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/css/perpustakaan-digital.css', 'resources/js/app.js'])
</head>
<body class="home-page dlib-public-page">
    <header class="navbar">
        <div class="navbar-container" style="width:100%; max-width:1800px; margin:0 auto; padding:12px 32px; display:grid; grid-template-columns:minmax(0,1fr) auto minmax(0,1fr); gap:16px;">
            <a href="{{ url('/') }}" class="navbar-brand" style="justify-self:start;">
                <div class="logo-slot" title="MI Assaidiyyah"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
                <div class="brand-text"><span class="brand-title">MI ASSA'IDIYYAH</span><span class="brand-subtitle">Madrasah Ibtidaiyah Unggulan Cipanas</span></div>
            </a>
            <nav class="navbar-menu" aria-label="Navigasi utama" style="justify-self:center;">
                <a href="{{ url('/') }}#beranda" class="nav-link">Beranda</a>
                <a href="{{ url('/') }}#profil" class="nav-link">Profile &amp; Visi Misi</a>
                <a href="{{ url('/') }}#program" class="nav-link">Program</a>
                <a href="{{ url('/') }}#fasilitas" class="nav-link">Fasilitas</a>
                <a href="{{ url('/') }}#berita" class="nav-link">Berita</a>
                <a href="{{ url('/perpustakaan-digital') }}" class="nav-link active">Perpustakaan</a>
                <a href="{{ url('/') }}#kontak" class="nav-link">Kontak</a>
            </nav>
            <div class="navbar-action" style="justify-self:end;"><a href="{{ url('/login') }}" class="btn-portal"><span class="btn-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span><span>Portal Login</span></a></div>
        </div>
    </header>

    <main class="dlib-public-main" style="width:100%; max-width:1800px; margin:0 auto; padding:28px 32px 40px; box-sizing:border-box;">
        <header class="dlib-public-heading"><h1>Perpustakaan Digital</h1><p>Jelajahi koleksi pustaka digital dan baca naskah buku yang tersedia secara daring</p></header>
        <form class="dlib-public-controls" method="GET" action="{{ route('publik.perpustakaan.index') }}" aria-label="Pencarian dan kategori buku">
            <button type="submit" class="sr-only">Cari buku</button>
            <div class="dlib-public-control-row">
                <label class="dlib-public-search"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" name="q" value="{{ $filter['q'] ?? '' }}" maxlength="{{ \App\Models\Buku::MAKS_JUDUL_KARAKTER }}" placeholder="Cari judul atau penulis buku..." aria-label="Cari judul atau penulis buku"></label>
                    <label class="dlib-public-select">
                        <select name="kategori" aria-label="Pilih kategori" onchange="this.form.requestSubmit()">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoriBuku as $kategori)
                                <option value="{{ $kategori->id }}" @selected((int) ($filter['kategori'] ?? 0) === $kategori->id)>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </label>
                <a href="{{ route('publik.perpustakaan.index') }}" class="dlib-public-reset"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>Atur Ulang</a>
            </div>
            <nav class="dlib-public-categories" aria-label="Kategori koleksi">
                <button type="button" value="" @class(['active' => !isset($filter['kategori'])]) onclick="this.form.elements.kategori.value = this.value; this.form.requestSubmit()">Semua Kategori</button>
                @foreach ($kategoriBuku as $kategori)
                    <button type="button" value="{{ $kategori->id }}" @class(['active' => (int) ($filter['kategori'] ?? 0) === $kategori->id]) onclick="this.form.elements.kategori.value = this.value; this.form.requestSubmit()">{{ $kategori->nama }}</button>
                @endforeach
            </nav>
        </form>
        <section class="dlib-public-list" aria-label="Daftar pustaka">
            <h2>Daftar Pustaka</h2>
            <div class="dlib-public-book-grid">
                @forelse ($katalog as $buku)
                    <article class="dlib-public-book-card">
                        <div class="dlib-public-cover relative overflow-hidden">
                            @if ($buku->sampul)
                                <img src="{{ route('publik.perpustakaan.buku.sampul', $buku->id) }}" alt="Sampul {{ $buku->judul }}" class="absolute inset-0 h-full w-full object-contain" loading="lazy">
                            @else
                                <span>Sampul Buku (3:4)</span>
                            @endif
                            <small class="relative">E-Book {{ $buku->naskah->mime === 'application/epub+zip' ? 'EPUB' : 'PDF' }}</small>
                        </div>
                        <span class="dlib-public-badge">Kategori: {{ $buku->kategori->pluck('nama')->join(', ') ?: '—' }}</span>
                        <h3>{{ $buku->judul }}</h3>
                        <p>Penulis: {{ $buku->penulis }}</p>
                        <footer><span>ID: {{ $buku->id }}</span><a href="{{ route('publik.perpustakaan.buku.detail', $buku->id) }}" aria-label="Baca {{ $buku->judul }}"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>Baca</a></footer>
                    </article>
                @empty
                    <p class="col-span-full">{{ isset($filter['q']) || isset($filter['kategori']) ? 'Buku Tidak Ditemukan' : 'Belum Ada Koleksi Buku Ditambahkan' }}</p>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
