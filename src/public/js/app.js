document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.form-group, .item-comment__form, .purchase-form, .sell-form, .transaction-form').forEach(form => {
        const button = form.querySelector('.form-btn');
        const iconButton = form.querySelector('.transaction-form__btn');

        if (button) {
            form.addEventListener("submit", function () {
                button.disabled = true;
                button.textContent = "処理中...";
            });
        }

        if (iconButton) {
            form.addEventListener("submit", function () {
                iconButton.disabled = true;
                iconButton.innerHTML = '<span>処理中...</span>';
            });
        }
    });

    const resendForm = document.getElementById("resendForm");
    const resendButton = document.getElementById("resendButton");

    if (resendForm && resendButton) {
        resendForm.addEventListener("submit", function () {
            resendButton.disabled = true;
            resendButton.textContent = "送信中...";
        });
    }

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