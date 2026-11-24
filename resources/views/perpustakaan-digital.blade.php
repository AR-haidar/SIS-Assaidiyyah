<x-public-library-layout title="Perpustakaan Digital">
    <main id="konten-perpustakaan" class="landing-wrap dlib-public-main" tabindex="-1">
        <header class="dlib-public-heading">
            <h1>Perpustakaan Digital</h1>
            <p>Jelajahi koleksi pustaka digital dan baca naskah buku yang tersedia secara daring.</p>
        </header>

        <form class="dlib-panel dlib-public-controls" method="GET" action="{{ route('publik.perpustakaan.index') }}" role="search" aria-label="Pencarian buku">
            <div class="dlib-public-control-row">
                <div class="dlib-public-search">
                    <button type="submit" aria-label="Cari buku" title="Cari buku"><x-landing-icon name="search" :size="17" /></button>
                    <input type="search" name="q" value="{{ $filter['q'] ?? '' }}" maxlength="{{ \App\Models\Buku::MAKS_JUDUL_KARAKTER }}"
                        placeholder="Cari judul atau penulis buku..." aria-label="Cari judul atau penulis buku">
                </div>
                <div class="dlib-public-select">
                    <select name="kategori" aria-label="Pilih kategori" onchange="this.form.requestSubmit()">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoriBuku as $kategori)
                            <option value="{{ $kategori->id }}" @selected((int) ($filter['kategori'] ?? 0) === $kategori->id)>{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    <x-landing-icon name="chevron-down" :size="16" />
                </div>
                <a href="{{ route('publik.perpustakaan.index') }}" class="landing-button dlib-button-quiet dlib-public-reset"><x-landing-icon name="reset" :size="16" /> Atur Ulang</a>
            </div>
            <div class="dlib-public-categories" role="group" aria-label="Kategori koleksi">
                <button class="landing-button dlib-category" type="button" value="" aria-pressed="{{ isset($filter['kategori']) ? 'false' : 'true' }}"
                    onclick="this.form.elements.kategori.value = this.value; this.form.requestSubmit()">Semua Kategori</button>
                @foreach ($kategoriBuku as $kategori)
                    <button class="landing-button dlib-category" type="button" value="{{ $kategori->id }}" aria-pressed="{{ (int) ($filter['kategori'] ?? 0) === $kategori->id ? 'true' : 'false' }}"
                        onclick="this.form.elements.kategori.value = this.value; this.form.requestSubmit()">{{ $kategori->nama }}</button>
                @endforeach
            </div>
        </form>

        <section aria-labelledby="judul-daftar-pustaka">
            <h2 class="dlib-list-title" id="judul-daftar-pustaka">Daftar Pustaka</h2>
            <div class="dlib-public-book-grid">
                @forelse ($katalog as $buku)
                    <article class="dlib-panel dlib-public-book-card">
                        <div class="dlib-public-cover">
                            @if ($buku->sampul)
                                <img src="{{ route('publik.perpustakaan.buku.sampul', $buku->id) }}" alt="Sampul {{ $buku->judul }}" loading="lazy" decoding="async">
                            @else
                                <span>Sampul Buku (3:4)</span>
                            @endif
                            <small>E-Book {{ $buku->naskah->mime === 'application/epub+zip' ? 'EPUB' : 'PDF' }}</small>
                        </div>
                        <div class="dlib-public-book-text">
                            <span class="landing-chip">Kategori: {{ $buku->kategori->pluck('nama')->join(', ') ?: '—' }}</span>
                            <h3>{{ $buku->judul }}</h3>
                            <p>Penulis: {{ $buku->penulis }}</p>
                        </div>
                        <footer>
                            <a class="landing-button dlib-book-link" href="{{ route('publik.perpustakaan.buku.detail', $buku->id) }}" aria-label="Baca {{ $buku->judul }}"><x-landing-icon name="eye" :size="16" /> Baca</a>
                        </footer>
                    </article>
                @empty
                    <div class="dlib-panel dlib-empty">
                        <x-landing-icon name="book" :size="30" />
                        @if (isset($filter['q']) || isset($filter['kategori']))
                            <h3>Buku Tidak Ditemukan</h3>
                            <p>Coba kata kunci atau kategori lain.</p>
                            <a href="{{ route('publik.perpustakaan.index') }}" class="landing-button landing-button-soft"><x-landing-icon name="reset" :size="16" /> Atur Ulang</a>
                        @else
                            <h3>Belum Ada Koleksi Buku Ditambahkan</h3>
                            <p>Buku yang telah diterbitkan akan muncul di sini.</p>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</x-public-library-layout>
