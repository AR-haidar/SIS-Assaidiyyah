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
const toggleBtn=document.getElementById('sidebarToggle');
const sidebar=document.getElementById('perpustakaanSidebar');
toggleBtn.addEventListener('click',function(){sidebar.classList.toggle('collapsed');});
</script></body></html>