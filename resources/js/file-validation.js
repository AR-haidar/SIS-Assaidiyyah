export function getFileValidationMessage(input) {
    const file = input.files[0];
    const allowed = input.accept.split(',').map((item) => item.trim().toLowerCase());
    const extension = file?.name.toLowerCase().match(/\.[^.]+$/)?.[0];
    const invalidType = file && input.accept && !allowed.includes(file.type.toLowerCase()) && !allowed.includes(extension);
    const tooLarge = file && file.size > Number(input.dataset.fileMaxBytes);
    return invalidType ? input.dataset.fileTypeMessage : tooLarge ? input.dataset.fileSizeMessage : '';
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
