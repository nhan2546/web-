<div class="checkout-page-wrapper">
    <h1>Thanh Toán</h1>

    <?php if (!empty($order_error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 15px;">
            <strong>Đã có lỗi hệ thống nghiêm trọng xảy ra!</strong><br>
            <p>Chúng tôi không thể xử lý đơn hàng của bạn vào lúc này. Vui lòng liên hệ với quản trị viên để được hỗ trợ.</p>
            <small><i>Chi tiết lỗi: <?= htmlspecialchars($order_error) ?></i></small>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'order_failed'): ?>
        <div class="alert alert-danger" style="margin-bottom: 15px;">
            <strong>Đã có lỗi xảy ra!</strong> Không thể xử lý đơn hàng của bạn vào lúc này. Điều này có thể do một sản phẩm trong giỏ hàng đã hết hàng. Vui lòng kiểm tra lại giỏ hàng và thử lại.
        </div>
    <?php endif; ?>

    <form action="index.php?act=xu_ly_dat_hang" method="POST" class="checkout-form" id="checkout-form">
        <div class="checkout-grid">
            <!-- Cột bên trái: Thông tin giao hàng và thanh toán -->
            <div class="checkout-left">
                <div class="checkout-section">
                    <h3>1. Thông tin giao hàng</h3>
                    <div class="form-group">
                        <label for="fullname">Họ và tên người nhận</label>
                        <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($user_info['fullname'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Số điện thoại</label>
                        <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user_info['phone_number'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ nhận hàng</label>
                        <textarea id="address" name="address" rows="3" required><?= htmlspecialchars($user_info['address'] ?? '') ?></textarea>
                        <small>Vui lòng điền địa chỉ chi tiết (số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố).</small>
                    </div>
                </div>

                <div class="checkout-section">
                    <h3>2. Phương thức thanh toán</h3>
                    <div class="payment-methods">
                        <!-- Thanh toán khi nhận hàng (COD) -->
                        <label class="payment-method-item">
                            <img src="TaiNguyen/hinh_anh/cod-credit-debit-bank-transaction-32259.webp" alt="COD" class="payment-method-icon">
                            <div class="payment-method-content">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <strong>Thanh toán khi nhận hàng (COD)</strong>
                                <p>Bạn sẽ thanh toán bằng tiền mặt cho nhân viên giao hàng.</p>
                            </div>
                        </label>

                        <!-- Chuyển khoản ngân hàng -->
                        <label class="payment-method-item">
                            <img src="TaiNguyen/hinh_anh/images.png" alt="Bank Transfer" class="payment-method-icon">
                            <div class="payment-method-content">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <strong>Chuyển khoản ngân hàng</strong>
                                <p>Chúng tôi sẽ cung cấp thông tin chuyển khoản sau khi bạn đặt hàng.</p>
                            </div>
                        </label>

                        <!-- Ví MoMo -->
                        <label class="payment-method-item">
                            <img src="TaiNguyen/hinh_anh/tải xuống.png" alt="MoMo" class="payment-method-icon">
                            <div class="payment-method-content">
                                <input type="radio" name="payment_method" value="momo">
                                <strong>Thanh toán qua ví MoMo</strong>
                                <p>Quét mã QR MoMo để thanh toán nhanh chóng và tiện lợi.</p>
                            </div>                            
                        </label>

                        <!-- Ví VNPay -->
                        <label class="payment-method-item">
                            <img src="TaiNguyen/hinh_anh/tải xuống (1).png" alt="VNPay" class="payment-method-icon">
                            <div class="payment-method-content">
                                <input type="radio" name="payment_method" value="vnpay">
                                <strong>Thanh toán qua VNPay-QR</strong>
                                <p>Thanh toán bằng ứng dụng ngân hàng và các ví điện tử hỗ trợ VNPay.</p>
                            </div>
                        </label>

                        <!-- Thẻ quốc tế -->
                        <label class="payment-method-item">
                            <img src="TaiNguyen/hinh_anh/tải xuống (2).jpg" alt="Credit Card" class="payment-method-icon">
                            <div class="payment-method-content">
                                <input type="radio" name="payment_method" value="credit_card">
                                <strong>Thẻ quốc tế Visa, Master, JCB</strong>
                                <div class="card-logos">
                                    <img src="TaiNguyen/hinh_anh/tải xuống (2).png" alt="Visa">
                                    <img src="TaiNguyen/hinh_anh/tải xuống (1).jpg" alt="Mastercard">
                                    <img src="TaiNguyen/hinh_anh/tải xuống (3).png" alt="JCB">
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cột bên phải: Tóm tắt đơn hàng -->
            <div class="checkout-right">
                <div class="checkout-section order-summary">
                    <h3>Tóm tắt đơn hàng</h3>
                    <div class="order-items">
                        <?php foreach ($cart as $item): ?>
                            <div class="order-item">
                                <input type="checkbox" name="selected_items[]" value="<?= $item['id'] ?>" checked style="margin-right: 15px;">
                                <img src="TaiLen/san_pham/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <div class="item-info">
                                    <p><?= htmlspecialchars($item['name']) ?></p>
                                    <p>SL: <?= (int)$item['quantity'] ?></p>
                                </div>
                                <div class="item-price">
                                    <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Voucher Section -->
                    <div class="voucher-section" style="padding: 20px; border-top: 1px solid #e5e5e5;">
                        <div class="voucher-form">
                            <input type="text" name="voucher_code" id="voucher-input" placeholder="Nhập mã giảm giá" class="voucher-input" value="<?= htmlspecialchars($voucher_code ?? '') ?>">
                            <button type="button" id="apply-voucher-btn" class="voucher-apply-btn">Áp dụng</button>
                        </div>
                        <?php if (isset($voucher_error) && $voucher_error): ?>
                            <div class="voucher-message error"><?= htmlspecialchars($voucher_error) ?></div>
                        <?php endif; ?>
                        <?php if (isset($voucher_success) && $voucher_success): ?>
                            <div class="voucher-message success"><?= htmlspecialchars($voucher_success) ?></div>
                        <?php endif; ?>
                        <?php if ($discount_amount > 0): ?>
                            <div class="voucher-message success">
                                Đã áp dụng mã <strong><?= htmlspecialchars($voucher_code) ?></strong>. 
                                <a href="index.php?act=xoa_voucher" class="remove-voucher-btn">[Xóa]</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="order-totals">
                        <div class="total-row">
                            <span>Tạm tính</span>
                            <span><?= number_format($subtotal, 0, ',', '.') ?>₫</span>
                        </div>
                        <?php if ($discount_amount > 0): ?>
                            <div class="total-row voucher-applied">
                                <span>Giảm giá (Voucher)</span>
                                <span>- <?= number_format($discount_amount, 0, ',', '.') ?>₫</span>
                            </div>
                        <?php endif; ?>
                        <div class="total-row final-total">
                            <span>Tổng cộng</span>
                            <span><?= number_format($final_total, 0, ',', '.') ?>₫</span>
                        </div>
                    </div>
                    <!-- Nút hoàn tất đặt hàng được chuyển vào đây -->
                    <div class="summary-actions">
                        <button type="submit" class="cp-btn">Hoàn tất đặt hàng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-actions">
            <a href="index.php?act=gio_hang" class="cp-btn-secondary">&larr; Quay lại giỏ hàng</a>
        </div>
    </form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const itemCheckboxes = form.querySelectorAll('input[name="selected_items[]"]');
    const subtotalEl = document.querySelector('.order-totals .total-row:first-child span:last-child');
    const finalTotalEl = document.querySelector('.order-totals .final-total span:last-child');
    const voucherDiscountEl = document.querySelector('.order-totals .voucher-applied span:last-child');

    const initialDiscount = <?= $discount_amount ?? 0 ?>;
    const cartData = <?= json_encode($cart) ?>;

    function updateTotals() {
        let newSubtotal = 0;
        itemCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const itemId = checkbox.value;
                const item = cartData[itemId];
                if (item) {
                    newSubtotal += item.price * item.quantity;
                }
            }
        });

        // Tính toán lại giảm giá ở phía client để hiển thị (tính toán cuối cùng vẫn ở server)
        let newDiscount = 0;
        if (newSubtotal > 0 && initialDiscount > 0) {
            // Giả định đơn giản: nếu tổng tiền nhỏ hơn mức giảm, mức giảm sẽ bằng tổng tiền.
            // Logic phức tạp hơn (VD: giá trị đơn hàng tối thiểu) được xử lý ở server.
            newDiscount = Math.min(newSubtotal, initialDiscount);
        } else {
            // Nếu không có sản phẩm nào được chọn, không giảm giá.
            newDiscount = 0;
        }

        const newFinalTotal = newSubtotal - newDiscount;

        // Cập nhật giao diện
        subtotalEl.textContent = newSubtotal.toLocaleString('vi-VN') + '₫';
        if (voucherDiscountEl) {
            voucherDiscountEl.textContent = '- ' + newDiscount.toLocaleString('vi-VN') + '₫';
        }
        finalTotalEl.textContent = newFinalTotal.toLocaleString('vi-VN') + '₫';
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotals);
    });

    // Xử lý khi submit form
    form.addEventListener('submit', function(event) {
        let selectedCount = 0;
        itemCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
            }
        });

        if (selectedCount === 0) {
            // Ngăn form gửi đi nếu không có sản phẩm nào được chọn
            event.preventDefault();
            alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }
        // Nếu có sản phẩm được chọn, form sẽ được gửi đi bình thường.
    });

    // Tính toán tổng tiền lần đầu khi tải trang
    updateTotals();
});
</script>
</div>