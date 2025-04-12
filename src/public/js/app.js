document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.form-group, .item-comment__form, .purchase-form, .sell-form, .transaction-form, .transaction-edit-form, .rating-form').forEach(form => {
        const button = form.querySelector('.form-btn');
        const iconButton = form.querySelector('.transaction-form__btn');
        const editButton = form.querySelector('.transaction-edit__btn');
        const ratingButton = form.querySelector('.rating-btn');

        form.addEventListener("submit", function () {
            if (button) {
                button.disabled = true;
                button.textContent = "処理中...";
            }

            if (iconButton) {
                iconButton.disabled = true;
                iconButton.innerHTML = '<span style="font-size: 12px;">処理中...</span>';
            }

            if (editButton) {
                editButton.disabled = true;
                editButton.textContent = "更新中...";
            }

            if (ratingButton) {
                ratingButton.disabled = true;
                ratingButton.textContent = "送信中...";
            }
        });
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