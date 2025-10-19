<div class="page-header">
    <h1>Thêm Nhân Viên Mới</h1>
</div>
<div class="admin-form-container">
    <div class="admin-card">
        <div class="admin-card-body">
            <form action="admin.php?act=xl_them_nv" method="POST">
                <div class="form-group-grid">
                    <label for="fullname" class="form-label">Họ và Tên</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                </div>
    
                <div class="form-group-grid">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group-grid">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group-grid">
                    <label for="confirm_password" class="form-label">Xác nhận Mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
    
                <div class="form-group-grid">
                    <label for="role" class="form-label">Vai trò</label>
                    <div>
                        <select id="role" name="role" class="form-select">
                            <?php
                            $role_names_vn = [
                                'staff' => 'Nhân viên',
                                'admin' => 'Quản lý',
                                'manager' => 'Giám đốc' // Thêm vai trò Giám đốc
                            ];
                            foreach ($roles as $role_value): ?>
                                <option value="<?php echo $role_value; ?>">
                                    <?php echo $role_names_vn[$role_value] ?? ucfirst($role_value); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
    
                <div class="d-flex justify-content-end mt-4">
                    <a href="admin.php?act=ds_nhanvien" class="admin-btn admin-btn-secondary">Hủy</a>
                    <button type="submit" class="admin-btn admin-btn-primary">Thêm Nhân Viên</button>
                </div>
            </form>
        </div>
    </div>
</div>