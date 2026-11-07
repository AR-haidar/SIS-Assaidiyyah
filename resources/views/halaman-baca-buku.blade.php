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
</head>
<body class="digital-reader-page">
    <header class="digital-reader-topbar">
        <a href="{{ url('/perpustakaan-digital/buku/' . $bookId) }}" class="digital-reader-back"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>Kembali ke Detail Buku</a>
        <span class="digital-reader-page-status">Halaman <b id="readerCurrentTop">12</b> dari <b>84</b></span>
    </header>
    <main class="digital-reader-main">
        <article class="digital-reader-paper" aria-label="Halaman buku digital"></article>
    </main>
    <footer class="digital-reader-controls">
        <button type="button" class="digital-reader-previous" id="readerPrevious"><span aria-hidden="true">&lsaquo;</span> Halaman Sebelumnya</button>
        <label class="digital-reader-page-picker">Menuju <input id="readerPageInput" type="number" min="1" max="84" value="12" aria-label="Nomor halaman"> dari <strong>84</strong></label>
        <button type="button" class="digital-reader-next" id="readerNext">Halaman Berikutnya <span aria-hidden="true">&rsaquo;</span></button>
    </footer>
    <script>
        (function () {
            const totalPages = 84;
            const pageInput = document.getElementById('readerPageInput');
            const topPage = document.getElementById('readerCurrentTop');
            function showPage(value) {
                const page = Math.max(1, Math.min(totalPages, Number(value) || 1));
                pageInput.value = page;
                topPage.textContent = page;
            }
            pageInput.addEventListener('change', function () { showPage(this.value); });
            document.getElementById('readerPrevious').addEventListener('click', function () { showPage(Number(pageInput.value) - 1); });
            document.getElementById('readerNext').addEventListener('click', function () { showPage(Number(pageInput.value) + 1); });
        })();
    </script>
</body>
</html>