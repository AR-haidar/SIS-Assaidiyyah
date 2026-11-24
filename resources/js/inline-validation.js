import { getFileValidationMessage } from './file-validation';

const characterCount = new Intl.NumberFormat('id-ID');
const messageDuration = 4000;
const errorTimers = new WeakMap();

function bindCharacterLimit(field) {
    const counter = document.getElementById(field.dataset.characterCounter);
    const feedback = document.getElementById(field.dataset.characterFeedback);
    const maximum = field.maxLength;
    const limitMessage = `Batas ${characterCount.format(maximum)} karakter sudah tercapai.`;
    let noticeTimer;

    function setLimitNotice(visible) {
        field.classList.toggle('form-field-limit-reached', visible);
        counter.classList.toggle('form-field-counter--limit', visible);
        feedback.textContent = visible ? limitMessage : '';
        feedback.hidden = !visible;
    }

    function showLimitNotice() {
        clearTimeout(noticeTimer);
        setLimitNotice(true);
        noticeTimer = setTimeout(() => setLimitNotice(false), messageDuration);
    }

    function updateCounter() {
        counter.textContent = `${characterCount.format(field.value.length)} / ${characterCount.format(maximum)} karakter`;
    }

    updateCounter();
    field.addEventListener('input', () => {
        updateCounter();

        if (field.value.length >= maximum) {
            showLimitNotice();
        } else {
            clearTimeout(noticeTimer);
            setLimitNotice(false);
        }
    });
    field.addEventListener('beforeinput', (event) => {
        if (event.inputType.startsWith('insert') && field.value.length >= maximum
            && field.selectionStart === field.selectionEnd) {
            showLimitNotice();
        }
    });
}

function showFieldMessage(field, feedback, message) {
    clearTimeout(errorTimers.get(feedback));
    errorTimers.delete(feedback);
    feedback.textContent = message;
    feedback.hidden = !message;
    field.classList.toggle('form-field-error-visible', Boolean(message));

    if (message) {
        errorTimers.set(feedback, setTimeout(() => {
            feedback.hidden = true;
            field.classList.remove('form-field-error-visible');
            errorTimers.delete(feedback);
        }, messageDuration));
    }
}

function validateField(field, showAllErrors, revealMessage = false) {
    const { validity, dataset } = field;
    const textIsBlank = field.required && ['text', 'textarea'].includes(field.type) && !field.value.trim();
    let message = '';

    if (field.matches('input[type="file"][data-file-max-bytes]')) {
        message = getFileValidationMessage(field);
        field.setCustomValidity(message);
    }

    if (showAllErrors && (validity.valueMissing || textIsBlank)) {
        message = dataset.requiredMessage || 'Kolom ini wajib diisi.';
    } else if (showAllErrors && field.maxLength >= 0 && field.value.length > field.maxLength) {
        message = `Maksimal ${characterCount.format(field.maxLength)} karakter.`;
    } else if (showAllErrors && !message && !validity.valid) {
        message = dataset.invalidMessage || field.validationMessage;
    }

    const feedback = document.getElementById(dataset.validationError);

    if (revealMessage || feedback.textContent.trim() !== message) {
        showFieldMessage(field, feedback, message);
    }

    if (message) {
        field.setAttribute('aria-invalid', 'true');
    } else {
        field.removeAttribute('aria-invalid');
    }

    return !message;
}

for (const form of document.querySelectorAll('form[data-inline-validation]')) {
    const fields = Array.from(form.elements).filter((field) => field.willValidate && field.dataset.validationError);
    let attemptedSubmit = fields.some((field) => field.getAttribute('aria-invalid') === 'true');

    for (const field of fields) {
        const feedback = document.getElementById(field.dataset.validationError);

        if (!feedback.hidden && feedback.textContent.trim()) {
            showFieldMessage(field, feedback, feedback.textContent.trim());
        }
    }

    for (const field of form.querySelectorAll('[maxlength][data-character-counter]')) {
        bindCharacterLimit(field);
    }

    function updateField(event) {
        const target = event.target;
        const field = fields.find((item) => item === target
            || (item.type === 'radio' && target.type === 'radio' && item.name === target.name));

        if (field) {
            validateField(field, attemptedSubmit);
        }
    }

    form.addEventListener('input', updateField);
    form.addEventListener('change', updateField);
    form.addEventListener('submit', (event) => {
        attemptedSubmit = true;
        const invalidFields = fields.filter((field) => !validateField(field, true, true));

        if (invalidFields.length) {
            event.preventDefault();
            invalidFields[0].focus();
        }
    });

    form.noValidate = true;
}
