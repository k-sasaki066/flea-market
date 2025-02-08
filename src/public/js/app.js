document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        document.querySelectorAll('.flash_success-message, .flash_error-message').forEach(el => {
            el.style.display = 'none';
        });
    }, 5000);
});