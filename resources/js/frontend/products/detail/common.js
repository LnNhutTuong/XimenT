// ĐỔI ẢNH
const changeImage = (src) => {
    const mainImg = document.getElementById("main-product-image");
    if (!mainImg) return;
    mainImg.style.opacity = "0.5";
    setTimeout(() => {
        mainImg.src = src;
        mainImg.style.opacity = "1";
    }, 150);
};
window.changeImage = changeImage;

// CHỌN SIZE VÀ CẬP NHẬT GIAO DIỆN
let selectedVariantId = null;

document.querySelectorAll(".p-size-input").forEach((input) => {
    input.addEventListener("change", function () {
        selectedVariantId = this.value;

        // Bổ sung: Gán Variant ID vào input ẩn của Form để gửi đi
        const hiddenVariantInput = document.getElementById(
            "selected-variant-id",
        );
        if (hiddenVariantInput) {
            hiddenVariantInput.value = selectedVariantId;
        }

        // Cập nhật giá
        const actualPriceEl = document.getElementById("display-actual-price");
        const oldPriceEl = document.getElementById("display-old-price");

        if (actualPriceEl && oldPriceEl) {
            const discountPrice = this.dataset.discountPrice;
            const originalPrice = this.dataset.price;

            if (discountPrice) {
                // Nếu có giá giảm
                actualPriceEl.innerText = discountPrice;
                actualPriceEl.classList.add("text-red-600");
                actualPriceEl.classList.remove("text-slate-700");
                
                oldPriceEl.innerText = originalPrice;
                oldPriceEl.classList.remove("hidden");
            } else {
                // Nếu không có giá giảm
                actualPriceEl.innerText = originalPrice;
                actualPriceEl.classList.remove("text-red-600");
                actualPriceEl.classList.add("text-slate-700");
                
                oldPriceEl.classList.add("hidden");
            }
        }

        // Đổi màu Label
        document.querySelectorAll(".size-label").forEach((label) => {
            label.classList.remove(
                "border-slate-800",
                "bg-black",
                "text-white",
            );
            label.classList.add(
                "border-slate-200",
                "bg-white",
                "text-slate-800",
            );
        });
        const parentLabel = this.parentElement;
        parentLabel.classList.remove(
            "border-slate-200",
            "bg-white",
            "text-slate-800",
        );
        parentLabel.classList.add("border-slate-800", "bg-black", "text-white");
    });
});

// CẬP NHẬT SỐ LƯỢNG
function updateQty(delta) {
    const input = document.getElementById("quantity");
    if (!input) return;
    let newVal = parseInt(input.value) + delta;
    if (newVal < 1) newVal = 1;
    input.value = newVal;
}
window.updateQty = updateQty;

// VALIDATE BẮT BUỘC CHỌN SIZE KHI SUBMIT FORM
document.addEventListener("DOMContentLoaded", () => {
    const cartForm = document.getElementById("add-to-cart-form"); // Bắt theo ID của Form
    if (cartForm) {
        cartForm.addEventListener("submit", function (e) {
            const variantIdVal = document.getElementById(
                "selected-variant-id",
            ).value;
            if (!variantIdVal) {
                e.preventDefault(); // Chặn không cho form gửi đi
                Swal.fire({
                    icon: "error",
                    title: "Vui lòng chọn kích thước trước khi thêm vào giỏ hàng!",
                    iconColor: "#000000",
                    confirmButtonColor: "#000000",
                });
            }
        });
    }
});
