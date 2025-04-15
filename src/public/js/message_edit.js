document.addEventListener("DOMContentLoaded", function () {
    const updateButtons = document.querySelectorAll('.transaction-message__update-btn');
    const cancelButtons = document.querySelectorAll('.transaction-cancel-edit__btn');
    const editingId = sessionStorage.getItem('editing_message_id');

    function getMessageElements(wrapper) {
        return {
            wrapper,
            messageText: wrapper.querySelector('.transaction-message__text'),
            messageForm: wrapper.querySelector('.transaction-message__form-group'),
            editForm: wrapper.querySelector('.transaction-edit-form'),
        };
    }

    // 編集モードに切り替える
    function showEditForm(elements) {
        elements.messageText.style.display = 'none';
        elements.messageForm.style.display = 'none';
        elements.editForm.style.display = 'block';
    }

    // 通常表示に戻す
    function hideEditForm(elements) {
        elements.messageText.style.display = 'block';
        elements.messageForm.style.display = 'flex';
        elements.editForm.style.display = 'none';
    }

    // ページロード時：編集状態を復元 or 完了後に閉じる
    if (editingId) {
        const wrapper = document.querySelector(`.transaction-message__self-wrap[data-message-id="${editingId}"]`);
        if (wrapper) {
            const elements = getMessageElements(wrapper);
            const updateStatus = document.getElementById('message-update-status');
            if (updateStatus && updateStatus.dataset.updated === 'true') {
                hideEditForm(elements);
                sessionStorage.removeItem('editing_message_id');
            } else {
                showEditForm(elements);
            }
        }
    }

    // 編集ボタンが押された時
    updateButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const wrapper = button.closest('.transaction-message__self-wrap');
            const messageId = wrapper.dataset.messageId;
            const elements = getMessageElements(wrapper);

            sessionStorage.setItem('editing_message_id', messageId);
            showEditForm(elements);
        });
    });

    // キャンセルボタンが押された時
    cancelButtons.forEach(button => {
        button.addEventListener('click', function () {
            const wrapper = button.closest('.transaction-message__self-wrap');
            const elements = getMessageElements(wrapper);

            sessionStorage.removeItem('editing_message_id');
            hideEditForm(elements);
        });
    });
});