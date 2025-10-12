<div class="page-header">
    <h1>Quản lý Đơn hàng</h1>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID Đơn hàng</th>
                    <th>Tên Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach_don_hang)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($danh_sach_don_hang as $don_hang): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($don_hang['id']); ?></td>
                        <td><?php echo htmlspecialchars($don_hang['customer_name']); ?></td>
                        <td><?php echo number_format($don_hang['total_amount'], 0, ',', '.'); ?> VND</td>
                        <td>
                            <!-- Sử dụng class badge để làm nổi bật trạng thái -->
                            <span class="badge badge-<?php echo htmlspecialchars($don_hang['status']); ?>">
                                <?php echo htmlspecialchars($don_hang['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($don_hang['order_date'])); ?></td>
                        <td class="action-buttons">
                            <a href="admin.php?act=ct_donhang&id=<?php echo $don_hang['id']; ?>" class="admin-btn admin-btn-primary">Xem chi tiết</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>