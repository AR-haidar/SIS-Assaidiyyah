<!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') - SIS MI Assa'idiyyah</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/css/siswa/siswa.css', 'resources/css/perpustakaan/perpustakaan.css', 'resources/js/app.js'])
</head><body class="siswa-page perpustakaan-page">
@include('perpustakaan.partials.topbar')
<div class="siswa-layout">@include('perpustakaan.partials.sidebar')
<main class="siswa-main perpustakaan-main">@yield('content')</main></div>
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const perpustakaanSidebar = document.getElementById('perpustakaanSidebar');
    const narrowScreen = window.matchMedia('(max-width: 768px)');

    function updateSidebar(expanded) {
        const defaultExpanded = !narrowScreen.matches;
        perpustakaanSidebar.classList.toggle('is-toggled', expanded !== defaultExpanded);
        sidebarToggle.setAttribute('aria-expanded', String(expanded));
    }

    updateSidebar(!narrowScreen.matches);

    sidebarToggle.addEventListener('click', () => {
        updateSidebar(sidebarToggle.getAttribute('aria-expanded') === 'false');
    });

    narrowScreen.addEventListener('change', () => updateSidebar(!narrowScreen.matches));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && narrowScreen.matches && sidebarToggle.getAttribute('aria-expanded') === 'true') {
            updateSidebar(false);
            sidebarToggle.focus();
        }
    });
</script></body></html>
