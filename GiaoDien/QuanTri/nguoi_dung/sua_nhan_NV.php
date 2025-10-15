<div class="page-header">
    <h1>Sửa thông tin Nhân Viên</h1>
</div>
<div class="admin-form-container">
    <div class="admin-card">
        <div class="admin-card-body">
            <form action="admin.php?act=xl_sua_nhanvien" method="POST">
                <input type="hidden" name="id" value="<?php echo $nhan_vien['id']; ?>">
    
                <div class="form-group-grid">
                    <label for="fullname" class="form-label">Họ và Tên</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($nhan_vien['fullname']); ?>" required>
                </div>
    
                <div class="form-group-grid">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($nhan_vien['email']); ?>" required>
                </div>
    
                <div class="form-group-grid">
                    <label for="role" class="form-label">Vai trò (Phân quyền)</label>
                    <div>
                        <select id="role" name="role" class="form-select">
                            <?php 
                            $role_names_vn = [
                                'customer' => 'Khách hàng',
                                'staff' => 'Nhân viên',
                                'admin' => 'Quản lý',
                                'manager' => 'Giám đốc'
                            ];
                            foreach ($roles as $role_value): ?>
                                <option value="<?php echo $role_value; ?>" <?php echo ($nhan_vien['role'] === $role_value) ? 'selected' : ''; ?>>
                                    <?php echo $role_names_vn[$role_value] ?? ucfirst($role_value); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
    
                <div class="d-flex justify-content-end mt-4">
                    <a href="admin.php?act=ds_nhanvien" class="admin-btn admin-btn-secondary">Hủy</a>
                    <button type="submit" class="admin-btn admin-btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>