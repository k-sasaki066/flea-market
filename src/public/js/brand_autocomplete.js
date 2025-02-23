document.addEventListener("DOMContentLoaded", function () {
    const brandInput = document.querySelector("#brand_name");
    const dataList = document.querySelector("#brand-list");

    function fetchBrands(query = "") {
        fetch(`/api/brands?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                // console.log("取得したデータ:", data);
                dataList.innerHTML = ""; // 一度クリア

                // if (data.length === 0) {
                //     console.warn("⚠️ ブランドが見つかりませんでした");
                //     return;
                // }

                data.forEach(brand => {
                    let option = document.createElement("option");
                    option.value = brand.name;
                    dataList.appendChild(option);
                });
            })
            .catch(error => console.error("❌ APIの取得エラー:", error));
    }

    fetchBrands();

    brandInput.addEventListener("input", function () {
        if (this.value === "") {
            fetchBrands(); // 入力が空なら全ブランド表示
        } else {
            fetchBrands(this.value);
        }
    });
});