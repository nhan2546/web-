<?php
// Dữ liệu $chi_tiet_don_hang được truyền từ controller
$order_info = $chi_tiet_don_hang['order_info'];
$order_items = $chi_tiet_don_hang['order_items'];
?>

<div class="order-success-page-wrapper">
    <div class="order-success-card">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
        </div>
        <h1 class="success-title">Đặt hàng thành công!</h1>
        <p class="success-message">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận.</p>

        <div class="order-summary-details">
            <div class="summary-header">
                <p>Mã đơn hàng của bạn là: <strong>#<?= htmlspecialchars($order_info['id']) ?></strong></p>
            </div>
            <div class="summary-body">
                <div class="summary-row">
                    <span>Ngày đặt hàng:</span>
                    <span><?= date('d/m/Y H:i', strtotime($order_info['order_date'])) ?></span>
                </div>
                <div class="summary-row">
                    <span>Giao đến:</span>
                    <span class="text-end">
                        <?= htmlspecialchars($order_info['fullname']) ?><br>
                        <?= htmlspecialchars($order_info['address']) ?>
                    </span>
                </div>
                <div class="summary-row">
                    <span>Tổng tiền:</span>
                    <span class="total-price"><?= number_format($order_info['total_amount'], 0, ',', '.') ?>₫</span>
                </div>
            </div>
        </div>

        <div class="next-steps">
            <p>Chúng tôi sẽ gửi một email xác nhận với chi tiết đơn hàng của bạn trong ít phút.</p>
            <p>Bạn có thể theo dõi trạng thái đơn hàng trong trang tài khoản.</p>
        </div>

        <div class="action-buttons">
            <a href="index.php?act=chi_tiet_don_hang&id=<?= $order_info['id'] ?>" class="cp-btn">Xem chi tiết đơn hàng</a>
            <a href="index.php?act=hienthi_sp" class="cp-btn cp-btn-secondary">Tiếp tục mua sắm</a>
        </div>
    </div>
</div>