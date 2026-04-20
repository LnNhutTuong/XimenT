const OrderDetail = {
    init() {
        this.handleEditMode();
    },

    handleEditMode() {
        document.addEventListener("click", (e) => {
            const editBtn = e.target.closest(".btn-edit-order");
            if (!editBtn) return;

            const modal = editBtn.closest(".detail-order-modal-container");

            const statusOrder = modal.querySelector(".status-order");

            const saveBtn = modal.querySelector(".btn-accept-edit");
            const cancelBtn = modal.querySelector(".btn-cancel-edit");
            const deleteBtn = modal.querySelector(".btn-delete-order");

            statusOrder.classList.remove("bg-gray-100");
            statusOrder.readOnly = false;

            editBtn.classList.add("hidden");
            deleteBtn.classList.add("hidden");
            saveBtn.classList.remove("hidden");
            cancelBtn.classList.remove("hidden");
        });
    },
    //     const form = document.getElementById("createOrderForm");
    //     if (!form) return;

    //     form.addEventListener("submit", (e) => {
    //         const orderItems = document.querySelector(".order-items-body");
    //         if (orderItems.children.length === 0) {
    //             e.preventDefault();
    //             return showError("Vui lòng thêm sản phẩm vào đơn hàng!");
    //         }

    //         function showError(message) {
    //             Swal.fire({
    //                 icon: "warning",
    //                 title: "Thiếu thông tin",
    //                 text: message,
    //                 confirmButtonColor: "#000000ff",
    //             });
    //         }
    //     });
    // },
};

document.addEventListener("DOMContentLoaded", () => {
    OrderDetail.init();
});
