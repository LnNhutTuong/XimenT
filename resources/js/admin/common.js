function initTableFilter(tbodyId, searchId, statusId) {
    const tbody = document.getElementById(tbodyId);
    const searchInput = document.getElementById(searchId);
    const statusSelect = document.getElementById(statusId);

    if (!tbody) return;

    const applyFilters = () => {
        const searchText = searchInput
            ? searchInput.value.toLowerCase().trim()
            : "";
        const statusValue = statusSelect ? statusSelect.value : "";
        const rows = tbody.querySelectorAll("tr");

        rows.forEach((row) => {
            const rowText = row.innerText.toLowerCase();
            const rowStatus = row.getAttribute("data-status") || ""; //lay attribute ben

            const matchesSearch = rowText.includes(searchText);
            const matchesStatus =
                statusValue === "" || rowStatus === statusValue;

            row.style.display = matchesSearch && matchesStatus ? "" : "none";
        });
    };

    if (searchInput) {
        searchInput.addEventListener("keyup", applyFilters);
    }

    if (statusSelect) {
        statusSelect.addEventListener("change", applyFilters);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    initTableFilter("product-tbody", "search-input", "status-filter");
    initTableFilter("category-tbody", "search-input", "status-filter");
    initTableFilter("brand-tbody", "search-input", "status-filter");
    initTableFilter("order-tbody", "search-input", "status-filter");
});
