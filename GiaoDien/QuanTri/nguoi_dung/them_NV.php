<div class="page-header">
    <h1>Thêm Nhân Viên Mới</h1>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <form action="admin.php?act=xl_them_nv" method="POST">

            <div class="mb-3">
                <label for="fullname" class="form-label">Họ và Tên</label>
                <input type="text" id="fullname" name="fullname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Vai trò (Phân quyền)</label>
                <select id="role" name="role" class="form-select">
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
                <div class="form-text">
                     'Staff' là nhân viên, 'Admin' là quản trị viên.
                </div>
            </div>

            <div class="form-group mt-4 d-flex justify-content-end">
                <a href="admin.php?act=ds_nhanvien" class="admin-btn" style="background-color: #090909ff; color: white; margin-right: 10px;">Hủy</a>
                <button type="submit" class="admin-btn admin-btn-primary">Thêm Nhân Viên</button>
            </div>
        </form>
    </div>
</div>