<div class="account-page-wrapper">
    <div class="account-form-container">
        <a href="index.php?act=thong_tin_tai_khoan" class="back-to-dashboard-link">&larr; Quay lại trang tài khoản</a>
        <h1 class="auth-form-title text-center">Lịch sử mua hàng</h1>
        
        <!-- Thanh điều hướng lọc trạng thái -->
        <div class="order-status-tabs">
            <a href="index.php?act=lich_su_mua_hang" class="tab-item <?= empty($status_filter) ? 'active' : '' ?>">Tất cả</a>
            <?php foreach ($order_statuses as $status_key => $status_name): ?>
                <a href="index.php?act=lich_su_mua_hang&status=<?= $status_key ?>" class="tab-item <?= ($status_filter === $status_key) ? 'active' : '' ?>">
                    <?= $status_name ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="order-history-list">
            <?php if (empty($danh_sach_don_hang)): ?>
                <div class="alert alert-info text-center mt-4">Bạn chưa có đơn hàng nào trong mục này.</div>
            <?php else: ?>
                <?php foreach ($danh_sach_don_hang as $don_hang): ?>
                    <div class="order-card">
                        <div class="order-card-header">
                            <div class="order-id">Mã đơn: <strong>#<?= htmlspecialchars($don_hang['id']) ?></strong></div>
                            <div class="order-date">Ngày đặt: <?= htmlspecialchars(date('d-m-Y', strtotime($don_hang['order_date']))) ?></div>
                        </div>
                        <div class="order-card-body">
                            <div class="order-info">
                                <div class="info-item">
                                    <span>Trạng thái</span>
                                    <?php
                                        $status_key = $don_hang['status'];
                                        $status_name = $order_statuses[$status_key] ?? ucfirst($status_key);
                                        $status_class = 'badge-secondary'; // Mặc định
                                        if ($status_key == 'pending') $status_class = 'badge-pending';
                                        if ($status_key == 'processing') $status_class = 'badge-processing';
                                        if ($status_key == 'shipped') $status_class = 'badge-shipped';
                                        if ($status_key == 'delivered') $status_class = 'badge-delivered';
                                        if ($status_key == 'cancelled') $status_class = 'badge-cancelled';
                                    ?>
                                    <span class="badge <?= $status_class ?>"><?= htmlspecialchars($status_name) ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Tổng tiền</span>
                                    <strong><?= number_format($don_hang['total_amount'], 0, ',', '.') ?>₫</strong>
                                </div>
                            </div>
                            <div class="order-actions">
                                <a href="index.php?act=chi_tiet_don_hang&id=<?= $don_hang['id'] ?>" class="cp-btn">Xem Chi Tiết</a>
                                <?php if ($don_hang['status'] === 'pending'): ?>
                                    <a href="index.php?act=huy_don_hang&id=<?= $don_hang['id'] ?>" class="cp-btn cp-btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')">Hủy đơn</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>