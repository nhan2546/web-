<div class="cart-page-wrapper">
    <div class="cart-header">
        <h1>Giỏ hàng</h1>
    </div>

    <?php if (empty($cart)): ?>
        <div class="cart-empty-state">
            <img src="TaiNguyen/hinh_anh/empty-cart.png" alt="Giỏ hàng trống">
            <p>Giỏ hàng của bạn đang trống</p>
            <a href="index.php?act=hienthi_sp" class="cp-btn">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <!-- Cột trái: Danh sách sản phẩm -->
            <div class="cart-items-column">
                <div class="cart-items-list">
                    <?php foreach ($cart as $item): ?>
                        <div class="cart-item-card" data-id="<?= $item['id'] ?>">
                            <div class="cart-item-image">
                                <img src="TaiLen/san_pham/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="cart-item-details">
                                <a href="index.php?act=chi_tiet_san_pham&id=<?= $item['id'] ?>" class="cart-item-name"><?= htmlspecialchars($item['name']) ?></a>
                                <div class="cart-item-price">
                                    <span class="current-price"><?= number_format($item['price'], 0, ',', '.') ?>₫</span>
                                    <!-- <del class="old-price">12.000.000₫</del> -->
                                </div>
                            </div>
                            <div class="cart-item-actions">
                                <div class="quantity-selector">
                                    <button type="button" class="quantity-btn minus">-</button>
                                    <input type="number" name="quantities[<?= $item['id'] ?>]" class="quantity-input" value="<?= htmlspecialchars($item['quantity']) ?>" min="0" max="99" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
                                    <button type="button" class="quantity-btn plus">+</button>
                                </div>
                                <span class="cart-item-total-price">
                                    <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫
                                </span>
                                <a href="index.php?act=xoa_san_pham_gio_hang&id=<?= $item['id'] ?>" class="cart-item-delete" title="Xóa sản phẩm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16"><path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt đơn hàng -->
            <div class="cart-summary-column">
                <div class="order-summary-box">
                    <h4>Tóm tắt đơn hàng</h4>

                    <!-- Chi tiết giá -->
                    <div class="price-details">
                        <div class="price-row">
                            <span>Tạm tính</span>
                            <span id="cart-subtotal"><?= number_format($subtotal, 0, ',', '.') ?>₫</span>
                        </div>                        
                    </div>

                    <!-- Tổng cuối cùng -->
                    <div class="final-total">
                        <div class="price-row">
                            <span>Tổng cộng</span>
                            <span class="total-price" id="cart-final-total"><?= number_format($subtotal, 0, ',', '.') ?>₫</span>
                        </div>
                        <small>(Đã bao gồm VAT nếu có)</small>
                    </div>

                    <a href="index.php?act=thanh_toan" class="cp-btn checkout-btn">Tiến hành thanh toán</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>