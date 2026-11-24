<x-public-library-layout :title="$buku->judul" stylesheet="resources/css/detail-buku.css">
    <main id="konten-perpustakaan" class="landing-wrap dlib-public-main" tabindex="-1">
        <a href="{{ route('publik.perpustakaan.index') }}" class="dlib-back"><x-landing-icon name="arrow-left" :size="17" /> Kembali ke Perpustakaan</a>
        <div class="book-detail-grid">
            <figure class="dlib-panel book-detail-cover-card">
                <div class="book-detail-cover">
                    @if ($buku->sampul)
                        <img src="{{ route('publik.perpustakaan.buku.sampul', $buku->id) }}" alt="Sampul {{ $buku->judul }}">
                    @else
                        <span>Pratinjau Sampul Buku (3:4)</span>
                    @endif
                </div>
            </figure>
            <div class="book-detail-content">
                <header class="book-detail-title">
                    <span class="landing-chip">Kategori: {{ $buku->kategori->pluck('nama')->join(', ') ?: '—' }}</span>
                    <h1>{{ $buku->judul }}</h1>
                </header>
                <dl class="dlib-panel book-detail-metadata" aria-label="Informasi buku">
                    <div><dt>Penulis / Pengarang</dt><dd>{{ $buku->penulis }}</dd></div>
                    <div><dt>Penerbit Naskah</dt><dd>{{ $buku->penerbit ?? '—' }}</dd></div>
                    <div><dt>Tahun Terbit</dt><dd>{{ $buku->tahun_terbit ?? '—' }}</dd></div>
                    <div><dt>Klasifikasi DDC</dt><dd>—</dd></div>
                </dl>
                <section class="dlib-panel book-detail-synopsis" aria-labelledby="judul-sinopsis">
                    <h2 id="judul-sinopsis"><x-landing-icon name="paragraph" :size="19" /> Sinopsis &amp; Deskripsi Naskah</h2>
                    <p>{{ $buku->sinopsis ?? '—' }}</p>
                </section>
                <div class="dlib-panel book-detail-actions">
                    <a href="{{ route('publik.perpustakaan.buku.baca', $buku->id) }}" class="landing-button"><x-landing-icon name="book" :size="18" /> Baca Buku</a>
                </div>
            </div>
        </div>
    </main>
</x-public-library-layout>
