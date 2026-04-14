const DetailBrand = {
    init() {
        this.initOpenButtons();
        this.initCloseButtons();
        this.initSlugGenerator();
        this.initFormValidation();
        this.initIsEdit();
        this.initPreviewImage();
        this.checkErrors();
    },

    initFormValidation() {
        document.addEventListener("submit", function (e) {
            if (e.target && e.target.classList.contains("brand-edit-form")) {
                const nameInput = e.target.querySelector('input[name="name"]');

                if (nameInput && !nameInput.value.trim()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Thiếu thông tin",
                        text: "Vui lòng nhập tên thương hiệu.",
                        confirmButtonColor: "#000000",
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
            if (e.target.classList.contains("btn-edit-brand")) {
                const modal = e.target.closest(".modal-detail-brand");
                if (!modal) return;

                const nameInput = modal.querySelector(".brand-name-input");
                const imageInput = modal.querySelector(".image-input");
                const btnAccept = modal.querySelector(".btn-accept-edit");
                const btnCancel = modal.querySelector(".btn-cancel-edit");
                const btnEdit = modal.querySelector(".btn-edit-brand");
                const btnDelete = modal.querySelector(".btn-delete-brand");

                if (nameInput) nameInput.disabled = false;
                if (imageInput) imageInput.disabled = false;

                if (btnAccept) btnAccept.classList.remove("hidden");
                if (btnCancel) btnCancel.classList.remove("hidden");

                    if (btnEdit) btnEdit.classList.add("hidden");
                if (btnDelete) btnDelete.classList.add("hidden");
            }

            if (e.target.classList.contains("btn-cancel-edit")) {
                const modal = e.target.closest(".modal-detail-brand");
                if (modal) this.resetForm(modal);
            }
        });
    },

    resetForm(modal) {
        const nameInput = modal.querySelector(".brand-name-input");
        const btnAccept = modal.querySelector(".btn-accept-edit");
        const btnCancel = modal.querySelector(".btn-cancel-edit");
        const btnEdit = modal.querySelector(".btn-edit-brand");
        const btnDelete = modal.querySelector(".btn-delete-brand");
        const imageInput = modal.querySelector('input[type="file"]');

        // Lock input
        if (nameInput) {
            nameInput.disabled = true;
            nameInput.value = nameInput.defaultValue;
        }
        if (imageInput) imageInput.disabled = true;

        // Hide edit UI
        if (btnAccept) btnAccept.classList.add("hidden");
        if (btnCancel) btnCancel.classList.add("hidden");

        // Show button edit/delete
        if (btnEdit) btnEdit.classList.remove("hidden");
        if (btnDelete) btnDelete.classList.remove("hidden");
    },

    initPreviewImage() {
        document.addEventListener("change", (e) => {
            if (e.target.matches('.modal-detail-brand input[type="file"]')) {
                const modal = e.target.closest(".modal-detail-brand");
                const previewImg = modal.querySelector(".preview-img");
                const placeholder = modal.querySelector(".image-placeholder");
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (previewImg) {
                            previewImg.src = e.target.result;
                            previewImg.classList.remove("hidden");
                        }
                        if (placeholder) placeholder.classList.add("hidden");
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    },

    initCloseButtons() {
        document.addEventListener("click", (e) => {
            if (e.target.closest(".btn-close-detail")) {
                const modal = e.target.closest(".modal-detail-brand");
                if (modal) {
                    this.hideModal(modal);
                    this.resetForm(modal);
                }
            }
        });
    },

    initSlugGenerator() {
        document.addEventListener("input", (e) => {
            if (e.target.classList.contains("brand-name-input")) {
                const modal = e.target.closest(".modal-detail-brand");
                if (!modal) return;

                const slugInput = modal.querySelector(".brand-slug-input");
                if (!slugInput) return;

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

    checkErrors() {
        const modal = document.querySelector(
            '.modal-detail-brand[data-has-errors="true"]',
        );
        if (modal) {
            this.showModal(modal);
        }
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
    DetailBrand.init();
});
