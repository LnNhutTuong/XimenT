const CategoryCreate = {
    init() {
        this.initSlugGenerator();
        this.initSizeAddition();
        this.initFormValidation();
    },

    initSlugGenerator() {
        const nameInput = document.getElementById("category_name");
        const slugInput = document.getElementById("category_slug");
        if (!nameInput || !slugInput) return;

        nameInput.addEventListener("input", function () {
            let name = this.value;
            let slug = name
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/[đĐ]/g, "d")
                .replace(/([^0-9a-z-\s])/g, "")
                .replace(/(\s+)/g, "-")
                .replace(/-+/g, "-")
                .replace(/^-+|-+$/g, "");

            slugInput.value = slug;
        });
    },

    initFormValidation() {
        const form = document.getElementById("categoryForm");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            const selectedSizes = form.querySelectorAll(
                'input[name="sizes[]"]:checked',
            );
            if (selectedSizes.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Thiếu thông tin",
                    text: "Vui lòng chọn ít nhất một kích cỡ (size) cho danh mục này.",
                    confirmButtonColor: "#4f46e5",
                });
            }
        });
    },

    initSizeAddition() {
        const submitSizeBtn = document.getElementById("submit-new-size");
        const sizeNameInput = document.getElementById("size_name");
        const sizeGrid = document.getElementById("size-checkbox-grid");
        const noSizeMessage = document.getElementById("no-size-message");
        const modal = document.getElementById("modal-create-category");

        if (!submitSizeBtn || !modal) return;

        const storeUrl = modal.getAttribute("data-sizes-store-url");
        const csrfToken = document.querySelector('input[name="_token"]')?.value;

        submitSizeBtn.addEventListener("click", async function () {
            const name = sizeNameInput.value.trim();
            if (!name) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: "Vui lòng nhập tên kích cỡ",
                });
                return;
            }

            submitSizeBtn.disabled = true;
            submitSizeBtn.innerHTML =
                '<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            try {
                const response = await fetch(storeUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify({ name: name }),
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: "Thêm kích cỡ thành công",
                    });

                    if (noSizeMessage) noSizeMessage.style.display = "none";
                    if (sizeGrid) sizeGrid.classList.remove("hidden");

                    const newSize = result.data;
                    const label = document.createElement("label");
                    label.className =
                        "group relative flex items-center justify-center p-3 rounded-xl border border-white bg-white hover:border-blue-300 hover:shadow-md transition-all cursor-pointer";
                    label.innerHTML = `
                        <input type="checkbox" name="sizes[]" value="${newSize.id}" class="hidden peer" checked>
                        <span class="text-sm font-bold text-gray-600 peer-checked:text-blue-600 transition-colors">${newSize.name}</span>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-blue-500 rounded-xl pointer-events-none transition-all"></div>
                        <div class="absolute top-1 right-1 opacity-0 peer-checked:opacity-100 transition-opacity">
                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    `;

                    if (sizeGrid) sizeGrid.appendChild(label);
                    sizeNameInput.value = "";
                } else {
                    let errorMsg = result.message || "Có lỗi xảy ra";
                    if (result.errors) {
                        errorMsg = Object.values(result.errors)
                            .flat()
                            .join("\n");
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text: errorMsg,
                    });
                }
            } catch (error) {
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: "Không thể kết nối đến máy chủ",
                });
            } finally {
                submitSizeBtn.disabled = false;
                submitSizeBtn.innerText = "Thêm mới";
            }
        });
    },
};

document.addEventListener("DOMContentLoaded", () => {
    CategoryCreate.init();
});
