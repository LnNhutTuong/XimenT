const DetailCategory = {
    init() {
        this.initOpenButtons();
        this.initCloseButtons();
        this.initSlugGenerator();
        this.initSizeAddition();
        this.initFormValidation();
        this.initIsEdit();
        this.checkErrors();
    },

    initFormValidation() {
        document.addEventListener("submit", (e) => {
            if (e.target.classList.contains("category-edit-form")) {
                const form = e.target;
                const selectedSizes = form.querySelectorAll(
                    'input[name="sizes[]"]:checked',
                );

                if (selectedSizes.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Thiếu thông tin",
                        text: "Vui lòng chọn ít nhất một kích cỡ (size) cho danh mục này.",
                        confirmButtonColor: "#000000ff",
                    });
                }
            }
        });
    },

    initOpenButtons() {
        document.addEventListener("click", (e) => {
            if (e.target.classList.contains("btn-open-detail")) {
                const targetId = e.target.getAttribute("data-target");
                const modal = document.getElementById(targetId);
                if (modal) this.showModal(modal);
            }
        });
    },

    initIsEdit() {
        document.addEventListener("click", (e) => {
            if (e.target.classList.contains("btn-edit-category")) {
                const modal = e.target.closest(".modal-detail-category");

                const nameCategory = modal.querySelector(
                    ".category-name-input",
                );
                const addNewSize = modal.querySelector(".add-new-size");
                const chooseSize = modal.querySelectorAll(".choose-size");
                const btnAccept = modal.querySelector(".btn-accept-edit");
                const btnCancel = modal.querySelector(".btn-cancel-edit");
                const btnEdit = modal.querySelector(".btn-edit-category");
                const btnDelete = modal.querySelector(".btn-delete-category");

                // enable input
                nameCategory.disabled = false;

                // show edit UI
                addNewSize.classList.remove("hidden");

                chooseSize.forEach((cb) => {
                    cb.disabled = false;
                });

                btnAccept.classList.remove("hidden");
                btnCancel.classList.remove("hidden");

                // hide button edit/delete
                btnEdit.classList.add("hidden");
                btnDelete.classList.add("hidden");
            }
            if (
                e.target.classList.contains("btn-cancel-edit") ||
                e.target.classList.contains("btn-close-detail")
            ) {
                const modal = e.target.closest(".modal-detail-category");
                if (modal) this.resetForm(modal);
            }
        });
    },

    resetForm(modal) {
        const nameCategory = modal.querySelector(".category-name-input");
        const addNewSize = modal.querySelector(".add-new-size");
        const btnAccept = modal.querySelector(".btn-accept-edit");
        const btnCancel = modal.querySelector(".btn-cancel-edit");
        const btnEdit = modal.querySelector(".btn-edit-category");
        const btnDelete = modal.querySelector(".btn-delete-category");
        const chooseSize = modal.querySelectorAll(".choose-size");

        //khoas liasj
        nameCategory.disabled = true;

        // an
        addNewSize.classList.add("hidden");

        chooseSize.forEach((cb) => {
            cb.checked = cb.defaultChecked;

            cb.disabled = true;
        });

        btnAccept.classList.add("hidden");
        btnCancel.classList.add("hidden");

        btnEdit.classList.remove("hidden");
        btnDelete.classList.remove("hidden");
    },

    initCloseButtons() {
        document.addEventListener("click", (e) => {
            if (e.target.closest(".btn-close-detail")) {
                const modal = e.target.closest(".modal-detail-category");
                if (modal) this.hideModal(modal);
            }
        });
    },

    initSlugGenerator() {
        document.addEventListener("input", (e) => {
            if (e.target.classList.contains("category-name-input")) {
                const modal = e.target.closest(".modal-detail-category");
                const slugInput = modal.querySelector(".category-slug-input");

                let name = e.target.value;
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
            }
        });
    },

    initSizeAddition() {
        document.addEventListener("click", async (e) => {
            if (e.target.classList.contains("btn-submit-new-size")) {
                const modal = e.target.closest(".modal-detail-category");
                const sizeNameInput = modal.querySelector(".size-name-input");
                const sizeGrid = modal.querySelector(".size-checkbox-grid");
                const submitBtn = e.target;

                const name = sizeNameInput.value.trim();
                if (!name) {
                    Swal.fire({
                        icon: "warning",
                        title: "Lỗi",
                        text: "Vui lòng nhập tên kích cỡ",
                    });
                    return;
                }

                const storeUrl = modal.getAttribute("data-sizes-store-url");
                const csrfToken = document.querySelector(
                    'input[name="_token"]',
                )?.value;

                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

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
                        sizeGrid.appendChild(label);
                        sizeNameInput.value = "";
                    } else {
                        const errorMsg = result.errors
                            ? Object.values(result.errors).flat().join("\n")
                            : result.message || "Có lỗi xảy ra";
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
                    submitBtn.disabled = false;
                    submitBtn.innerText = "Thêm mới";
                }
            }
        });
    },

    showModal(modal) {
        modal.classList.remove("hidden");
        modal.offsetHeight; // force reflow
        modal.classList.add("opacity-100");
        const content = modal.querySelector(".modal-content");
        content.classList.remove("scale-90", "opacity-0", "translate-y-4");
        content.classList.add("scale-100", "opacity-100", "translate-y-0");
    },

    hideModal(modal) {
        modal.classList.remove("opacity-100");
        const content = modal.querySelector(".modal-content");
        content.classList.remove("scale-100", "opacity-100", "translate-y-0");
        content.classList.add("scale-90", "opacity-0", "translate-y-4");

        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    },
};

document.addEventListener("DOMContentLoaded", () => {
    DetailCategory.init();
});
