<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIS MI Assa'idiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/css/operator/operator.css', 'resources/css/humas/humas.css', 'resources/css/humas/dashboard.css', 'resources/css/humas/layout.css', 'resources/css/humas/beranda.css', 'resources/css/humas/beranda-tweaks.css', 'resources/css/humas/profil.css', 'resources/css/humas/guru.css', 'resources/css/humas/guru-tweaks.css', 'resources/css/humas/guru-create.css', 'resources/css/humas/program.css', 'resources/css/humas/prestasi.css', 'resources/css/humas/prestasi-tweaks.css', 'resources/css/humas/kontak.css', 'resources/js/app.js', 'resources/js/humas.js'])
</head>
<body class="siswa-page operator-page humas-page">
    @include('humas.partials.topbar')
    <div class="siswa-layout">
        @include('humas.partials.sidebar')
        <main class="siswa-main operator-main humas-main">@yield('content')</main>
    </div>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', () => document.getElementById('humasSidebar')?.classList.toggle('collapsed'));
    </script>
</body>
</html>
