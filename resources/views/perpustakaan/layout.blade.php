<!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') - SIS MI Assa'idiyyah</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/css/perpustakaan/perpustakaan.css', 'resources/js/app.js'])
</head><body class="siswa-page perpustakaan-page">
@include('perpustakaan.partials.topbar')
<div class="siswa-layout">@include('perpustakaan.partials.sidebar')
<main class="siswa-main perpustakaan-main">@yield('content')</main></div>
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('perpustakaanSidebar');
    const narrowScreen = window.matchMedia('(max-width: 768px)');

    function updateSidebar(expanded) {
        sidebar.classList.toggle('is-toggled', expanded !== !narrowScreen.matches);
        sidebarToggle.setAttribute('aria-expanded', String(expanded));
    }

    updateSidebar(!narrowScreen.matches);
    sidebarToggle.addEventListener('click', () => updateSidebar(sidebarToggle.getAttribute('aria-expanded') === 'false'));
    narrowScreen.addEventListener('change', () => updateSidebar(!narrowScreen.matches));
</script></body></html>
