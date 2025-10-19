<?php
// Extract order info and items from the data passed by the controller
$order_info = $chi_tiet_don_hang['order_info'] ?? [];
$order_items = $chi_tiet_don_hang['order_items'] ?? [];

// All possible order statuses
$all_statuses = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'shipping' => 'Đang giao hàng',
    'delivered' => 'Đã giao',
    'success' => 'Thành công',
    'cancelled' => 'Đã hủy'
];

$current_status = $order_info['status'] ?? 'pending';
?>

<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span>Chi tiết đơn hàng #<?= htmlspecialchars($order_info['id'] ?? '') ?></span>
        <a href="admin.php?act=ds_donhang" class="admin-btn" style="background-color: #6c757d; color: white;">‹ Quay lại danh sách</a>
    </div>
    <div class="admin-card-body">
        <div class="row">
            <!-- Left Column: Order and Customer Info -->
            <div class="col-md-6">
                <h4>Thông tin người nhận</h4>
                <p><strong>Tên:</strong> <?= htmlspecialchars($order_info['fullname'] ?? 'N/A') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order_info['email'] ?? 'N/A') ?></p>
                <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order_info['phone_number'] ?? 'N/A') ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order_info['address'] ?? 'N/A') ?></p>
                <hr>
                <h4>Thông tin đơn hàng</h4>
                <p><strong>Ngày đặt:</strong> <?= htmlspecialchars(date("d/m/Y H:i", strtotime($order_info['order_date'] ?? 'now'))) ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order_info['payment_method'] ?? 'N/A') ?></p>
            </div>

            <!-- Right Column: Status and Totals -->
            <div class="col-md-6">
                <h4>Trạng thái đơn hàng</h4>
                <form id="update-status-form" class="d-flex align-items-center">
                    <input type="hidden" name="order_id" value="<?= $order_info['id'] ?>">
                    <select name="status" class="form-select" style="max-width: 200px; margin-right: 10px;">
                        <?php foreach ($all_statuses as $status_key => $status_name): ?>
                            <option value="<?= $status_key ?>" <?= ($status_key == $current_status) ? 'selected' : '' ?>>
                                <?= $status_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="admin-btn admin-btn-primary">Cập nhật</button>
                </form>
                <div id="status-update-alert" class="mt-2" style="display: none;"></div>

                <hr>

                <h4>Tổng cộng</h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Tạm tính</td>
                            <td class="text-end"><?= number_format(($order_info['total_amount'] ?? 0) + ($order_info['discount_amount'] ?? 0), 0, ',', '.') ?>₫</td>
                        </tr>
                        <tr>
                            <td>Giảm giá</td>
                            <td class="text-end text-danger">-<?= number_format($order_info['discount_amount'] ?? 0, 0, ',', '.') ?>₫</td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Thành tiền</td>
                            <td class="text-end fs-5 text-danger"><?= number_format($order_info['total_amount'] ?? 0, 0, ',', '.') ?>₫</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <!-- Product List -->
        <h4>Các sản phẩm trong đơn</h4>
        <ul class="list-group list-group-flush">
            <?php if (!empty($order_items)): ?>
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
            <?php else: ?>
                <li class="list-group-item">Không có sản phẩm nào trong đơn hàng này.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<script>
document.getElementById('update-status-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const orderId = formData.get('order_id');
    const newStatus = formData.get('status');
    const alertBox = document.getElementById('status-update-alert');

    fetch('admin.php?act=capnhat_trangthai_donhang', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'id': orderId,
            'status': newStatus
        })
    })
    .then(response => {
        if (response.ok) {
            return response.text();
        }
        throw new Error('Lỗi mạng hoặc máy chủ.');
    })
    .then(data => {
        if (data === 'OK') {
            alertBox.className = 'alert alert-success';
            alertBox.textContent = 'Cập nhật trạng thái thành công!';
            alertBox.style.display = 'block';
        } else {
            throw new Error(data || 'Lỗi không xác định từ máy chủ.');
        }
    })
    .catch(error => {
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'Lỗi: ' + error.message;
        alertBox.style.display = 'block';
    })
    .finally(() => {
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000);
    });
});
</script>