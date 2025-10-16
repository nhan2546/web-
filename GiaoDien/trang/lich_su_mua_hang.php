<div class="container">
    <h2 class="mb-4">Lịch Sử Mua Hàng</h2>

    <div class="order-history-list">
        <?php if (empty($danh_sach_don_hang)): ?>
            <div class="alert alert-info text-center">Bạn chưa có đơn hàng nào.</div>
        <?php else: ?>
            <?php foreach ($danh_sach_don_hang as $don_hang): ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-id">Mã đơn: #<?= htmlspecialchars($don_hang['id']) ?></div>
                        <div class="order-date">Ngày đặt: <?= htmlspecialchars(date('d-m-Y', strtotime($don_hang['order_date']))) ?></div>
                    </div>
                    <div class="order-card-body">
                        <div class="order-info">
                            <div class="info-item">
                                <span>Trạng thái</span>
                                <?php
                                    $status_class = 'badge-secondary'; // Mặc định
                                    if ($don_hang['status'] == 'pending') $status_class = 'badge-pending';
                                    if ($don_hang['status'] == 'processing') $status_class = 'badge-processing';
                                    if ($don_hang['status'] == 'shipped') $status_class = 'badge-shipped';
                                    if ($don_hang['status'] == 'delivered') $status_class = 'badge-delivered';
                                    if ($don_hang['status'] == 'cancelled') $status_class = 'badge-cancelled';
                                ?>
                                <span class="badge <?= $status_class ?>"><?= htmlspecialchars($don_hang['status']) ?></span>
                            </div>
                            <div class="info-item">
                                <span>Tổng tiền</span>
                                <strong><?= number_format($don_hang['total_amount'], 0, ',', '.') ?> VND</strong>
                            </div>
                        </div>
                        <div class="order-actions">
                            <a href="index.php?act=chi_tiet_don_hang&id=<?= $don_hang['id'] ?>" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>