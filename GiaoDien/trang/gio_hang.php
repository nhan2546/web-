<div class="cart-page-wrapper">
    <div class="cart-header">
        <h1>Giỏ hàng</h1>
    </div>

    <?php
    if (isset($_SESSION['cart_error'])) {
        echo '<div class="alert alert-danger" style="margin-bottom: 15px;">' . htmlspecialchars($_SESSION['cart_error']) . '</div>';
        unset($_SESSION['cart_error']); // Xóa thông báo sau khi hiển thị
    }
    ?>

    <?php if (empty($cart)): ?>
        <div class="cart-empty-state">
            <img src="TaiNguyen/hinh_anh/empty-cart.png" alt="Giỏ hàng trống">
            <p>Giỏ hàng của bạn đang trống</p>
            <a href="index.php?act=hienthi_sp" class="cp-btn">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form id="cart-form" action="index.php?act=thanh_toan" method="post">
            <div class="cart-layout">
                <!-- Cột trái: Danh sách sản phẩm -->
                <div class="cart-items-column">
                    <div class="cart-select-all">
                        <input type="checkbox" id="select-all-items">
                        <label for="select-all-items">Chọn tất cả (<?= count($cart) ?> sản phẩm)</label>
                    </div>

                    <div class="cart-items-list">
                        <?php foreach ($cart as $item): ?>
                            <div class="cart-item-card" data-id="<?= $item['id'] ?>">
                                <div class="cart-item-selector">
                                    <input type="checkbox" name="selected_items[]" class="item-checkbox" value="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>" data-quantity="<?= $item['quantity'] ?>">
                                </div>
                                <div class="cart-item-image">
                                    <img src="TaiLen/san_pham/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </div>
                                <div class="cart-item-details">
                                    <a href="index.php?act=chi_tiet_san_pham&id=<?= $item['id'] ?>" class="cart-item-name"><?= htmlspecialchars($item['name']) ?></a>
                                    <div class="cart-item-price">
                                        <span class="current-price"><?= number_format($item['price'], 0, ',', '.') ?>₫</span>
                                    </div>
                                </div>
                                <div class="cart-item-actions">
                                    <div class="quantity-selector">
                                        <button type="button" class="quantity-btn minus">-</button>
                                        <input type="number" name="quantities[<?= $item['id'] ?>]" class="quantity-input" value="<?= htmlspecialchars($item['quantity']) ?>" min="1" max="99" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
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
                        <div class="price-details">
                            <div class="price-row">
                                <span>Tạm tính</span>
                                <span id="cart-subtotal">0₫</span>
                            </div>
                        </div>
                        <div class="final-total">
                            <div class="price-row">
                                <span>Tổng cộng</span>
                                <span class="total-price" id="cart-final-total">0₫</span>
                            </div>
                            <small>(Chưa bao gồm phí vận chuyển)</small>
                        </div>
                        <button type="submit" class="cp-btn checkout-btn" disabled>Thanh toán</button>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-items');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const checkoutButton = document.querySelector('.checkout-btn');

    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    }

    function updateSummary() {
        let subtotal = 0;
        let selectedItemsCount = 0;
        itemCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(checkbox.closest('.cart-item-card').querySelector('.quantity-input').value);
                subtotal += price * quantity;
                selectedItemsCount++;
            }
        });

        document.getElementById('cart-subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('cart-final-total').textContent = formatCurrency(subtotal);

        // Enable/disable checkout button
        if (selectedItemsCount > 0) {
            checkoutButton.disabled = false;
        } else {
            checkoutButton.disabled = true;
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSummary();
    });

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else {
                // Check if all items are selected
                const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }
            updateSummary();
        });
    });

    // Also update summary when quantity changes
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', updateSummary);
    });
     document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', updateSummary);
    });


    // Initial summary calculation
    updateSummary();
});
</script>
