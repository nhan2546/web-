<?php
$order_info = $chi_tiet_don_hang['order_info'];
$order_items = $chi_tiet_don_hang['order_items'];

// Định nghĩa các bước và trạng thái tương ứng
$all_statuses = [
    'pending' => 'Chờ xác nhận',
    'processing' => 'Đang xử lý',
    'shipped' => 'Đang giao hàng',
    'delivered' => 'Đã giao',
    'cancelled' => 'Đã hủy'
];

$current_status = $order_info['status'];
$current_status_index = array_search($current_status, array_keys($all_statuses));

?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="auth-form-title mb-0">Chi tiết đơn hàng #<?= htmlspecialchars($order_info['id']) ?></h3>
        <a href="index.php?act=lich_su_mua_hang" class="btn btn-outline-secondary">‹ Quay lại Lịch sử</a>
    </div>

    <!-- Thanh theo dõi trạng thái đơn hàng -->
    <?php if ($current_status !== 'cancelled'): ?>
    <div class="order-tracking-container mb-5">
        <ul class="order-tracking-steps">
            <?php 
            $is_past_step = true;
            foreach ($all_statuses as $status_key => $status_name): 
                if ($status_key === 'cancelled') continue; // Bỏ qua trạng thái hủy trong thanh tiến trình

                $status_index = array_search($status_key, array_keys($all_statuses));
                $is_completed = $status_index < $current_status_index;
                $is_current = $status_index === $current_status_index;
            ?>
            <li class="step-item <?= $is_completed ? 'completed' : '' ?> <?= $is_current ? 'current' : '' ?>">
                <div class="step-marker"></div>
                <div class="step-name"><?= $status_name ?></div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">Đơn hàng này đã bị hủy.</div>
    <?php endif; ?>


    <div class="row g-5">
        <!-- Cột thông tin đơn hàng -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <strong>Thông tin giao hàng</strong>
                </div>
                <div class="card-body">
                    <p><strong>Người nhận:</strong> <?= htmlspecialchars($order_info['fullname']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order_info['email']) ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order_info['phone_number']) ?></p>
                    <p class="mb-0"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order_info['address']) ?></p>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <strong>Tổng cộng</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Tạm tính:</span>
                        <span><?= number_format($order_info['total_amount'] + $order_info['discount_amount'], 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Giảm giá:</span>
                        <span class="text-danger">-<?= number_format($order_info['discount_amount'], 0, ',', '.') ?>₫</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Thành tiền:</span>
                        <span class="text-danger"><?= number_format($order_info['total_amount'], 0, ',', '.') ?>₫</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột danh sách sản phẩm -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <strong>Các sản phẩm trong đơn</strong>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($order_items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="TaiLen/san_pham/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px;">
                                <div>
                                    <p class="mb-0 fw-bold"><?= htmlspecialchars($item['product_name']) ?></p>
                                    <small class="text-muted">Số lượng: <?= htmlspecialchars($item['quantity']) ?></small>
                                </div>
                            </div>
                            <span class="fw-bold"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫</span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>