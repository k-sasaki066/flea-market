document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("imageInput");
    const imageSelectBtn = document.getElementById("imageSelectBtn");
    const imagePreviewContainer = document.getElementById("imagePreviewContainer");

    imageInput.addEventListener("change", () => {
        const file = imageInput.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreviewContainer.innerHTML = `
                    <div style="position: relative; display: inline-block;">
                        <img src="${e.target.result}" alt="プレビュー画像" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc; margin-top: 10px;">
                        <button type="button" id="removeImageBtn" style="position: absolute; top: 4px; right: -4px; background: #fff; color:rgb(76, 76, 76); border: 2px solid rgb(76, 76, 76); border-radius: 50%; width: 24px; height: 24px; cursor: pointer;display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1;">×</button>
                    </div>
                `;

                // 削除ボタンのイベントを追加
                document.getElementById("removeImageBtn").addEventListener("click", function () {
                    imagePreviewContainer.innerHTML = "";
                    imageInput.value = ""; // ファイル選択もリセット
                });
            };
            reader.readAsDataURL(file);
        } else {
            imagePreviewContainer.innerHTML = "";
        }
    });
});