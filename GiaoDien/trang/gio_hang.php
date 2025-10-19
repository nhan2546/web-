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
            <p>Chưa có sản phẩm nào trong giỏ hàng</p>
            <a href="index.php?act=hienthi_sp" class="cp-btn">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form id="cart-form" action="index.php?act=thanh_toan" method="post">
            <div class="cart-layout">
                <!-- Cột trái: Danh sách sản phẩm -->
                <div class="cart-items-column">
                    <div class="cart-select-all"> 
                        <label class="custom-checkbox">
                            <input type="checkbox" id="select-all-items"> 
                            <span class="checkmark"></span> 
                            Chọn tất cả (<?= count($cart) ?> sản phẩm) 
                        </label> 
                    </div>
 
                    <div class="cart-items-list">
                        <?php foreach ($cart as $item): ?>
                            <div class="cart-item-card" data-id="<?= $item['id'] ?>">
                                <div class="cart-item-selector">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="selected_items[]" class="item-checkbox" value="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>" data-quantity="<?= $item['quantity'] ?>">
                                        <span class="checkmark"></span>
                                    </label>
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
                                        <button type="button" class="quantity-btn minus" aria-label="Giảm số lượng">-</button>
                                        <input type="number" name="quantities[<?= $item['id'] ?>]" class="quantity-input" value="<?= htmlspecialchars($item['quantity']) ?>" min="1" max="99" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
                                        <button type="button" class="quantity-btn plus" aria-label="Tăng số lượng">+</button>
                                    </div>
                                    <span class="cart-item-total-price" data-item-total-id="<?= $item['id'] ?>">
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
                        <!-- Thanh tiến trình miễn phí vận chuyển -->
                        <div class="free-shipping-progress-box">
                            <p class="message">...đang tính...</p>
                            <div class="free-shipping-progress-bar">
                                <div class="free-shipping-progress-bar-fill" style="width: 0%;"></div>
                            </div>
                        </div>

                        <!-- Mã giảm giá -->
                        <form action="index.php?act=ap_dung_voucher" method="POST" class="voucher-section">
                            <div class="voucher-form">
                                <input type="text" name="voucher_code" placeholder="Nhập mã giảm giá" class="voucher-input" value="<?= htmlspecialchars($voucher_code ?? '') ?>">
                                <button type="submit" class="voucher-apply-btn">Áp dụng</button>
                            </div>
                        </form>
                        <h4>Tóm tắt đơn hàng</h4>
                        <div class="price-details">
                            <div class="price-row">
                                <span>Tạm tính</span>
                                <span id="cart-subtotal">0₫</span>
                            </div>
                        </div>
                        <?php if ($discount_amount > 0): ?>
                        <div class="price-row discount">
                            <span>Giảm giá (Voucher)</span>
                            <span>- <?= number_format($discount_amount, 0, ',', '.') ?>₫</span>
                        </div>
                        <a href="index.php?act=xoa_voucher" class="remove-voucher-btn">Xóa voucher</a>
                        <?php endif; ?>
                        <div class="final-total">
                            <div class="price-row">
                                <span>Tổng cộng</span>
                                <span class="total-price" id="cart-final-total">0₫</span>
                            </div>
                            <small>(Chưa bao gồm phí vận chuyển)</small>
                        </div>
                        <button type="submit" class="cp-btn checkout-btn" disabled>Mua Hàng</button>
                    </div>
                </div>
            </div>

            <!-- Mục sản phẩm đã lưu (Wishlist) -->
            <div class="saved-items-section">
                <h4>Sản phẩm đã lưu</h4>
                <div class="cp-grid" style="grid-template-columns: repeat(4, 1fr);">
                    <!-- Ví dụ một sản phẩm đã lưu -->
                    <article class="cp-card">
                        <div class="cp-card__image-container">
                            <a href="#">
                                <img src="TaiLen/san_pham/1760848327_apple-macbook-air-m2-2023-15-inch-startlight-600x600.jpg" alt="Macbook Air M2 2023">
                            </a>
                        </div>
                        <div class="cp-card__content">
                            <a href="#">
                                <h4>Macbook Air M2 2023</h4>
                            </a>
                            <button type="button" class="cp-btn">Thêm lại vào giỏ</button>
                        </div>
                    </article>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- KHAI BÁO BIẾN ---
        const cartPage = document.querySelector('.cart-page-wrapper');
        if (!cartPage) return; // Chỉ chạy script nếu đang ở trang giỏ hàng

        const selectAllCheckbox = document.getElementById('select-all-items');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkoutButton = document.querySelector('.checkout-btn');
        const cartBadge = document.getElementById('cart-badge');

        // Elements for summary
        const subtotalEl = document.getElementById('cart-subtotal');
        const finalTotalEl = document.getElementById('cart-final-total');

        // Elements for free shipping progress
        const freeShippingThreshold = 1000000; // 1,000,000 VND
        const shippingProgressBar = document.querySelector('.free-shipping-progress-bar-fill');
        const shippingMessage = document.querySelector('.free-shipping-progress-box .message');

        // --- CÁC HÀM TRỢ GIÚP ---
        const formatCurrency = (number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);

        // --- HÀM CẬP NHẬT GIAO DIỆN VÀ GỬI AJAX ---
        const updateCart = (productId, quantity) => {
            // 1. Cập nhật giao diện ngay lập tức
            const itemCard = document.querySelector(`.cart-item-card[data-id='${productId}']`);
            if (itemCard) {
                const price = parseFloat(itemCard.querySelector('.quantity-input').dataset.price);
                const itemTotalEl = itemCard.querySelector('.cart-item-total-price');
                itemTotalEl.textContent = formatCurrency(price * quantity);
            }
            updateSummary();

            // 2. Gửi yêu cầu AJAX để cập nhật session
            const formData = new FormData();
            formData.append('id', productId);
            formData.append('quantity', quantity);

            fetch('index.php?act=cap_nhat_gio_hang', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật lại tổng tiền và số lượng trên header từ server
                    cartBadge.textContent = data.new_total_quantity;
                    // Nếu số lượng <= 0, sản phẩm đã bị xóa, reload để hiển thị đúng trạng thái
                    if (quantity <= 0) {
                        window.location.reload();
                    }
                }
            })
            .catch(error => console.error('Lỗi cập nhật giỏ hàng:', error));
        };

        // --- HÀM CẬP NHẬT TÓM TẮT ĐƠN HÀNG ---
        function updateSummary() {
            let subtotal = 0;
            let selectedItemsCount = 0;

            itemCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const itemCard = checkbox.closest('.cart-item-card');
                    const price = parseFloat(checkbox.dataset.price);
                    const quantity = parseInt(itemCard.querySelector('.quantity-input').value);
                    subtotal += price * quantity;
                    selectedItemsCount++;
                }
            });

            const discount = <?= $discount_amount ?? 0 ?>;
            const finalTotal = subtotal > 0 ? Math.max(0, subtotal - discount) : 0;

            subtotalEl.textContent = formatCurrency(subtotal);
            finalTotalEl.textContent = formatCurrency(finalTotal);

            // Cập nhật nút Mua Hàng
            checkoutButton.disabled = selectedItemsCount === 0;
            checkoutButton.textContent = selectedItemsCount > 0 ? `Mua Hàng (${selectedItemsCount})` : 'Mua Hàng';

            // Cập nhật thanh tiến trình miễn phí vận chuyển
            if (subtotal >= freeShippingThreshold) {
                shippingProgressBar.style.width = '100%';
                shippingMessage.innerHTML = '🎉 <strong>Chúc mừng!</strong> Bạn đã được miễn phí vận chuyển.';
            } else {
                const needed = freeShippingThreshold - subtotal;
                const progress = subtotal > 0 ? (subtotal / freeShippingThreshold) * 100 : 0;
                shippingProgressBar.style.width = `${progress}%`;
                shippingMessage.innerHTML = `Mua thêm <strong>${formatCurrency(needed)}</strong> để được miễn phí vận chuyển.`;
            }
        }

        // --- GẮN SỰ KIỆN ---

        // Sự kiện cho nút "Chọn tất cả"
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateSummary();
        });

        // Sự kiện cho từng checkbox của sản phẩm
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                selectAllCheckbox.checked = [...itemCheckboxes].every(cb => cb.checked);
                updateSummary();
            });
        });

        // Sự kiện cho các nút +/- và ô nhập số lượng
        cartPage.querySelectorAll('.quantity-selector').forEach(selector => {
            const input = selector.querySelector('.quantity-input');
            const productId = input.dataset.id;

            selector.addEventListener('click', (e) => {
                let currentValue = parseInt(input.value);
                if (e.target.classList.contains('plus')) {
                    currentValue++;
                } else if (e.target.classList.contains('minus')) {
                    currentValue = Math.max(0, currentValue - 1); // Cho phép giảm về 0 để xóa
                }
                input.value = currentValue;
                updateCart(productId, currentValue);
            });

            input.addEventListener('change', () => {
                let quantity = parseInt(input.value);
                if (isNaN(quantity) || quantity < 0) {
                    quantity = 1; // Nếu nhập linh tinh, reset về 1
                    input.value = 1;
                }
                updateCart(productId, quantity);
            });
        });

        // --- KHỞI TẠO ---
        updateSummary(); // Tính toán lần đầu khi tải trang
    });
</script>
