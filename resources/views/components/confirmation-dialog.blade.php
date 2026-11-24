@props([
    'id',
    'title',
    'message',
    'confirmLabel' => 'Lanjutkan',
    'cancelLabel' => 'Kembali',
    'tone' => 'primary',
])

<dialog id="{{ $id }}" class="confirmation-dialog"
    aria-labelledby="{{ $id }}-title" aria-describedby="{{ $id }}-message">
    <form method="dialog">
        <h2 id="{{ $id }}-title">{{ $title }}</h2>
        <p id="{{ $id }}-message">{{ $message }}</p>
        <div class="confirmation-dialog__actions">
            <button type="submit" value="cancel" class="action-button" autofocus>{{ $cancelLabel }}</button>
            <button type="submit" value="confirm"
                class="action-button {{ $tone === 'danger' ? 'action-button--danger action-button--danger-solid' : 'action-button--primary' }}">
                {{ $confirmLabel }}
            </button>
        </div>
    </form>
</dialog>
