function findConfirmationDialog(trigger) {
    const dialog = document.getElementById(trigger.dataset.confirmDialog);

    return dialog instanceof HTMLDialogElement ? dialog : null;
}

function askForConfirmation(dialog) {
    if (dialog.open) {
        return Promise.resolve(false);
    }

    dialog.returnValue = '';
    dialog.showModal();

    return new Promise((resolve) => {
        dialog.addEventListener('close', () => {
            resolve(dialog.returnValue === 'confirm');
        }, { once: true });
    });
}

document.addEventListener('click', async (event) => {
    const link = event.target instanceof Element && event.target.closest('a[data-confirm-dialog]');

    if (!link || event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
    }

    const dialog = findConfirmationDialog(link);

    if (!dialog) {
        return;
    }

    event.preventDefault();

    if (await askForConfirmation(dialog)) {
        window.location.assign(link.href);
    }
});

const confirmedForms = new WeakSet();

document.addEventListener('submit', async (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || event.defaultPrevented || confirmedForms.has(form)) {
        return;
    }

    const dialog = findConfirmationDialog(form);

    if (!dialog) {
        return;
    }

    event.preventDefault();

    if (!await askForConfirmation(dialog)) {
        return;
    }

    const accepted = new CustomEvent('confirmation:accepted', { cancelable: true });

    if (form.dispatchEvent(accepted)) {
        confirmedForms.add(form);

        try {
            form.requestSubmit(event.submitter ?? undefined);
        } finally {
            confirmedForms.delete(form);
        }
    }
});
