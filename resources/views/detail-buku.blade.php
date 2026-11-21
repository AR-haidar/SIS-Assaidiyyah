<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - Perpustakaan Digital MI Assaidiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/detail-buku.css', 'resources/js/app.js'])
</head>
<body class="book-detail-page">
    <main class="book-detail-main">
        <a href="{{ url('/perpustakaan-digital') }}" class="book-detail-back"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>Kembali ke Perpustakaan</a>
        <div class="book-detail-grid">
            <section class="book-detail-cover-card" aria-label="Pratinjau sampul buku"><div class="book-detail-cover-placeholder">PRATINJAU SAMPUL BUKU (3:4)</div></section>
            <div class="book-detail-content">
                <header class="book-detail-title">
                    <span>Kategori: &mdash;</span>
                    <h1>&mdash; (Judul Buku)</h1>
                </header>
                <section class="book-detail-metadata" aria-label="Informasi buku">
                    <article><span>Penulis / Pengarang</span><strong>&mdash;</strong></article>
                    <article><span>Penerbit Naskah</span><strong>&mdash;</strong></article>
                    <article><span>Tahun Terbit</span><strong>&mdash;</strong></article>
                    <article><span>Klasifikasi DDC</span><strong>&mdash;</strong></article>
                </section>
                <section class="book-detail-synopsis">
                    <h2><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>Sinopsis &amp; Deskripsi Naskah</h2>
                </section>
                <footer class="book-detail-actions">
                    <a href="{{ url('/perpustakaan-digital/buku/' . $bookId . '/baca') }}" class="book-detail-read"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 7v14M3 18V5a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v16a2 2 0 0 0-2-2H3z"/><path d="M21 18V5a2 2 0 0 0-2-2h-5a2 2 0 0 0-2 2"/></svg>Baca Buku</a>
                </footer>
            </div>
        </div>
    </main>
</body>
</html>