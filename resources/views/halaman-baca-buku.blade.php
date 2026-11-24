<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Buku - Perpustakaan Digital MI Assaidiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/halaman-baca-buku.css', 'resources/js/app.js'])
    @if ($buku->naskah->mime === 'application/pdf')
        @vite('resources/js/pembaca-pdf.js')
    @endif
</head>
<body class="digital-reader-page">
    <header class="digital-reader-topbar">
        <a href="{{ route('publik.perpustakaan.buku.detail', $buku->id) }}" class="digital-reader-back"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>Kembali ke Detail Buku</a>
        <span class="digital-reader-page-status">{{ $buku->judul }}</span>
    </header>
    <main class="digital-reader-main">
        @if ($buku->naskah->mime === 'application/pdf')
            <section class="digital-reader-pdf" data-pdf-url="{{ route('publik.perpustakaan.buku.naskah', $buku->id) }}" aria-label="Naskah {{ $buku->judul }}">
                <p data-pdf-status role="status">Memuat naskah PDF…</p>
                <div class="digital-reader-pdf-page">
                    <canvas class="digital-reader-paper" data-pdf-canvas aria-label="Halaman PDF" hidden></canvas>
                </div>
                <footer class="digital-reader-controls digital-reader-pdf-controls" aria-label="Kontrol pembaca PDF">
                    <button type="button" data-pdf-prev disabled>Sebelumnya</button>
                    <span data-pdf-page aria-live="polite">Halaman —</span>
                    <button type="button" data-pdf-next disabled>Berikutnya</button>
                    <button type="button" data-pdf-zoom-out aria-label="Perkecil halaman" disabled>−</button>
                    <button type="button" data-pdf-zoom-in aria-label="Perbesar halaman" disabled>+</button>
                </footer>
            </section>
        @else
            <article class="digital-reader-paper flex items-center justify-center" aria-label="Naskah EPUB">
                <p>Pembaca EPUB belum tersedia.</p>
            </article>
        @endif
    </main>
</body>
</html>
