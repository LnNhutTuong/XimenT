const ProductCreate = {
    init() {
        this.initSlugGenerator();
        this.initFormValidation();
        this.initSizeWithCategory();
        this.initGiaYeuThuong();
        this.initPreviewProfile();
        this.initPreviewGallery();
        this.initCKEditor();
    },
    initPreviewGallery() {
        let filesArray = [];
        const imageInput = document.getElementById("gallery_images");
        const previewContainer = document.getElementById(
            "gallery-preview-container",
        );

        if (imageInput) {
            imageInput.addEventListener("change", (e) => {
                const files = Array.from(e.target.files);

                files.forEach((file) => filesArray.push(file));

                renderPreviewGallery();

                imageInput.value = "";
            });
        }

        const renderPreviewGallery = () => {
            previewContainer.innerHTML = "";

            filesArray.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const div = document.createElement("div");
                    div.className = "relative inline-block mr-2";

                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-lg">
                        <button type="button"
                            class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded">
                            x
                        </button>
                    `;

                    div.querySelector("button").addEventListener(
                        "click",
                        () => {
                            filesArray.splice(index, 1);
                            renderPreviewGallery();
                        },
                    );

                    previewContainer.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        };
    },

    initPreviewProfile() {
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

    initGiaYeuThuong() {
        const basePrice = document.getElementById("base_price");
        const sellPrice = document.getElementById("sell_price");
        const discountPercent = document.getElementById("discount_percent");
        const discountAmount = document.getElementById("discount_amount");

        if (!sellPrice || !discountPercent || !discountAmount) return;

        const formatCurrency = (input) => {
            let value = input.value.replace(/\D/g, "");

            if (value !== "") {
                input.value =
                    new Intl.NumberFormat("vi-VN").format(value) + " VNĐ";
            } else {
                input.value = "";
            }
        };

        const formatPercent = (input) => {
            let value = input.value.replace(/\D/g, "");

            if (value !== "") {
                let num = parseInt(value, 10);
                if (num > 100) num = 100;

                input.value = num + " %";
            } else {
                input.value = "";
            }
        };

        const calculateDiscount = () => {
            const priceStr = sellPrice.value.replace(/\./g, "");
            const price = parseFloat(priceStr) || 0;
            const percent = parseFloat(discountPercent.value) || 0;

            if (price > 0) {
                const finalPrice = price - (price * percent) / 100;

                discountAmount.value = new Intl.NumberFormat("vi-VN").format(
                    Math.round(finalPrice),
                );
            } else {
                discountAmount.value = "";
            }
        };

        sellPrice.addEventListener("input", function () {
            formatCurrency(this);
            calculateDiscount();
        });

        discountPercent.addEventListener("input", function () {
            formatPercent(this);
            calculateDiscount();
        });

        discountAmount.addEventListener("input", function () {
            formatCurrency(this);
        });
        basePrice.addEventListener("input", function () {
            formatCurrency(this);
        });
    },

    initSizeWithCategory() {
        const categorySelect = document.getElementById("product_category_id");
        const variantsContainer = document.getElementById("variants");

        let currentOptionSize = `<option value="">Chọn kích thước</option>`;
        let previousCategoryValue = categorySelect ? categorySelect.value : "";

        if (categorySelect) {
            categorySelect.addEventListener("focus", function () {
                previousCategoryValue = this.value;
            });

            categorySelect.addEventListener("change", function () {
                const newCategoryId = this.value;

                if (!variantsContainer) return;

                const allVariantItems =
                    variantsContainer.querySelectorAll(".variant-item");
                const firstSize =
                    allVariantItems[0].querySelector(".size-select").value;
                const hasData = allVariantItems.length > 1 || firstSize !== "";

                if (hasData) {
                    Swal.fire({
                        icon: "warning",
                        title: "Thay đổi danh mục?",
                        text: "Hành động này sẽ làm mới toàn bộ danh sách phân loại bạn đã nhập. Bạn có chắc chắn muốn tiếp tục?",
                        showCancelButton: true,
                        confirmButtonColor: "#000000ff",
                        cancelButtonColor: "#ffffffff",
                        confirmButtonText: "Đồng ý, làm mới",
                        cancelButtonText: "Hủy bỏ",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            previousCategoryValue = newCategoryId;
                            processCategoryChange(newCategoryId);
                            resetVariantsToDefault();
                        } else {
                            categorySelect.value = previousCategoryValue;
                        }
                    });
                } else {
                    previousCategoryValue = newCategoryId;
                    processCategoryChange(newCategoryId);
                }
            });
        }

        const processCategoryChange = (categoryId) => {
            if (categoryId) {
                const hiddenData = document.getElementById(
                    "sizes-for-category-" + categoryId,
                );
                currentOptionSize = hiddenData
                    ? hiddenData.innerHTML
                    : `<option value="">Chọn kích thước</option>`;
            } else {
                currentOptionSize = `<option value="">Chọn kích thước</option>`;
            }
            updateAllSizeSelect();
            updateAvailableSizes();
        };

        const updateAllSizeSelect = () => {
            const sizeSelects = document.querySelectorAll(".size-select");
            sizeSelects.forEach((select) => {
                const selectValue = select.value;
                select.innerHTML = currentOptionSize;

                if (
                    selectValue &&
                    select.querySelector(`option[value="${selectValue}"]`)
                ) {
                    select.value = selectValue;
                }
            });
        };

        const resetVariantsToDefault = () => {
            const allVariantItems =
                variantsContainer.querySelectorAll(".variant-item");
            for (let i = 1; i < allVariantItems.length; i++) {
                allVariantItems[i].remove();
            }
            const firstVariant =
                variantsContainer.querySelector(".variant-item");
            if (firstVariant) {
                firstVariant.querySelector(".size-select").value = "";
                firstVariant.querySelector('input[type="number"]').value = "";
            }
        };

        const updateAvailableSizes = () => {
            const allSelects = document.querySelectorAll(".size-select");

            const selectedSizes = Array.from(allSelects)
                .map((select) => select.value)
                .filter((value) => value !== "");

            allSelects.forEach((select) => {
                const options = select.querySelectorAll("option");

                options.forEach((option) => {
                    if (option.value === "") return;

                    if (
                        selectedSizes.includes(option.value) &&
                        select.value !== option.value
                    ) {
                        option.disabled = true;
                        option.style.display = "none";
                    } else {
                        option.disabled = false;
                        option.style.display = "";
                    }
                });
            });
        };

        if (variantsContainer) {
            variantsContainer.addEventListener("change", (e) => {
                if (e.target.classList.contains("size-select")) {
                    updateAvailableSizes();
                }
            });

            variantsContainer.addEventListener("click", (e) => {
                if (e.target.closest(".btn-add-variant")) {
                    const firstSelect =
                        document.querySelectorAll(".size-select")[0];
                    if (!firstSelect) return;

                    const availableOptionsCount = firstSelect.querySelectorAll(
                        'option:not([value=""])',
                    ).length;
                    const currentRowsCount =
                        variantsContainer.querySelectorAll(
                            ".variant-item",
                        ).length;

                    if (
                        currentRowsCount >= availableOptionsCount &&
                        availableOptionsCount > 0
                    ) {
                        Swal.fire({
                            icon: "info",
                            title: "Đã đạt giới hạn",
                            text: "Bạn đã thêm tất cả các kích thước có sẵn của danh mục này!",
                            confirmButtonColor: "#000000",
                        });
                        return;
                    }

                    const firstVariant =
                        variantsContainer.querySelector(".variant-item");
                    if (!firstVariant) return;

                    const newVariant = firstVariant.cloneNode(true);

                    const inputNumber = newVariant.querySelector(
                        'input[type="number"]',
                    );
                    if (inputNumber) inputNumber.value = "";

                    const inputSize = newVariant.querySelector(".size-select");
                    if (inputSize) inputSize.value = "";

                    variantsContainer.appendChild(newVariant);
                    updateAvailableSizes();
                }

                if (e.target.closest(".btn-remove-variant")) {
                    const allVariantItems =
                        variantsContainer.querySelectorAll(".variant-item");

                    if (allVariantItems.length > 1) {
                        e.target.closest(".variant-item").remove();
                        updateAvailableSizes();
                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: "Không thể xóa",
                            text: "Phải có ít nhất một kích thước (Size)!",
                            confirmButtonColor: "#000000",
                        });
                    }
                }
            });
        }
    },

    initFormValidation() {
        const form = document.getElementById("productForm");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            // ò fin
            // const name = form.querySelector("input[name='name']");
            // if (!name || name.value.trim() === "") {
            //     e.preventDefault();
            //     return showError("Vui lòng nhập tên sản phẩm.");
            // }

            if (typeof CKEDITOR !== "undefined") {
                for (let instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            }

            const description = form.querySelector(
                "textarea[name='description']",
            );
            if (!description || description.value.trim() === "") {
                e.preventDefault();
                return showError("Vui lòng nhập mô tả sản phẩm.");
            }

            const category = form.querySelector(
                "select[name='product_category_id']",
            );
            if (!category || category.value === "") {
                e.preventDefault();
                return showError("Vui lòng chọn danh mục sản phẩm.");
            }

            const brand = form.querySelector("select[name='product_brand_id']");
            if (!brand || brand.value === "") {
                e.preventDefault();
                return showError("Vui lòng chọn thương hiệu sản phẩm.");
            }

            const activeProduct = form.querySelector(
                "input[name='product_status']:checked",
            );
            if (!activeProduct) {
                e.preventDefault();
                return showError("Vui lòng chọn trạng thái sản phẩm.");
            }

            //rice
            const basePrice = form.querySelector("input[name='base_price']");
            if (!basePrice.value.trim()) {
                e.preventDefault();
                return showError("Vui lòng nhập gián nhập");
            }

            const sellPrice = form.querySelector("input[name='sell_price']");
            if (!sellPrice) {
                e.preventDefault();
                return showError("Vui lòng nhập gián bán");
            }

            const imageProduct = form.querySelector("input[name='image']");
            if (!imageProduct || imageProduct.files.length === 0) {
                e.preventDefault();
                return showError("Vui lòng chọn ảnh đại diện cho sản phẩm.");
            }

            const selectSizes = form.querySelectorAll('select[name="sizes[]"]');
            let hasSize = false;
            selectSizes.forEach((select) => {
                if (select.value.trim() !== "") hasSize = true;
            });

            if (!hasSize) {
                e.preventDefault();
                return showError(
                    "Vui lòng chọn ít nhất một kích cỡ (size) cho sản phẩm này.",
                );
            }

            const quantity = form.querySelectorAll(
                "input[name='quantities[]']",
            );
            let hasQuantity = false;
            quantity.forEach((select) => {
                if (select.value.trim() !== "") hasQuantity = true;
            });

            if (!hasQuantity) {
                e.preventDefault();
                return showError("Vui lòng nhập số lượng cho phân loại.");
            }

            function showError(message) {
                Swal.fire({
                    icon: "warning",
                    title: "Thiếu thông tin",
                    text: message,
                    confirmButtonColor: "#000000ff",
                });
            }
        });
    },

    initSlugGenerator() {
        const nameInput = document.getElementById("product_name");
        const slugInput = document.getElementById("product_slug");
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
};

document.addEventListener("DOMContentLoaded", () => {
    ProductCreate.init();
});
