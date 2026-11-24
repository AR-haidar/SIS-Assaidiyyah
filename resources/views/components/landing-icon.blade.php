@props(['name', 'size' => 20])
<svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
    fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('image')
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <circle cx="8.5" cy="8.5" r="1.5" />
            <path d="m3 17 5-5 4 4 3-3 6 6" />
            @break
        @case('book')
            <path d="M12 7v13M12 7C9 4 5 4 2 5v14c4-1 7-1 10 1 3-2 6-2 10-1V5c-3-1-7-1-10 2Z" />
            @break
        @case('document')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 0v6h6M8 13h6m-6 4h4" />
            <path d="m15 16 3 3" />
            @break
        @case('pin')
            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" />
            <circle cx="12" cy="10" r="2.5" />
            @break
        @case('person')
            <circle cx="12" cy="8" r="3.5" />
            <path d="M4 21a8 8 0 0 1 16 0" />
            @break
        @case('school')
            <path d="M5 21h14M7 21V4h10v17m-7 0v-5h4v5M10 7h1m2 0h1m-4 4h1m2 0h1" />
            @break
        @case('building')
            <path d="M3 9h18L12 4 3 9Zm0 11h18M6 12v5m6-5v5m6-5v5" />
            @break
        @case('globe')
            <circle cx="12" cy="12" r="9" />
            <path d="m6 5 3 5 4 1 2 3-3 2-1 5M16 4l-1 4 3 2h3" />
            @break
        @case('arrow')
            <path d="M4 12h16m-6-6 6 6-6 6" />
            @break
        @case('arrow-left')
            <path d="M20 12H4m6-6-6 6 6 6" />
            @break
        @case('chevron-left')
            <path d="m15 6-6 6 6 6" />
            @break
        @case('chevron-right')
            <path d="m9 6 6 6-6 6" />
            @break
        @case('chevron-down')
            <path d="m6 9 6 6 6-6" />
            @break
        @case('search')
            <circle cx="11" cy="11" r="6.5" />
            <path d="m16 16 4 4" />
            @break
        @case('reset')
            <path d="M3 4v5h5m-5 0a8.5 8.5 0 1 1 .7 7" />
            @break
        @case('eye')
            <path d="M2 12s3.5-6.5 10-6.5 10 6.5 10 6.5-3.5 6.5-10 6.5S2 12 2 12Z" />
            <circle cx="12" cy="12" r="2.5" />
            @break
        @case('paragraph')
            <path d="M4 5h16M4 9h16M4 13h16M4 17h9" />
            @break
        @case('plus')
            <path d="M5 12h14M12 5v14" />
            @break
        @case('minus')
            <path d="M5 12h14" />
            @break
        @case('menu')
            <path d="M4 6h16M4 12h16M4 18h16" />
            @break
        @case('close')
            <path d="m6 6 12 12M18 6 6 18" />
            @break
    @endswitch
</svg>
