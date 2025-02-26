document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".favorite-btn").forEach(button => {
        button.addEventListener("click", async function () {
            const itemId = this.dataset.itemId;
            const icon = this.querySelector(".favorite-count__img");
            const countSpan = this.closest(".favorite-count__group").querySelector(".favorite-count__text");
            const isLiked = icon.src.endsWith("star-yellow.svg");
            const url = isLiked ? `/unlike/${itemId}` : `/like/${itemId}`;
            const method = isLiked ? "DELETE" : "POST";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            this.disabled = true;

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();

                if (isLiked) {
                    icon.src = "/images/star.svg";
                    countSpan.textContent = Math.max(0, parseInt(countSpan.textContent) - 1);
                } else {
                    icon.src = "/images/star-yellow.svg";
                    countSpan.textContent = parseInt(countSpan.textContent) + 1;
                }
            } catch (error) {
                console.error("お気に入り処理エラー:", error);
                alert("エラーが発生しました。通信状況を確認してください。");
            } finally {
                this.disabled = false;
            }
        });
    });
});