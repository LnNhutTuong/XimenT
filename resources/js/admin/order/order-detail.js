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
            statusOrder.disabled = false;

            editBtn.classList.add("hidden");
            deleteBtn.classList.add("hidden");
            saveBtn.classList.remove("hidden");
            cancelBtn.classList.remove("hidden");

            if (cancelBtn) {
                cancelBtn.addEventListener("click", () => {
                    this.resetForm(modal);
                });
            }
        });
    },
    resetForm(modal) {
        const statusOrder = modal.querySelector(".status-order");

        const saveBtn = modal.querySelector(".btn-accept-edit");
        const cancelBtn = modal.querySelector(".btn-cancel-edit");
        const deleteBtn = modal.querySelector(".btn-delete-order");
        const editBtn = modal.querySelector(".btn-edit-order");

        statusOrder.classList.add("bg-gray-100");
        statusOrder.value = statusOrder.dataset.value;
        statusOrder.disabled = true;

        editBtn.classList.remove("hidden");
        deleteBtn.classList.remove("hidden");

        saveBtn.classList.add("hidden");
        cancelBtn.classList.add("hidden");
    },
};

document.addEventListener("DOMContentLoaded", () => {
    OrderDetail.init();
});
