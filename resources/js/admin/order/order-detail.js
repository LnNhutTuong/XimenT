const OrderDetail = {
    init() {
        this.handleAddOrderDetail();
        this.handleEditMode();
    },

    handleEditMode() {
        document.addEventListener("click", (e) => {
            const editBtn = e.target.closest(".btn-edit-order");
            if (!editBtn) return;

            const modal = editBtn.closest(".detail-order-modal-container");

            const statusOrder = modal.querySelector(".status-order");

            const orderItems = modal.querySelector("#order-detail-of-user");
            const removeItem = modal.querySelectorAll(
                ".btn-remove-order-detail",
            );

            const productListInStock = modal.querySelector(
                "#product-list-in-stock",
            );
            const quantityInputs = modal.querySelectorAll(
                ".input-item-quantity",
            );

            const saveBtn = modal.querySelector(".btn-accept-edit");
            const cancelBtn = modal.querySelector(".btn-cancel-edit");
            const deleteBtn = modal.querySelector(".btn-delete-order");

            statusOrder.classList.remove("bg-gray-100");
            statusOrder.readOnly = false;

            orderItems.classList.remove("w-full");
            orderItems.classList.add("w-1/2");

            productListInStock.classList.remove("hidden");

            removeItem.forEach((item) => {
                item.classList.remove("opacity-20");
                item.disabled = false;
            });

            quantityInputs.forEach((input) => {
                input.readOnly = false;

                const tr = input.closest("tr");
                const variantId = tr.dataset.variantId;
                input.name = `items[${variantId}][quantity]`;
            });

            this.updateTotalOrder(modal);

            editBtn.classList.add("hidden");
            deleteBtn.classList.add("hidden");
            saveBtn.classList.remove("hidden");
            cancelBtn.classList.remove("hidden");
        });
    },

    handleFormValidate() {
        const form = document.getElementById("createOrderForm");
        if (!form) return;

        form.addEventListener("submit", (e) => {
            const type = document.getElementById("choose-type-customers").value;
            if (type === "guest") {
                const guestName = form.querySelector(
                    "input[name='guest-name']",
                );
                if (!guestName.value.trim()) {
                    e.preventDefault();
                    return showError("Vui lòng nhập tên khách hàng vãng lai!");
                }
            }

            const orderItems = document.querySelector(".order-items-body");
            if (orderItems.children.length === 0) {
                e.preventDefault();
                return showError("Vui lòng thêm sản phẩm vào đơn hàng!");
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

    handleAddOrderDetail() {
        const orderBody = document.querySelector(".order-items-body");
        if (!orderBody) return;

        document.addEventListener("click", (e) => {
            const btnAdd = e.target.closest(".btn-add-order-detail");
            const btnRemove = e.target.closest(".btn-remove-order-detail");

            if (btnAdd) {
                const row = btnAdd.closest("tr");

                //lay data attribute
                const variantId = btnAdd.dataset.variantId;
                const productName = btnAdd.dataset.productName;
                const size = btnAdd.dataset.size;
                const price = btnAdd.dataset.price;
                const quantity = btnAdd.dataset.quantity; // số lượng kho

                const modal = btnAdd.closest(".detail-order-modal-container");

                //lay row
                const quantityInput = row.querySelector(
                    ".input-quantity-buy",
                ).value;
                const quantityToOrdder = parseInt(quantityInput);

                if (quantityToOrdder <= 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể thêm",
                        text: "Số lượng đặt hàng phải lớn hơn 0!",
                        confirmButtonColor: "#000000",
                    });
                    return;
                }

                if (quantityToOrdder > quantity) {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể thêm",
                        text: "Số lượng đặt hàng không được vượt quá số lượng tồn kho!",
                        confirmButtonColor: "#000000",
                    });

                    row.querySelector(".input-quantity-buy").value = quantity;
                    this.updateTotalOrder(modal);
                    return;
                }

                const existingRow = orderBody.querySelector(
                    `tr[data-variant-id="${variantId}"]`,
                );
                if (existingRow) {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể thêm",
                        text: "Sản phẩm này đã có trong đơn hàng. Bạn có thể chỉnh sửa số lượng trực tiếp tại bảng đơn hàng.",
                        confirmButtonColor: "#000000",
                    });

                    return;
                }

                this.addTableRow(
                    orderBody,
                    variantId,
                    productName,
                    size,
                    price,
                    quantityToOrdder,
                    quantity,
                );
                this.updateTotalOrder(modal);
            }
            if (btnRemove) {
                const currentModal = btnRemove.closest(
                    ".detail-order-modal-container",
                );
                btnRemove.closest("tr").remove();
                this.updateTotalOrder(currentModal);
            }
        });
        orderBody.addEventListener("change", (e) => {
            if (e.target.tagName === "INPUT" && e.target.type === "number") {
                const input = e.target;
                const quantityOrder = parseInt(input.value); // so luong mua
                const row = input.closest("tr");

                const quantity = parseInt(row.dataset.quantity); // số lưowng kho
                const modal = input.closest(".detail-order-modal-container");

                if (quantityOrder <= 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể sửa",
                        text: "Số lượng đặt hàng phải lớn hơn 0!",
                        confirmButtonColor: "#000000",
                    });
                    input.value = 1;
                    this.updateRowTotal(row); // reset về 1
                    this.updateTotalOrder(modal);
                    return;
                }

                if (quantityOrder > quantity) {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể thêm",
                        text: `Chỉ còn ${quantity} sản phẩm trong kho`,
                        confirmButtonColor: "#000000",
                    });

                    input.value = quantity;
                    this.updateRowTotal(row); // reset về quantity
                    this.updateTotalOrder(modal);
                    return;
                }

                this.updateRowTotal(row);
                this.updateTotalOrder(modal);
            }
        });
    },

    addTableRow(orderBody, id, name, size, price, quantity, stock) {
        const total = price * quantity;
        const html = `<tr class="border-b" data-variant-id="${id}" data-quantity="${stock}" data-price="${price}">
            <td class="px-8 py-2 text-center">
                <div class="font-semibold">${name}</div>
                <div class="text-xs text-blue-500">Size: ${size}</div>
            </td>
            <td class="px-8 py-2 text-center">
                <input type="number" 
                    name="items[${id}][quantity]" 
                    value="${quantity}" 
                    min="1" 
                    class="w-16 border rounded text-center">
            </td>
            <td class="px-8 py-2 text-center row-total">
                ${new Intl.NumberFormat("vi-VN").format(total)}đ
            </td>
            <input type="hidden" name="items[${id}][variant_id]" value="${id}">
            <td class="px-8 py-2 text-center">
                <button type="button" class="btn-remove-order-detail hover:text-red-700">
                    <svg class="size-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
            </td>
        </tr>`;
        orderBody.insertAdjacentHTML("beforeend", html);
    },

    updateRowTotal(row) {
        const price = parseFloat(row.dataset.price);
        const qtyInput =
            row.querySelector(".input-item-quantity") ||
            row.querySelector('input[type="number"]');
        const quantity = parseInt(qtyInput.value) || 0;

        const totalPriceProduct = row.querySelector(".row-total");
        const newTotal = price * quantity;

        totalPriceProduct.innerText =
            new Intl.NumberFormat("vi-VN").format(newTotal) + " VNĐ";
    },
    updateTotalOrder(modal) {
        const orderBody = modal.querySelector(".order-items-body");

        const totalQtyDisplay = modal.querySelector(".total-quantity-display");
        const totalPriceDisplay = modal.querySelector(".total-price-display");
        const allRows = Array.from(orderBody.querySelectorAll("tr"));

        let totalQty = 0;
        let totalCash = 0;

        allRows.forEach((row) => {
            const qtyInput = row.querySelector('input[type="number"]');
            const qty = qtyInput ? parseInt(qtyInput.value) || 0 : 0;

            const price = parseFloat(row.dataset.price || 0);

            totalQty += qty;
            totalCash += qty * price;
        });

        if (totalQtyDisplay) {
            totalQtyDisplay.innerText = totalQty;
        }

        if (totalPriceDisplay) {
            totalPriceDisplay.innerText =
                new Intl.NumberFormat("vi-VN").format(totalCash) + " VNĐ";
        }
    },
};

document.addEventListener("DOMContentLoaded", () => {
    OrderDetail.init();
});
