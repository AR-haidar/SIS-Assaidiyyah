@props(['schoolName' => 'MI Assaidiyyah', 'catalog' => false, 'portal' => null, 'main' => 'beranda'])
@php
    $beranda = $catalog ? route('publik.beranda') : '';
    $nav = [
        'beranda' => 'Beranda',
        'profil' => 'Profil & Visi Misi',
        'program' => 'Program',
        'fasilitas' => 'Profil Guru',
        'berita' => 'Prestasi Siswa',
        'perpustakaan' => 'Perpustakaan Digital',
        'kontak' => 'Kontak',
    ];
@endphp
<a class="landing-skip" href="#{{ $main }}">Langsung ke konten</a>
<header class="landing-header">
    <div class="landing-wrap landing-header-inner">
        <a class="landing-brand" href="{{ $beranda }}#beranda">
            <img src="{{ asset('images/logo-assaidiyyah.png') }}" width="1269" height="542" alt="">
            <span><strong>{{ $schoolName }}</strong><small>Sistem Informasi Madrasah</small></span>
        </a>
        <button class="landing-menu" type="button" aria-controls="landing-nav" aria-expanded="false" aria-label="Buka menu navigasi" hidden>
            <x-landing-icon name="menu" />
        </button>
        <nav class="landing-nav" id="landing-nav" aria-label="Navigasi utama" @if (! $catalog) data-section-nav @endif>
            @foreach ($nav as $id => $label)
                <a href="{{ $id === 'perpustakaan' ? route('publik.perpustakaan.index') : $beranda.'#'.$id }}"
                    @if ($catalog && $id === 'perpustakaan') aria-current="page"
                    @elseif (! $catalog && $loop->first) aria-current="location" @endif>{{ $label }}</a>
            @endforeach
        </nav>
        <a class="landing-button landing-portal" href="{{ $portal ?? route(\App\Support\PortalPublik::rute(request())) }}">@auth Dashboard @else Portal Login @endauth</a>
    </div>
</header>
