@props(['title', 'stylesheet' => null, 'reader' => false])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | MI Assaidiyyah</title>
    <link rel="icon" href="{{ asset('images/logo-assaidiyyah.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/landing.css', 'resources/css/perpustakaan-digital.css', 'resources/js/landing.js'])
    @if ($stylesheet)
        @vite($stylesheet)
    @endif
    @if ($reader)
        @vite('resources/js/pembaca-pdf.js')
    @endif
</head>
<body {{ $attributes->class(['landing-page', 'dlib-public-page']) }}>
    <x-public-header catalog main="konten-perpustakaan" />
    {{ $slot }}
</body>
</html>
