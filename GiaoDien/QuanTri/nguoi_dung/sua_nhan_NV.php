<div class="page-header">
    <h1>Sửa thông tin Nhân Viên</h1>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <form action="admin.php?act=xl_sua_nhanvien" method="POST">
            <input type="hidden" name="id" value="<?php echo $nguoi_dung['id']; ?>">

            <div class="mb-3">
                <label for="fullname" class="form-label">Họ và Tên</label>
                <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($nguoi_dung['fullname']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($nguoi_dung['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Vai trò (Phân quyền)</label>
                <select id="role" name="role" class="form-select">
                    <?php foreach ($roles as $role_value): ?>
                        <option value="<?php echo $role_value; ?>" <?php echo ($nguoi_dung['role'] === $role_value) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($role_value); // Viết hoa chữ cái đầu ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">
                    'Customer' là khách hàng, 'Staff' là nhân viên, 'Admin' là quản trị viên.
                </div>
            </div>

            <div class="form-group mt-4 d-flex justify-content-end">
                <a href="admin.php?act=ds_nguoidung" class="admin-btn" style="background-color: #6c757d; color: white; margin-right: 10px;">Hủy</a>
                <button type="submit" class="admin-btn admin-btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>