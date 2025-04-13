document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.querySelector('.transaction-form__message-input');
    const form = document.querySelector('.transaction-form');

    const transactionId = document.querySelector('[data-transaction-id]')?.dataset.transactionId;
    const storageKey = 'chat_draft_' + transactionId;

    window.addEventListener('load', () => {
        const messageBox = document.getElementById('message-box');
        if (messageBox) {
            messageBox.scrollTo({
                top: messageBox.scrollHeight,
                behavior: 'smooth'
            });
        }
    });

    if (textarea && localStorage.getItem(storageKey)) {
        textarea.value = localStorage.getItem(storageKey);
    }

    if (textarea) {
        textarea.addEventListener('input', function () {
            localStorage.setItem(storageKey, textarea.value);
        });
    }

    if (form) {
        form.addEventListener('submit', function () {
            localStorage.removeItem(storageKey);
        });
    }

    const hash = window.location.hash;
    if (hash && document.querySelector(hash)) {
        const target = document.querySelector(hash);
        target.style.visibility = 'visible';
        target.style.opacity = '1';
    }
});