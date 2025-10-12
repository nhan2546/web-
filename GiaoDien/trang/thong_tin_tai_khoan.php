<div class="container account-page-wrapper">
    <div class="card">
        <div class="card-body p-4">
            <h1 class="mb-4 text-center">Thông Tin Tài Khoản</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Cập nhật thông tin thành công!</div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại.</div>
            <?php endif; ?>

            <div class="row">
                <!-- Cột trái: Các form cập nhật -->
                <div class="col-lg-8 border-end-lg">
                    <h3 class="account-section-title">Cập nhật thông tin cá nhân</h3>
                    <form action="index.php?act=cap_nhat_tai_khoan" method="POST">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và Tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($user_info['fullname'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user_info['email'] ?? '') ?>" readonly>
                            <div class="form-text">Bạn không thể thay đổi địa chỉ email.</div>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user_info['phone_number'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($user_info['address'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>

                    <hr class="my-4">

                    <h3 class="account-section-title">Đổi mật khẩu</h3>
                    <form action="index.php?act=doi_mat_khau" method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_new_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                    </form>
                </div>

                <!-- Cột phải: Tóm tắt thông tin -->
                <div class="col-lg-4">
                    <div class="profile-summary-card text-center">
                        <form action="index.php?act=cap_nhat_avatar" method="POST" enctype="multipart/form-data" id="avatar-form">
                            <div class="profile-avatar-wrapper">
                                <div class="profile-avatar">
                                    <?php if (!empty($user_info['avatar_url'])): ?>
                                        <img src="TaiLen/avatars/<?= htmlspecialchars($user_info['avatar_url']) ?>" alt="Avatar" id="avatar-preview">
                                    <?php else: ?>
                                        <span id="avatar-initial"><?= strtoupper(substr($user_info['fullname'] ?? 'K', 0, 1)) ?></span>
                                        <img src="" alt="Avatar" id="avatar-preview" style="display: none;">
                                    <?php endif; ?>
                                </div>
                                <label for="avatar-upload" class="avatar-upload-overlay" title="Đổi ảnh đại diện">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16"><path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/></svg>
                                </label>
                                <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="d-none">
                            </div>
                        </form>
                        <h5 class="profile-name"><?= htmlspecialchars($user_info['fullname'] ?? 'Khách') ?></h5>
                        <p class="profile-email text-muted"><?= htmlspecialchars($user_info['email'] ?? '') ?></p>
                        <hr>
                        <a href="index.php?act=lich_su_mua_hang" class="btn btn-outline-primary w-100 mb-2">Lịch sử mua hàng</a>
                        <a href="index.php?act=dang_xuat" class="btn btn-outline-danger w-100">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarUpload = document.getElementById('avatar-upload');

    avatarUpload.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            // Tự động gửi form ngay khi người dùng chọn ảnh
            document.getElementById('avatar-form').submit();
        }
    });
});
</script>