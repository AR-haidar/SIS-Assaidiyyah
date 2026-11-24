@php($pdf = $buku->naskah->mime === 'application/pdf')
<x-public-library-layout :title="'Baca '.$buku->judul" stylesheet="resources/css/halaman-baca-buku.css" :reader="$pdf" class="digital-reader-page">
    <main id="konten-perpustakaan" class="digital-reader-shell" tabindex="-1" aria-label="Pembaca {{ $buku->judul }}"
        @if ($pdf) data-pdf-url="{{ route('publik.perpustakaan.buku.naskah', $buku->id) }}" @endif>
        <div class="digital-reader-topbar">
            <div class="landing-wrap digital-reader-bar">
                <a href="{{ route('publik.perpustakaan.buku.detail', $buku->id) }}" class="dlib-back"><x-landing-icon name="arrow-left" :size="17" /> Kembali ke Detail Buku</a>
                @if ($pdf)
                    <span class="digital-reader-page-status" data-pdf-page aria-live="polite">Halaman —</span>
                @endif
            </div>
        </div>

        <div class="digital-reader-pdf-page" @if ($pdf) tabindex="0" aria-label="Area naskah PDF" @endif>
            @if ($pdf)
                <p class="digital-reader-paper digital-reader-message" data-pdf-status role="status">Memuat naskah PDF…</p>
                <canvas class="digital-reader-paper" data-pdf-canvas aria-label="Halaman PDF {{ $buku->judul }}" hidden></canvas>
            @else
                <div class="digital-reader-paper digital-reader-message">
                    <x-landing-icon name="book" :size="29" />
                    <p>Pembaca EPUB belum tersedia.</p>
                </div>
            @endif
        </div>

        @if ($pdf)
            <footer class="digital-reader-controls" aria-label="Kontrol pembaca PDF">
                <div class="landing-wrap digital-reader-control-row">
                    <div class="digital-reader-pagination">
                        <button class="landing-button dlib-button-quiet digital-reader-previous" type="button" data-pdf-prev disabled>
                            <x-landing-icon name="chevron-left" :size="16" /> <span><span class="digital-reader-long-label">Halaman </span>Sebelumnya</span>
                        </button>
                        <form class="digital-reader-page-picker" data-pdf-picker novalidate>
                            <label for="nomor-halaman-pdf">Menuju</label>
                            <input id="nomor-halaman-pdf" type="number" min="1" step="1" inputmode="numeric" enterkeyhint="go"
                                title="Masukkan nomor halaman dan tekan Enter" value="1" data-pdf-target required disabled>
                            <button class="dlib-visually-hidden" type="submit">Buka nomor halaman</button>
                            <span>dari <strong data-pdf-total>—</strong></span>
                        </form>
                        <button class="landing-button digital-reader-next" type="button" data-pdf-next disabled>
                            <span><span class="digital-reader-long-label">Halaman </span>Berikutnya</span> <x-landing-icon name="chevron-right" :size="16" />
                        </button>
                    </div>
                    <div class="digital-reader-zoom" role="group" aria-label="Ukuran halaman">
                        <button class="landing-button dlib-button-quiet" type="button" data-pdf-zoom-out title="Perkecil halaman" aria-label="Perkecil halaman" disabled><x-landing-icon name="minus" :size="16" /></button>
                        <output data-pdf-zoom aria-label="Persentase ukuran">100%</output>
                        <button class="landing-button dlib-button-quiet" type="button" data-pdf-zoom-in title="Perbesar halaman" aria-label="Perbesar halaman" disabled><x-landing-icon name="plus" :size="16" /></button>
                    </div>
                </div>
            </footer>
        @endif
    </main>
</x-public-library-layout>
