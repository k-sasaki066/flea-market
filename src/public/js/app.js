document.addEventListener("DOMContentLoaded", function () {
    // setTimeout(() => {
    //     document.querySelectorAll('.flash_success-message, .flash_error-message').forEach(el => {
    //         el.style.display = 'none';
    //     });
    // }, 5000);

    setTimeout(() => {
        document.querySelectorAll('.flash_success-message, .flash_error-message').forEach(el => {
            el.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-20px)'; // 上にスライドさせながら消す
            setTimeout(() => {
                el.style.display = 'none';
            }, 500); // フェードアウト完了後に非表示
        });
    }, 5000);
});