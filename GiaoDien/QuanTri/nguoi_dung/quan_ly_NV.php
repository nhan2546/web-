<div class="page-header d-flex justify-content-between align-items-center">
    <h1>Quản lý Nhân Viên</h1>
    <a href="admin.php?act=them_nv" class="admin-btn admin-btn-primary">Thêm Nhân Viên Mới</a>
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
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach_nhan_vien)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Không có nhân viên nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($danh_sach_nhan_vien as $nhan_vien): ?>
                    <tr>
                        <td><?php echo $nhan_vien['id']; ?></td>
                        <td><?php echo htmlspecialchars($nhan_vien['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($nhan_vien['email']); ?></td>
                        <td><span class="badge badge-secondary"><?php echo htmlspecialchars($nhan_vien['role']); ?></span></td>
                        <td>
                            <?php if (isset($nhan_vien['is_locked']) && $nhan_vien['is_locked']): ?>
                                <span class="badge badge-danger">Đã khóa</span>
                            <?php else: ?>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($nhan_vien['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="admin.php?act=sua_nhanvien&id=<?php echo $nhan_vien['id']; ?>" class="admin-btn admin-btn-primary">Sửa</a>
                            <?php if ($nhan_vien['id'] != $_SESSION['user_id']): // Không cho phép thao tác với chính mình ?>
                                <a href="admin.php?act=toggle_lock_nv&id=<?php echo $nhan_vien['id']; ?>" class="admin-btn admin-btn-warning">
                                    <?php if (isset($nhan_vien['is_locked']) && $nhan_vien['is_locked']): ?>
                                        Mở khóa
                                    <?php else: ?>
                                        Khóa
                                    <?php endif; ?>
                                </a>
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