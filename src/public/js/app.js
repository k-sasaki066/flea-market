document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.form-group, .item-comment__form, .purchase-form, sell-form').forEach(form => {
        const button = form.querySelector('.form-btn');

        form.addEventListener("submit", function () {
            button.disabled = true;
            button.textContent = "処理中...";
        });
    });

    setTimeout(() => {
        document.querySelectorAll('.flash_success-message, .flash_error-message').forEach(el => {
            el.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                el.style.display = 'none';
            }, 500);
        });
    }, 5000);
});