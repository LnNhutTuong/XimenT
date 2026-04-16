const ProductDetail = {
    init() {
        const detailForms = document.querySelectorAll(
            'form[id^="productForm-"]',
        );

        detailForms.forEach((form) => {
            this.initSlugGenerator(form);
            this.initFormValidation(form);
            this.initSizeWithCategory(form);
            this.initGiaYeuThuong(form);
            this.initGiaYeuThuongToPercent(form);
            this.initPreviewProfile(form);
            this.initPreviewGallery(form);
            this.initEditMode(form);
        });
    },

    initPreviewGallery(container) {
        let filesArray = [];
        const imageInput = container.querySelector("#gallery_images");
        const previewContainer = container.querySelector(
            "#gallery-preview-container",
        );

        //cai nay dung cho viec co the chon trung anh
        const syncFilesToInput = () => {
            const dataTransfer = new DataTransfer();
            filesArray.forEach((file) => dataTransfer.items.add(file));
            imageInput.files = dataTransfer.files;
        };

        if (imageInput) {
            imageInput.addEventListener("change", (e) => {
                const files = Array.from(e.target.files);

                files.forEach((file) => {
                    filesArray.push(file);
                });

                renderPreviewGallery();
                syncFilesToInput();
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
                            filesArray.splice(index, 1); //splice: xoa 1 phan tu tai vi tri index
                            renderPreviewGallery();
                            syncFilesToInput();
                        },
                    );

                    previewContainer.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        };
    },

    initPreviewProfile(container) {
        const imageInput = container.querySelector(".product-image-input");
        const imagePreview = container.querySelector(".product-image-preview");
        const imagePlaceHolder = container.querySelector(".image-placeholder");

        if (imageInput && imagePreview) {
            imageInput.addEventListener("change", (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove("hidden");
                        if (imagePlaceHolder)
                            imagePlaceHolder.classList.add("hidden");
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    },

    initEditMode(container) {
        container
            .closest(".detail-product-modal")
            .addEventListener("click", (e) => {
                const modal = e.target.closest(".detail-product-modal");

                if (e.target.classList.contains("btn-edit-product")) {
                    //quá cao siêu
                    const variantsContainer = modal.querySelector("#variants");
                    if (variantsContainer) {
                        container._originalVariantsHTML =
                            variantsContainer.innerHTML;
                    }

                    modal
                        .querySelectorAll("input, select, textarea")
                        .forEach((input) => {
                            if (
                                !input.classList.contains("discount-amount") &&
                                !input.id.includes("slug")
                            ) {
                                input.disabled = false;
                                input.classList.remove("bg-gray-50");
                            }
                        });

                    const descriptionTextarea = modal.querySelector(
                        "textarea[name='description']",
                    );
                    if (
                        descriptionTextarea &&
                        typeof CKEDITOR !== "undefined"
                    ) {
                        const editorInstance =
                            CKEDITOR.instances[descriptionTextarea.id];
                        if (editorInstance) {
                            editorInstance.setReadOnly(false);
                        }
                    }

                    modal
                        .querySelectorAll(
                            ".btn-add-variant, .btn-remove-variant",
                        )
                        .forEach((btn) => {
                            btn.classList.remove(
                                "pointer-events-none",
                                "opacity-20",
                            );
                        });

                    modal
                        .querySelector(".btn-accept-edit")
                        ?.classList.remove("hidden");
                    modal
                        .querySelector(".btn-cancel-edit")
                        ?.classList.remove("hidden");
                    modal
                        .querySelector(".btn-edit-product")
                        ?.classList.add("hidden");
                    modal
                        .querySelector(".btn-delete-product")
                        ?.classList.add("hidden");
                }

                if (e.target.classList.contains("btn-cancel-edit")) {
                    const form = modal.querySelector("form");

                    const variantsContainer = modal.querySelector("#variants");
                    if (container._originalVariantsHTML) {
                        variantsContainer.innerHTML =
                            container._originalVariantsHTML;
                    }

                    form.reset();

                    modal
                        .querySelectorAll("input, select, textarea")
                        .forEach((input) => {
                            input.disabled = true;
                            input.classList.add("bg-gray-50");
                        });

                    //cái này chịu thua =))
                    const descriptionTextarea = modal.querySelector(
                        "textarea[name='description']",
                    );
                    if (
                        descriptionTextarea &&
                        typeof CKEDITOR !== "undefined"
                    ) {
                        const editorInstance =
                            CKEDITOR.instances[descriptionTextarea.id];
                        if (editorInstance) {
                            editorInstance.setReadOnly(true);
                        }
                    }
                    modal
                        .querySelectorAll(
                            ".btn-add-variant, .btn-remove-variant",
                        )
                        .forEach((btn) => {
                            btn.classList.add(
                                "pointer-events-none",
                                "opacity-20",
                            );
                        });

                    this.initGiaYeuThuongToPercent(container);

                    modal
                        .querySelector(".btn-accept-edit")
                        ?.classList.add("hidden");
                    modal
                        .querySelector(".btn-cancel-edit")
                        ?.classList.add("hidden");
                    modal
                        .querySelector(".btn-edit-product")
                        ?.classList.remove("hidden");
                    modal
                        .querySelector(".btn-delete-product")
                        ?.classList.remove("hidden");
                }
            });
    },

    parseCurrency(value) {
        return parseFloat(value.replace(/\D/g, "")) || 0;
    },

    formatCurrency(value) {
        return (
            new Intl.NumberFormat("vi-VN").format(Math.round(value)) + " VNĐ"
        );
    },

    initGiaYeuThuongToPercent(container) {
        const sellPriceInput = container.querySelector(".sell-price");
        const discountAmountInput = container.querySelector(".discount-amount");
        const percentInput = container.querySelector(".discount-percent");

        if (!sellPriceInput || !discountAmountInput || !percentInput) return;

        const sellPrice = this.parseCurrency(sellPriceInput.value);
        const discountPrice = this.parseCurrency(discountAmountInput.value);

        if (sellPrice > 0) {
            const percent = ((sellPrice - discountPrice) / sellPrice) * 100;
            percentInput.value = Math.round(percent) + " %";
        }
    },

    initGiaYeuThuong(container) {
        const basePrice = container.querySelector(".base-price");
        const sellPrice = container.querySelector(".sell-price");
        const discountPercent = container.querySelector(".discount-percent");
        const discountAmount = container.querySelector(".discount-amount");

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

        const calculatePercent = () => {
            const priceStr = sellPrice.value.replace(/\./g, "");
            const price = parseFloat(priceStr) || 0;
            const percent = parseFloat(discountPercent.value) || 0;

            if (price) {
                percent = ((price - discountAmount.value) / price) * 100;
                discountPercent.value = percent + " %";
            }
        };

        sellPrice.addEventListener("input", function () {
            formatCurrency(this);
            calculateDiscount();
        });

        discountPercent.addEventListener("input", function () {
            formatPercent(this);
            calculateDiscount();
            calculatePercent();
        });

        discountAmount.addEventListener("input", function () {
            formatCurrency(this);
            calculatePercent();
        });
        basePrice.addEventListener("input", function () {
            formatCurrency(this);
        });
    },

    initSizeWithCategory(container) {
        const categorySelect = container.querySelector("#product_category_id");
        const variantsContainer = container.querySelector("#variants");

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
            const sizeSelects = container.querySelectorAll(".size-select");
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
            const allSelects = container.querySelectorAll(".size-select");

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
                        container.querySelectorAll(".size-select")[0];
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

        // Khởi tạo giá trị ban đầu nếu đã có category được chọn
        if (categorySelect && categorySelect.value) {
            processCategoryChange(categorySelect.value);
        }
    },

    initFormValidation(container) {
        const form = container;

        form.addEventListener("submit", function (e) {
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

            // Validate Prices
            const basePrice = form.querySelector("input[name='base_price']");
            if (!basePrice || !basePrice.value.trim()) {
                e.preventDefault();
                return showError("Vui lòng nhập giá nhập.");
            }

            const sellPrice = form.querySelector("input[name='sell_price']");
            if (!sellPrice || !sellPrice.value.trim()) {
                e.preventDefault();
                return showError("Vui lòng nhập giá bán.");
            }

            const galleryImages = form.querySelector(
                "input[name='gallery_images[]']",
            );
            if (galleryImages && galleryImages.files.length > 5) {
                e.preventDefault();
                return showError(
                    "Vui lòng chọn tối đa 5 ảnh chi tiết cho sản phẩm.",
                );
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

    initSlugGenerator(container) {
        const nameInput = container.querySelector("#product_name");
        const slugInput = container.querySelector("#product_slug");
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
    ProductDetail.init();
});
