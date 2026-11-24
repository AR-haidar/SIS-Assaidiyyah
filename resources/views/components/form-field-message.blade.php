@props(['id', 'kind' => 'error', 'message' => null])

<p id="{{ $id }}" {{ $attributes->class(['form-field-message', 'form-field-message--limit' => $kind === 'limit']) }}
    @if (!$message) hidden @endif>{{ $message }}</p>
