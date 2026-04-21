document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.getElementById("filter-form");
    const categorySelect = document.getElementById("category");
    const brandSelect = document.getElementById("brand");
    const sortSelect = document.getElementById("sort");
    const searchInput = document.getElementById("search-input");

    function autoSubmit() {
        filterForm.submit();
    }

    categorySelect.addEventListener("change", autoSubmit);
    brandSelect.addEventListener("change", autoSubmit);
    sortSelect.addEventListener("change", autoSubmit);

    searchInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            autoSubmit();
        }
    });
});
