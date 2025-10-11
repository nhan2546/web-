<?php
$cart = $_SESSION['cart'] ?? [];
$total_price = 0;
?>
<div class="cart-section">
    <h2 class="mb-4">Giỏ Hàng Của Bạn</h2>
    <?php if (empty($cart)): ?>
        <div class="alert alert-info text-center">Giỏ hàng của bạn đang trống. <a href="index.php?act=hienthi_sp">Bắt đầu mua sắm</a>!</div>
    <?php else: ?>
        <div class="row">
            <!-- Cột danh sách sản phẩm -->
            <div class="col-lg-8">
                <form action="index.php?act=cap_nhat_gio_hang" method="POST" id="cart-update-form">
                    <div class="cart-items-container">
                        <table class="table cart-items-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Tạm tính</th>
                                    <th class="text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total_price += $subtotal;
                                ?>
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="TaiLen/san_pham/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-product-image">
                                    </td>
                                    <td class="align-middle">
                                        <div class="cart-product-info">
                                            <a href="index.php?act=chi_tiet_san_pham&id=<?= $item['id'] ?>" class="cart-product-name"><?= htmlspecialchars($item['name']) ?></a>
                                            <div class="cart-product-price"><?= number_format($item['price'], 0, ',', '.') ?> VND</div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="quantity-controls">
                                            <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= htmlspecialchars($item['quantity']) ?>" class="form-control form-control-sm text-center" min="1">
                                        </div>
                                    </td>
                                    <td class="align-middle text-end"><?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                                    <td class="align-middle text-center">
                                        <a href="index.php?act=xoa_san_pham_gio_hang&id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">&times;</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-info">Cập nhật giỏ hàng</button>
                    </div>
                </form>
            </div>

            <!-- Cột tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4 class="mb-3">Tóm tắt đơn hàng</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <span><?= number_format($total_price, 0, ',', '.') ?> VND</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Tổng cộng</span>
                        <span><?= number_format($total_price, 0, ',', '.') ?> VND</span>
                    </div>
                    <div class="d-grid mt-4">
                        <a href="index.php?act=thanh_toan" class="btn btn-primary btn-lg">Tiến hành Thanh Toán</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>