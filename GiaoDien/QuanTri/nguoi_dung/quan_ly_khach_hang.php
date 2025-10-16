<div class="admin-card">
    <div class="admin-card-header">
        Quản lý Khách hàng
    </div>
    <div class="admin-card-body">
         <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Trạng thái</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach_khach_hang)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có khách hàng nào.</td>
                    </tr>
                <?php else: foreach ($danh_sach_khach_hang as $khach_hang): ?>
                    <tr>
                        <td><?= htmlspecialchars($khach_hang['id']) ?></td>
                        <td><?= htmlspecialchars($khach_hang['fullname']) ?></td>
                        <td><?= htmlspecialchars($khach_hang['email']) ?></td>
                        <td class="phone-number"><?= htmlspecialchars($khach_hang['phone_number'] ?? 'Chưa cập nhật') ?></td>
                        <td>
        <div class="admin-grid">
            <?php if (empty($danh_sach_khach_hang)): ?>
                <p class="text-center w-100">Không có khách hàng nào.</p>
            <?php else: foreach ($danh_sach_khach_hang as $khach_hang): ?>
                <div class="admin-user-card">
                    <div class="user-card-info">
                        <h5 class="user-card-name"><?= htmlspecialchars($khach_hang['fullname']) ?></h5>
                        <p class="user-card-email"><?= htmlspecialchars($khach_hang['email']) ?></p>
                        <p class="user-card-phone"><?= htmlspecialchars($khach_hang['phone_number'] ?? 'Chưa có SĐT') ?></p>
                        <div class="user-card-badges">
                            <?php if ($khach_hang['status'] == 'active'): ?>
                                <span class="badge badge-delivered">Hoạt động</span>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge badge-cancelled">Đã khóa</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <?php if ($khach_hang['status'] == 'active'): ?>
                                <a href="admin.php?act=toggle_trangthai_khachhang&id=<?= $khach_hang['id'] ?>&status=locked" class="admin-btn admin-btn-danger" onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?');">Khóa</a>
                            <?php else: ?>
                                <a href="admin.php?act=toggle_trangthai_khachhang&id=<?= $khach_hang['id'] ?>&status=active" class="admin-btn admin-btn-primary">Mở khóa</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
                        </div>
                    </div>
                    <div class="user-card-actions">
                        <?php if ($khach_hang['status'] == 'active'): ?>
                            <a href="admin.php?act=toggle_trangthai_khachhang&id=<?= $khach_hang['id'] ?>&status=locked" class="admin-btn admin-btn-danger" onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?');">Khóa</a>
                        <?php else: ?>
                            <a href="admin.php?act=toggle_trangthai_khachhang&id=<?= $khach_hang['id'] ?>&status=active" class="admin-btn admin-btn-primary">Mở khóa</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>