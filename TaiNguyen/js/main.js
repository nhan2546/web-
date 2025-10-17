document.addEventListener("DOMContentLoaded", function() {
    // Toggle nav trên mobile
    const burger = document.querySelector('.cp-burger');
    if (burger) {
        burger.addEventListener('click', () => {
            document.querySelector('.cp-header')?.classList.toggle('is-open');
        });
    }

    // Đếm ngược đơn giản
    const cd = document.querySelector('.cp-countdown');
    if (cd) {
        const end = new Date(cd.dataset.end);
        const tick = () => {
            const s = Math.max(0, Math.floor((end - new Date()) / 1000));
            const h = String(Math.floor(s / 3600)).padStart(2, '0');
            const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
            const ss = String(s % 60).padStart(2, '0');
            cd.textContent = `${h}:${m}:${ss}`;
        };
        tick();
        setInterval(tick, 1000);
    }

    // --- LOGIC TRANG THANH TOÁN ---
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const requiredInputs = checkoutForm.querySelectorAll('input[required], textarea[required]');
            let allFieldsFilled = true;

            for (const input of requiredInputs) {
                if (!input.value.trim()) {
                    allFieldsFilled = false;
                    // Thêm một class để làm nổi bật trường bị lỗi (tùy chọn)
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            }

            if (!allFieldsFilled) {
                e.preventDefault(); // Ngăn form được gửi đi
                alert('Vui lòng điền đầy đủ thông tin giao hàng (họ tên, số điện thoại, địa chỉ).');
            }
        });
    }

    // Xử lý nút áp dụng voucher
    const applyVoucherBtn = document.getElementById('apply-voucher-btn');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', function() {
            const voucherInput = document.getElementById('voucher-input');
            const voucherCode = voucherInput.value.trim();

            if (!voucherCode) {
                alert('Vui lòng nhập mã voucher.');
                return;
            }

            // Tạo một form ảo để gửi dữ liệu voucher
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?act=ap_dung_voucher';

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'voucher_code';
            hiddenInput.value = voucherCode;

            form.appendChild(hiddenInput);
            document.body.appendChild(form);
            form.submit();
        });
    }
});