function filterTable(inputId, tbodyId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener("keyup", function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll(`#${tbodyId} tr`);

        rows.forEach((row) => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("search-input")) {
        const categoryTable = document.getElementById("category-tbody");
        const productTable = document.getElementById("product-tbody");

        if (categoryTable) filterTable("search-input", "category-tbody");
        if (productTable) filterTable("search-input", "product-tbody");
    }
});
