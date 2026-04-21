const OrderCreate = {
    init() {
        this.handleChooseTypeCustomers();
        this.handleAddOrderDetail();
        this.handleFormValidate();
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

            const orderItems = document.getElementById("order-items-body");
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

    handleChooseTypeCustomers() {
        const typeSelect = document.getElementById("choose-type-customers");
        const userSelect = document.getElementById("user-id");
        const guestInput = document.getElementById("guest-name");
        const phoneInput = document.getElementById("customer_phone");
        const addressInput = document.getElementById("customer_address");

        const updateFields = () => {
            const isUser = typeSelect.value === "user";

            document.querySelector(".user").classList.toggle("hidden", !isUser);
            document
                .querySelector(".non-user")
                .classList.toggle("hidden", isUser);
            userSelect.disabled = !isUser;
            guestInput.disabled = isUser;

            if (isUser) {
                const selected = userSelect.options[userSelect.selectedIndex];
                phoneInput.value = selected.dataset.phone || "";
                addressInput.value = selected.dataset.address || "";
                phoneInput.readOnly = true;
                addressInput.readOnly = true;
                phoneInput.classList.add("bg-gray-100");
                addressInput.classList.add("bg-gray-100");
            } else {
                phoneInput.readOnly = false;
                addressInput.readOnly = false;

                // guestInput.value = "";
                phoneInput.value = "";
                addressInput.value = "";

                phoneInput.classList.remove("bg-gray-100");
                addressInput.classList.remove("bg-gray-100");
            }
        };

        typeSelect.addEventListener("change", updateFields);
        userSelect.addEventListener("change", updateFields);

        updateFields();
    },

    handleAddOrderDetail() {
        const orderBody = document.getElementById("order-items-body");
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
                    this.updateTotalOrder();
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
                this.updateTotalOrder();
            }
            if (btnRemove) {
                btnRemove.closest("tr").remove();
                this.updateTotalOrder();
            }
        });

        orderBody.addEventListener("change", (e) => {
            if (e.target.tagName === "INPUT" && e.target.type === "number") {
                const input = e.target;
                const quantityOrder = parseInt(input.value); // so luong mua
                const row = input.closest("tr");

                const quantity = parseInt(row.dataset.quantity); // số lưowng kho

                if (quantityOrder <= 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Không thể sửa",
                        text: "Số lượng đặt hàng phải lớn hơn 0!",
                        confirmButtonColor: "#000000",
                    });
                    input.value = 1;
                    this.updateRowTotal(row); // reset về 1
                    this.updateTotalOrder();
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
                    this.updateTotalOrder();
                    return;
                }
                this.updateRowTotal(row);
                this.updateTotalOrder();
            }
        });
    },

    addTableRow(orderBody, id, name, size, price, quantity, stock) {
        const total = price * quantity;
        const html = `
        <tr class="border-b" data-variant-id="${id}" data-quantity="${stock}" data-price="${price}">
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
                <button type="button" class="btn-remove-order-detail text-red-500 hover:text-red-700">
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
        const quantity = parseInt(
            row.querySelector('input[type="number"]').value,
        );
        const totalPriceProdut = row.querySelector(".row-total");

        const newTotal = price * quantity;
        totalPriceProdut.innerText =
            new Intl.NumberFormat("vi-VN").format(newTotal) + " VNĐ";
    },

    updateTotalOrder() {
        const orderBody = document.getElementById("order-items-body");

        const totalPriceOrderInput =
            document.getElementById("total-price-input");
        const totalPriceOrderDisplay = document.getElementById(
            "total-price-display",
        );

        const totalQuantityOrderInput = document.getElementById(
            "total-quantity-input",
        );
        const totalQuantityOrderDisplay = document.getElementById(
            "total-quantity-display",
        );

        const allRows = Array.from(orderBody.querySelectorAll("tr"));

        let totalQty = 0;
        let totalCash = 0;

        allRows.forEach((row) => {
            const qty =
                parseInt(row.querySelector('input[type="number"]').value) || 0;
            const price = parseFloat(row.dataset.price) || 0;
            totalQty += qty;
            totalCash += qty * price;
        });

        totalQuantityOrderDisplay.innerText = totalQty;
        totalPriceOrderDisplay.innerText =
            new Intl.NumberFormat("vi-VN").format(totalCash) + "đ";

        totalQuantityOrderInput.value = totalQty;
        totalPriceOrderInput.value = totalCash;
    },
};

document.addEventListener("DOMContentLoaded", () => {
    OrderCreate.init();
});
