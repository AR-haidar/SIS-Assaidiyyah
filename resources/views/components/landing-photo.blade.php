@props(['src' => null, 'label', 'caption' => null, 'icon' => 'image'])
<div {{ $attributes->class(['landing-photo']) }}>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $label }}" loading="lazy" decoding="async">
    @else
        @if ($icon)
            <span class="landing-photo-symbol"><x-landing-icon :name="$icon" /></span>
        @endif
        <span>{{ $label }}</span>
        @if ($caption)
            <small>{{ $caption }}</small>
        @endif
    @endif
</div>
