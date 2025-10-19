<div class="page-header">
    <h1>Quản lý Voucher</h1>
</div>

<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span>Danh sách Voucher</span>
        <a href="admin.php?act=them_voucher" class="admin-btn admin-btn-primary">Thêm Voucher mới</a>
    </div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã Voucher</th>
                    <th>Mô tả</th>
                    <th>Giá trị giảm</th>
                    <th>Đơn tối thiểu</th>
                    <th>Ngày hết hạn</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($danh_sach_voucher)): ?>
                    <?php foreach ($danh_sach_voucher as $voucher): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($voucher['code']); ?></strong></td>
                            <td><?php echo htmlspecialchars($voucher['description']); ?></td>
                            <td>
                                <?php 
                                    if ($voucher['discount_type'] == 'percentage') {
                                        echo htmlspecialchars($voucher['discount_value']) . '%';
                                    } else {
                                        echo number_format($voucher['discount_value'], 0, ',', '.') . 'đ';
                                    }
                                ?>
                            </td>
                            <td><?php echo number_format($voucher['min_order_amount'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <?php 
                                    if ($voucher['expires_at']) {
                                        echo date('d/m/Y', strtotime($voucher['expires_at']));
                                    } else {
                                        echo 'Không giới hạn';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if ($voucher['is_active']): ?>
                                    <span class="badge badge-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Không hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons text-center">
                                <a href="admin.php?act=sua_voucher&id=<?php echo $voucher['id']; ?>" class="admin-btn admin-btn-secondary">Sửa</a>
                                <a href="admin.php?act=xoa_voucher&id=<?php echo $voucher['id']; ?>" 
                                   class="admin-btn admin-btn-danger" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này không?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Chưa có voucher nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>