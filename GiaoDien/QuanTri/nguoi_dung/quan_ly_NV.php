<div class="page-header">
    <h1>Quản lý Nhân Viên</h1>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach_nhan_vien)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Không có nhân viên nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($danh_sach_nhan_vien as $nhan_vien): ?>
                    <tr>
                        <td><?php echo $nhan_vien['id']; ?></td>
                        <td><?php echo htmlspecialchars($nhan_vien['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($nhan_vien['email']); ?></td>
                        <td><span class="badge badge-secondary"><?php echo htmlspecialchars($nhan_vien['role']); ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($nhan_vien['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="admin.php?act=sua_nhanvien&id=<?php echo $nhan_vien['id']; ?>" class="admin-btn admin-btn-primary">Sửa</a>
                            <?php if ($nhan_vien['id'] != $_SESSION['user_id']): // Không cho phép xóa chính mình ?>
                                <a href="admin.php?act=xoa_nhanvien&id=<?php echo $nhan_vien['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');" class="admin-btn admin-btn-danger">Xóa</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>