<?php
if (empty($chi_tiet_don_hang) || empty($chi_tiet_don_hang['order_info'])) {
    // Hiển thị thông báo nếu không có dữ liệu đơn hàng
    echo '<div class="container my-5"><div class="alert alert-warning">Không tìm thấy thông tin đơn hàng.</div></div>';
    return; // Dừng thực thi script này nếu không có dữ liệu
}

$order_info = $chi_tiet_don_hang['order_info'] ?? [];
$order_items = $chi_tiet_don_hang['order_items'] ?? [];

// Định nghĩa các bước và trạng thái tương ứng
$all_statuses = [
    'pending'   => ['name' => 'Chờ xác nhận', 'icon' => 'fas fa-receipt', 'date_field' => 'order_date'],
    'confirmed' => ['name' => 'Đã xác nhận', 'icon' => 'fas fa-box-open', 'date_field' => 'confirmed_date'],
    'shipping'  => ['name' => 'Đang giao hàng', 'icon' => 'fas fa-truck', 'date_field' => 'shipping_date'],
    'success'   => ['name' => 'Giao thành công', 'icon' => 'fas fa-check-double', 'date_field' => 'delivered_date'],
    'cancelled' => ['name' => 'Đã hủy', 'icon' => 'fas fa-times-circle', 'date_field' => 'cancelled_date']
];

$current_status = $order_info['status'] ?? 'pending';
$status_keys = array_keys($all_statuses);
$current_status_index = array_search($current_status, $status_keys);
?>

<div class="order-detail-page">
    <div class="order-detail-container">
        <div class="order-detail-header">
            <h1 class="order-detail-title">Chi tiết đơn hàng #<?= htmlspecialchars($order_info['id'] ?? 'N/A') ?></h1>
            <a href="index.php?act=lich_su_mua_hang" class="back-link">‹ Quay lại Lịch sử mua hàng</a>
        </div>

        <!-- Thanh theo dõi trạng thái -->
        <div class="detail-card">
            <?php if ($current_status !== 'cancelled'): ?>
                <div class="order-tracking-container">
                    <ul class="order-tracking-steps">
                        <?php
                        // Bỏ qua 'cancelled' khỏi thanh tiến trình
                        $progress_statuses = array_filter($all_statuses, fn($key) => $key !== 'cancelled', ARRAY_FILTER_USE_KEY);

                        foreach ($progress_statuses as $status_key => $status_details):
                            $status_index = array_search($status_key, $status_keys); // Vẫn tìm trong mảng gốc
                            $is_completed = $current_status_index > $status_index;
                            $is_current = $current_status_index === $status_index;

                            $date_field = $status_details['date_field'];
                            $update_date = $order_info[$date_field] ?? null;
                        ?>
                        <li class="step-item <?= $is_completed ? 'completed' : '' ?> <?= $is_current ? 'current' : '' ?>">
                            <div class="step-marker"><i class="<?= $status_details['icon'] ?>"></i></div>
                            <div class="step-name"><?= $status_details['name'] ?></div>
                            <?php if ($update_date && ($is_completed || $is_current) && strtotime($update_date)): ?>
                                <div class="step-date"><?= date('H:i d/m/Y', strtotime($update_date)) ?></div>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: // Trường hợp đơn hàng đã hủy ?>
                <div class="cancelled-alert">
                    Đơn hàng này đã bị hủy vào lúc <?= isset($order_info['cancelled_date']) ? date('H:i d/m/Y', strtotime($order_info['cancelled_date'])) : 'không rõ' ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="row g-4 mt-1">
            <!-- Cột trái: Thông tin giao hàng và sản phẩm -->
            <div class="col-lg-8">
                <!-- Thông tin giao hàng -->
                <div class="detail-card">
                    <h2 class="detail-card-header">Thông tin giao hàng</h2>
                    <div class="info-grid">
                        <div class="info-group">
                            <p><strong>Người nhận:</strong> <?= htmlspecialchars($order_info['fullname'] ?? '') ?></p>
                            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order_info['phone_number'] ?? '') ?></p>
                        </div>
                        <div class="info-group">
                            <p><strong>Email:</strong> <?= htmlspecialchars($order_info['email'] ?? '') ?></p>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order_info['address'] ?? '') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Danh sách sản phẩm -->
                <div class="detail-card mt-4">
                    <h2 class="detail-card-header">Sản phẩm đã đặt</h2>
                    <ul class="list-group list-group-flush product-list">
                        <?php foreach ($order_items as $item): ?>
                        <li class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-md-8 col-9">
                                    <div class="product-info">
                                        <img src="TaiLen/san_pham/<?= htmlspecialchars($item['image_url'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($item['product_name'] ?? '') ?>">
                                        <div class="product-details">
                                            <p class="product-name"><?= htmlspecialchars($item['product_name'] ?? '') ?></p>
                                            <p class="product-quantity">Số lượng: <?= htmlspecialchars($item['quantity'] ?? 0) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-3 product-price">
                                    <span><?= number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') ?>₫</span>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt đơn hàng và thanh toán -->
            <div class="col-lg-4">
                <div class="detail-card">
                    <h2 class="detail-card-header">Tóm tắt đơn hàng</h2>
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span><?= number_format(($order_info['total_amount'] ?? 0) + ($order_info['discount_amount'] ?? 0), 0, ',', '.') ?>₫</span>
                        </div>
                        <div class="summary-row">
                            <span>Giảm giá:</span>
                            <span>-<?= number_format($order_info['discount_amount'] ?? 0, 0, ',', '.') ?>₫</span>
                        </div>
                        <div class="summary-row total">
                            <span>Thành tiền:</span>
                            <span><?= number_format($order_info['total_amount'] ?? 0, 0, ',', '.') ?>₫</span>
                        </div>
                    </div>

                    <!-- Nút hủy đơn hàng -->
                    <?php if (($order_info['status'] ?? '') === 'pending'): ?>
                        <div class="cancel-button-container">
                            <a href="index.php?act=huy_don_hang&id=<?= $order_info['id'] ?? 0 ?>" class="cp-btn cp-btn-danger w-100" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')">Hủy đơn hàng</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>