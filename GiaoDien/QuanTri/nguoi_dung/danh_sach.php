<div class="page-header">
    <h1>Quản lý Người dùng</h1>
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
                <?php foreach ($danh_sach_nguoi_dung as $nguoi_dung): ?>
                <tr>
                    <td><?php echo $nguoi_dung['id']; ?></td>
                    <td><?php echo htmlspecialchars($nguoi_dung['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($nguoi_dung['email']); ?></td>
                    <td><span class="badge"><?php echo htmlspecialchars($nguoi_dung['role']); ?></span></td>
                    <td><?php echo date('d/m/Y', strtotime($nguoi_dung['created_at'])); ?></td>
                    <td class="action-buttons">
                        <?php if ($nguoi_dung['id'] != $_SESSION['user_id']): // Không cho phép xóa chính mình ?>
                            <a href="admin.php?act=xoa_nguoidung&id=<?php echo $nguoi_dung['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');" class="admin-btn admin-btn-danger">Xóa</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>