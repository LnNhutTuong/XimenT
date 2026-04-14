const CategoryModal = {
    init(modalId, openBtnId, closeBtnIds, modalContentClass) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const openModalBtn = document.getElementById(openBtnId);
        const modalContent = modal.querySelector(`.${modalContentClass}`);

        const showModal = () => {
            modal.classList.remove("hidden");
            modal.offsetHeight;
            modal.classList.add("opacity-100");
            modalContent.classList.remove(
                "scale-90",
                "opacity-0",
                "translate-y-4",
            );
            modalContent.classList.add(
                "scale-100",
                "opacity-100",
                "translate-y-0",
            );
        };

        const hideModal = () => {
            modal.classList.remove("opacity-100");
            modalContent.classList.remove(
                "scale-100",
                "opacity-100",
                "translate-y-0",
            );
            modalContent.classList.add(
                "scale-90",
                "opacity-0",
                "translate-y-4",
            );

            setTimeout(() => {
                modal.classList.add("hidden");
            }, 300);
        };

        window.showCategoryModal = showModal;
        window.hideCategoryModal = hideModal;

        if (openModalBtn) openModalBtn.addEventListener("click", showModal);

        closeBtnIds.forEach((id) => {
            const btn = document.getElementById(id);
            if (btn) btn.addEventListener("click", hideModal);
        });

        if (modal.getAttribute("data-has-errors") === "true") {
            showModal();
        }
    },
};

document.addEventListener("DOMContentLoaded", () => {
    CategoryModal.init(
        "modal-create-product",
        "open-create-product",
        ["close-create-product", "closeButton"],
        "modal-content",
    );
});
