@props(['name', 'size' => 16])

<svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none"
    stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
    focusable="false" {{ $attributes }}>
    @switch($name)
        @case('back')
            <path d="M21 12H4m6-6-6 6 6 6" />
            @break
        @case('book-open')
            <path d="M12 7v13M12 7C9 4 5 4 2 5v14c4-1 7-1 10 1 3-2 6-2 10-1V5c-3-1-7-1-10 2Z" />
            @break
        @case('books')
            <rect x="7" y="3" width="13" height="15" rx="2" />
            <path d="M4 7H3v14h13v-1m-6-13h7m-7 4h7m-7 4h4" />
            @break
        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="2" />
            <path d="M7 3v4m10-4v4M3 10h18" />
            @break
        @case('category')
            <rect x="9" y="2" width="6" height="6" rx="1" />
            <rect x="2" y="16" width="6" height="6" rx="1" />
            <rect x="16" y="16" width="6" height="6" rx="1" />
            <path d="M12 8v4M5 16v-4h14v4" />
            @break
        @case('chevron-down')
            <path d="m6 9 6 6 6-6" />
            @break
        @case('file-plus')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 0v6h6m-8 3v7m-3.5-3.5h7" />
            @break
        @case('file-up')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 0v6h6m-8 10v-7m-3 3 3-3 3 3" />
            @break
        @case('image-plus')
            <path d="M11 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8" />
            <path d="m3 17 5-5 4 4 3-3 6 6" />
            <circle cx="8.5" cy="8.5" r="1.5" />
            <path d="M16 5h6m-3-3v6" />
            @break
        @case('plus')
            <path d="M12 5v14M5 12h14" />
            @break
        @case('publisher')
            <path d="M3 21h18M5 21V4h9v17m0-12h5v12M8 8h3M8 12h3M8 16h3" />
            @break
        @case('save')
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12l4 4v12a2 2 0 0 1-2 2ZM7 3v6h9V3m-9 18v-8h10v8" />
            @break
        @case('trash')
            <path d="M3 6h18M9 6V3h6v3m3 0-1 15H7L6 6m4 4v7m4-7v7" />
            @break
        @case('upload-cloud')
            <path d="M7 18H6a4 4 0 1 1 .7-7.9 6 6 0 0 1 11.7 1.2A3.4 3.4 0 0 1 18 18h-1m-5 3v-8m-3 3 3-3 3 3" />
            @break
        @case('user')
            <circle cx="12" cy="8" r="4" />
            <path d="M4 21a8 8 0 0 1 16 0" />
            @break
    @endswitch
</svg>
