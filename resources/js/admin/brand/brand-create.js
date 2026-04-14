const BrandCreate = {
    init() {
        this.initSlugGenerator();
        this.initFormValidation();
        this.initPreviewImage();
    },

    initSlugGenerator() {
        const nameInput = document.getElementById("brand_name");
        const slugInput = document.getElementById("brand_slug");
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
        document.addEventListener("submit", function (e) {
            if (e.target && e.target.id === "brandForm") {
                const logo = e.target.querySelector('input[name="image"]');
                
                if (logo && logo.files.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Thiếu thông tin",
                        text: "Vui lòng chọn ảnh đại diện cho thương hiệu.",
                        confirmButtonColor: "#000000ff",
                    });
                }
            }
        });
    },

    initPreviewImage() {
        const imageInput = document.getElementById("image");
        const imagePreview = document.getElementById("preview-img");
        const imagePlaceHolder = document.getElementById("image-placeholder");

        if (imageInput) {
            imageInput.addEventListener("change", (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove("hidden");
                        imagePlaceHolder.classList.add("hidden");
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add("hidden");
                    imagePlaceHolder.classList.remove("hidden");
                }
            });
        }
    },
};

document.addEventListener("DOMContentLoaded", () => {
    BrandCreate.init();
});
