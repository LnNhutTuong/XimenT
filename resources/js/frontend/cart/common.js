document.addEventListener("DOMContentLoaded", () => {
    const radios = document.querySelectorAll('input[name="payment_method"]');

    radios.forEach((radio) => {
        radio.addEventListener("change", chooseTypePayment);
    });

    chooseTypePayment();
});

function chooseTypePayment() {
    const vnpay = document.getElementById("vnpay");
    const instruction = document.getElementById("payment-vnpay-instruction");

    if (vnpay.checked) {
        instruction.classList.remove("hidden");
    } else {
        instruction.classList.add("hidden");
    }
}

function formValidate() {
    const checkoutForm = document.getElementById("checkout-form");
    if (checkoutForm) {
        checkoutForm.addEventListener("submit", function (e) {
            const phone = document
                .getElementById("shipping_phone")
                .value.trim();
            const address = document
                .getElementById("shipping_address")
                .value.trim();

            if (!phone || !address) {
                e.preventDefault(); // Ngăn form gửi đi
                Swal.fire({
                    icon: "warning",
                    title: "Thiếu thông tin!",
                    text: "Vui lòng nhập đầy đủ Số điện thoại và Địa chỉ nhận hàng để tiếp tục.",
                    confirmButtonColor: "#1e293b",
                    confirmButtonText: "Đã hiểu",
                });
            }

            if (phone.length !== 10) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Số điện thoại không hợp lệ!",
                    text: "Vui lòng nhập đúng số điện thoại.",
                    confirmButtonColor: "#1e293b",
                    confirmButtonText: "Đã hiểu",
                });
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    formValidate();
    const trigger = document.getElementById("order-success-trigger");
    if (trigger) {
        const orderCode = trigger.dataset.code;
        const productUrl = trigger.dataset.productUrl;

        Swal.fire({
            title: '<span class="text-slate-800 font-bold uppercase tracking-wider">Thanh toán thành công</span>',
            html: `
                <div class="py-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-50 rounded-full mb-6">
                        <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-gray-600 text-lg mb-2">Mã hóa đơn của bạn:</p>
                    <p class="text-3xl font-black text-indigo-600 mb-6 font-mono tracking-tighter">#${orderCode}</p>
                    
                    <div class="mx-auto max-w-xs p-4 bg-slate-50 border border-slate-100 rounded-lg text-sm text-slate-500 leading-relaxed italic">
                        <span class="font-bold text-slate-700 not-italic">Lưu ý:</span> Nếu thực hiện chọn thanh toán qua mã QR thì nhớ thực hiện theo hướng dẫn.
                    </div>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: "Tiếp tục mua sắm",
            confirmButtonColor: "#1e293b",
            background: "#ffffff",
            padding: "2rem",
            customClass: {
                popup: "rounded-md shadow-2xl border-0",
                confirmButton:
                    "rounded-md px-10 py-3 text-xs font-bold uppercase tracking-[0.2em]",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = productUrl;
            }
        });
    }
});
