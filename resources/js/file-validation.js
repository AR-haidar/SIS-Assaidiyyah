export function getFileValidationMessage(input) {
    const file = input.files[0];
    const allowed = input.accept.split(',').map((item) => item.trim().toLowerCase());
    const extension = file?.name.toLowerCase().match(/\.[^.]+$/)?.[0];
    const invalidType = file && input.accept && !allowed.includes(file.type.toLowerCase()) && !allowed.includes(extension);
    const tooLarge = file && file.size > Number(input.dataset.fileMaxBytes);
    return invalidType ? input.dataset.fileTypeMessage : tooLarge ? input.dataset.fileSizeMessage : '';
}

for (const preview of document.querySelectorAll('[data-cover-preview]')) {
    const image = preview.querySelector('[data-cover-image]');
    if (!image) continue;

    const placeholders = preview.querySelectorAll('[data-cover-placeholder]');
    const input = preview.closest('label')?.querySelector('input[type="file"]');
    let original = image.getAttribute('src');
    let temporaryUrl;

    function toggleImage(show) {
        image.hidden = !show;
        for (const placeholder of placeholders) placeholder.hidden = show;
    }

    image.addEventListener('load', () => toggleImage(true));
    image.addEventListener('error', () => {
        if (temporaryUrl) {
            URL.revokeObjectURL(temporaryUrl);
            temporaryUrl = undefined;
            if (original) {
                image.src = original;
                return;
            }
        } else {
            original = null;
        }

        image.removeAttribute('src');
        toggleImage(false);
    });

    if (original && image.complete && !image.naturalWidth) {
        original = null;
        image.removeAttribute('src');
        toggleImage(false);
    }

    input?.addEventListener('change', () => {
        if (temporaryUrl) URL.revokeObjectURL(temporaryUrl);

        const file = input.files[0];
        temporaryUrl = file && !getFileValidationMessage(input) ? URL.createObjectURL(file) : undefined;
        const source = temporaryUrl || original;
        if (source) {
            image.src = source;
        } else {
            image.removeAttribute('src');
            toggleImage(false);
        }
    });
}

function validateFile(input) {
    const message = getFileValidationMessage(input);
    input.setCustomValidity(message);

    const feedback = document.getElementById(input.dataset.fileFeedback);

    if (feedback) {
        feedback.textContent = message;
        feedback.hidden = !message;
    }

    if (message) {
        input.setAttribute('aria-invalid', 'true');
    } else {
        input.removeAttribute('aria-invalid');
    }
}

document.addEventListener('change', (event) => {
    const input = event.target;

    if (!(input instanceof HTMLInputElement) || !input.matches('input[type="file"][data-file-max-bytes]')) {
        return;
    }

    if (!input.form?.matches('[data-inline-validation]')) {
        validateFile(input);
    }

    if (input.dataset.fileSelection) {
        const selection = document.getElementById(input.dataset.fileSelection);
        const name = input.validity.customError ? '' : input.files[0]?.name ?? '';

        selection.textContent = name;
        selection.hidden = !name;
    }
});
