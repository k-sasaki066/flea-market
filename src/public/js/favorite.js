document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".favorite-btn").forEach(button => {
        button.addEventListener("click", function () {
            let itemId = this.dataset.itemId;
            let icon = this.querySelector(".favorite-count__img");
            let countSpan = this.closest(".favorite-count__group").querySelector(".favorite-count__text");
            let isLiked = icon.src.endsWith("star-yellow.svg");

            fetch(isLiked ? `/unlike/:${itemId}` : `/like/:${itemId}`, {
                method: isLiked ? "DELETE" : "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("HTTP error! Status: " + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (isLiked) {
                    icon.src = "/images/star.svg";
                    countSpan.textContent = Math.max(0, parseInt(countSpan.textContent) - 1);
                } else {
                    icon.src = "/images/star-yellow.svg";
                    countSpan.textContent = parseInt(countSpan.textContent) + 1;
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});