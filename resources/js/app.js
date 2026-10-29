const parser = new DOMParser();
const scrollKey = 'lantera:scroll';

async function fetchDocument(url, options = {}) {
    const response = await fetch(url, {
        credentials: 'same-origin',
        headers: { Accept: 'text/html' },
        ...options,
    });

    if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
    }

    const doc = parser.parseFromString(await response.text(), 'text/html');
    if (!doc.querySelector('[data-sync="quote-grid"]')) {
        throw new Error('Unexpected response');
    }

    doc.responseUrl = response.url;
    return doc;
}

function replaceShared(doc, key) {
    const current = document.querySelector(`[data-sync="${key}"]`);
    const fresh = doc.querySelector(`[data-sync="${key}"]`);
    if (current && fresh) {
        current.replaceWith(fresh);
    }
}

function refreshFeed(doc) {
    ['feed-toolbar', 'quote-grid', 'pagination'].forEach((key) => replaceShared(doc, key));
}

function closePopovers(root) {
    root.querySelectorAll('[popover]').forEach((popover) => {
        if (popover.matches(':popover-open')) {
            popover.hidePopover();
        }
    });
}

function swapTarget(targetId, doc, focusSelector, mode) {
    const current = document.getElementById(targetId);
    if (!current) {
        return;
    }

    closePopovers(current);
    const fresh = doc.getElementById(targetId);

    if (mode === 'grid' || !fresh) {
        refreshFeed(doc);
        return;
    }

    current.replaceWith(fresh);
    replaceShared(doc, 'quote-count');
    const focusTarget = focusSelector ? fresh.querySelector(focusSelector) : null;
    focusTarget?.focus({ preventScroll: true });
}

function setBusy(targetId, isBusy) {
    const target = document.getElementById(targetId);
    if (target) {
        target.toggleAttribute('aria-busy', isBusy);
    }
}

function showInlineError(form, doc) {
    const alert = doc.querySelector('.form-alert');
    form.querySelector('.form-alert')?.remove();
    if (alert) {
        alert.classList.remove('mb-5');
        form.prepend(alert);
    }
}

document.addEventListener('submit', async (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.dataset.swap || event.defaultPrevented) {
        return;
    }

    event.preventDefault();
    const targetId = form.dataset.swap;
    const keepOnError = form.hasAttribute('data-keep-on-error');
    setBusy(targetId, true);

    try {
        const doc = await fetchDocument(form.action, { method: 'POST', body: new URLSearchParams(new FormData(form)) });

        if (keepOnError && doc.querySelector('.form-alert')) {
            setBusy(targetId, false);
            showInlineError(form, doc);
            return;
        }

        swapTarget(targetId, doc, form.dataset.focus, form.dataset.swapMode);
    } catch {
        form.submit();
    }
});

document.addEventListener('submit', async (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-create') || event.defaultPrevented) {
        return;
    }

    event.preventDefault();
    const submit = form.querySelector('[type="submit"]');
    submit?.setAttribute('disabled', '');

    try {
        const doc = await fetchDocument(form.action, { method: 'POST', body: new URLSearchParams(new FormData(form)) });
        submit?.removeAttribute('disabled');

        if (doc.querySelector('.form-alert')) {
            showInlineError(form, doc);
            return;
        }

        refreshFeed(doc);
        if (doc.responseUrl) {
            history.replaceState(null, '', new URL(doc.responseUrl).pathname + new URL(doc.responseUrl).search);
        }
        form.closest('[popover]')?.hidePopover();
        const firstCard = document.querySelector('[data-sync="quote-grid"] .quote-card');
        firstCard?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstCard?.classList.add('is-new');
    } catch {
        form.submit();
    }
});

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (form instanceof HTMLFormElement && form.hasAttribute('data-keep-scroll')) {
        try {
            sessionStorage.setItem(scrollKey, String(window.scrollY));
        } catch {
        }
    }
});

window.addEventListener('DOMContentLoaded', () => {
    let saved = null;
    try {
        saved = sessionStorage.getItem(scrollKey);
        sessionStorage.removeItem(scrollKey);
    } catch {
    }

    if (saved !== null && !location.hash) {
        window.scrollTo({ top: Number(saved), behavior: 'instant' });
    }
});

function updateCompose(form) {
    const input = form.querySelector('[data-compose-input]');
    if (!input) {
        return;
    }

    const max = Number(input.getAttribute('maxlength')) || 155;
    const length = input.value.length;
    const counter = form.querySelector('[data-compose-counter]');
    const progress = form.querySelector('[data-compose-progress]');
    const preview = form.closest('.compose-dialog')?.querySelector('[data-compose-preview]');

    if (counter) {
        counter.textContent = `${length} / ${max}`;
        counter.classList.toggle('is-near', length > max - 15);
    }

    if (progress) {
        progress.style.width = `${Math.min(100, (length / max) * 100)}%`;
    }

    if (preview) {
        const text = input.value.trim();
        preview.textContent = text ? input.value : preview.dataset.empty;
        preview.classList.toggle('is-empty', !text);
    }
}

document.addEventListener('input', (event) => {
    const form = event.target instanceof Element ? event.target.closest('[data-compose]') : null;
    if (form) {
        updateCompose(form);
    }
});

document.addEventListener('toggle', (event) => {
    const dialog = event.target;
    if (!(dialog instanceof HTMLElement) || !dialog.matches('.compose-dialog')) {
        return;
    }

    const form = dialog.querySelector('[data-compose]');
    if (!form) {
        return;
    }

    if (event.newState === 'open') {
        updateCompose(form);
        const input = form.querySelector('[data-compose-input]');
        input?.focus({ preventScroll: true });
        input?.setSelectionRange(input.value.length, input.value.length);
    } else {
        form.reset();
        form.querySelector('.form-alert')?.remove();
        updateCompose(form);
    }
}, true);

document.querySelectorAll('[data-compose]').forEach(updateCompose);
