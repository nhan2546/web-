<div class="checkout-page-wrapper">
    <h1>Thanh Toán</h1>

    <form action="index.php?act=xu_ly_dat_hang" method="POST" class="checkout-form">
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
                            <input type="radio" name="payment_method" value="cod" checked>
                            <img src="TaiNguyen/hinh_anh/icon/cod.svg" alt="COD" class="payment-method-icon">
                            <div class="payment-method-content">
                                <strong>Thanh toán khi nhận hàng (COD)</strong>
                                <p>Bạn sẽ thanh toán bằng tiền mặt cho nhân viên giao hàng.</p>
                            </div>
                        </label>

                        <!-- Chuyển khoản ngân hàng -->
                        <label class="payment-method-item">
                            <input type="radio" name="payment_method" value="bank_transfer">
                            <img src="TaiNguyen/hinh_anh/icon/bank.svg" alt="Bank Transfer" class="payment-method-icon">
                            <div class="payment-method-content">
                                <strong>Chuyển khoản ngân hàng</strong>
                                <p>Chúng tôi sẽ cung cấp thông tin chuyển khoản sau khi bạn đặt hàng.</p>
                            </div>
                        </label>

                        <!-- Ví MoMo -->
                        <label class="payment-method-item">
                            <input type="radio" name="payment_method" value="momo">                            
                            <img src="TaiNguyen/hinh_anh/icon/momo.svg" alt="MoMo" class="payment-method-icon">
                            <div class="payment-method-content">
                                <strong>Thanh toán qua ví MoMo</strong>
                                <p>Quét mã QR MoMo để thanh toán nhanh chóng và tiện lợi.</p>
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
                        <form action="index.php?act=ap_dung_voucher" method="POST" class="voucher-form">
                            <input type="text" name="voucher_code" placeholder="Nhập mã giảm giá" class="voucher-input" value="<?= htmlspecialchars($voucher_code ?? '') ?>">
                            <button type="submit" class="voucher-apply-btn">Áp dụng</button>
                        </form>
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
                </div>
            </div>
        </div>

        <div class="checkout-actions">
            <a href="index.php?act=gio_hang" class="cp-btn-secondary">&larr; Quay lại giỏ hàng</a>
            <button type="submit" class="cp-btn">Hoàn tất đặt hàng</button>
        </div>
    </form>
</div>