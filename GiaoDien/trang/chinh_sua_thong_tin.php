<?php
// Lấy thông báo từ URL để hiển thị cho người dùng
$success_msg = '';
$error_msg = '';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1':
            $success_msg = 'Cập nhật thông tin tài khoản thành công!';
            break;
        case 'password_changed':
            $success_msg = 'Đổi mật khẩu thành công!';
            break;
        case 'avatar_updated':
            $success_msg = 'Cập nhật ảnh đại diện thành công!';
            break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'password_mismatch':
            $error_msg = 'Mật khẩu mới không khớp. Vui lòng thử lại.';
            break;
        case 'wrong_password':
            $error_msg = 'Mật khẩu hiện tại không đúng.';
            break;
        case 'avatar_upload_failed':
            $error_msg = 'Tải ảnh đại diện lên thất bại. Vui lòng thử lại.';
            break;
        default:
            $error_msg = 'Có lỗi xảy ra, vui lòng thử lại.';
            break;
    }
}
?>

    <div class="text-start mb-4">
        <a href="index.php?act=thong_tin_tai_khoan" class="back-to-dashboard-link">&larr; Quay lại trang tài khoản</a>
    </div>
    <h1 class="text-center mb-5">Chỉnh sửa thông tin</h1>

    <!-- Hiển thị thông báo -->
    <?php if ($success_msg): ?>
        <div class="alert alert-success mb-4"><?= $success_msg ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger mb-4"><?= $error_msg ?></div>
    <?php endif; ?>

    <div class="account-layout">
        <!-- Cột trái: Menu điều hướng -->
        <aside class="account-sidebar">
            <div class="account-user-info">
                <div class="account-avatar">
                    <?php if (!empty($user_info['avatar_url'])): ?>
                        <img src="TaiLen/avatars/<?= htmlspecialchars($user_info['avatar_url']) ?>" alt="Avatar">
                    <?php else: ?>
                        <span><?= htmlspecialchars(strtoupper(mb_substr($user_info['fullname'], 0, 1, 'UTF-8'))) ?></span>
                    <?php endif; ?>
                </div>
                <div class="user-details">
                    <p>Tài khoản của</p>
                    <strong><?= htmlspecialchars($user_info['fullname']) ?></strong>
                </div>
            </div>
            <nav class="account-nav">
                <a href="index.php?act=thong_tin_tai_khoan" class="account-nav-item"><i class="fas fa-user"></i><span>Thông tin chung</span></a>
                <a href="index.php?act=lich_su_mua_hang" class="account-nav-item"><i class="fas fa-receipt"></i><span>Lịch sử mua hàng</span></a>
                <a href="index.php?act=chinh_sua_thong_tin" class="account-nav-item active"><i class="fas fa-edit"></i><span>Chỉnh sửa thông tin</span></a>
                <a href="index.php?act=dang_xuat" class="account-nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Đăng xuất</span></a>
            </nav>
        </aside>

        <!-- Cột phải: Nội dung chính -->
        <main class="account-content">
            <!-- Form cập nhật thông tin cá nhân -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <form action="index.php?act=cap_nhat_tai_khoan" method="POST" id="info-form">
                        <div class="mb-3"><label for="email" class="form-label">Địa chỉ Email</label><input type="email" id="email" class="form-control" value="<?= htmlspecialchars($user_info['email']) ?>" readonly></div>
                        <div class="mb-3"><label for="fullname" class="form-label">Họ và tên</label><input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($user_info['fullname']) ?>"></div>
                        <div class="mb-3"><label for="phone_number" class="form-label">Số điện thoại</label><input type="text" id="phone_number" name="phone_number" class="form-control" value="<?= htmlspecialchars($user_info['phone_number'] ?? '') ?>"></div>
                        <div class="mb-3"><label for="address" class="form-label">Địa chỉ</label><input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($user_info['address'] ?? '') ?>"></div>
                        <div class="text-end"><button type="submit" form="info-form" class="btn btn-primary">Lưu thay đổi</button></div>
                    </form>
                </div>
            </div>

            <!-- Form đổi mật khẩu -->
            <div class="card">
                <div class="card-header">
                    <h3>Đổi mật khẩu</h3>
                </div>
                <div class="card-body">
                    <form action="index.php?act=doi_mat_khau" method="POST" id="password-form">
                        <div class="mb-3 password-wrapper"><label for="current_password" class="form-label">Mật khẩu hiện tại</label><input type="password" id="current_password" name="current_password" class="form-control" required><button type="button" class="password-toggle-btn" id="toggleCurrentPassword"><i class="fas fa-eye"></i></button></div>
                        <div class="mb-3 password-wrapper"><label for="new_password" class="form-label">Mật khẩu mới</label><input type="password" id="new_password" name="new_password" class="form-control" required><button type="button" class="password-toggle-btn" id="toggleNewPassword"><i class="fas fa-eye"></i></button></div>
                        <div class="mb-3 password-wrapper"><label for="confirm_new_password" class="form-label">Xác nhận mật khẩu mới</label><input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required><button type="button" class="password-toggle-btn" id="toggleConfirmPassword"><i class="fas fa-eye"></i></button></div>
                        <div class="text-end"><button type="submit" form="password-form" class="btn btn-primary">Đổi mật khẩu</button></div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Logic cho upload và preview avatar ---
    const avatarForm = document.getElementById('avatar-form');
    const avatarUploadInput = document.getElementById('avatar-upload-input');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarUploadInput && avatarPreview && avatarForm) {
        avatarUploadInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
                // Tự động submit form khi người dùng đã chọn ảnh
                setTimeout(() => avatarForm.submit(), 300);
            }
        });
    }

    // --- Logic cho nút ẩn/hiện mật khẩu ---
    function setupPasswordToggle(buttonId, inputId) {
        const toggleButton = document.getElementById(buttonId);
        const passwordInput = document.getElementById(inputId);
        if (!toggleButton || !passwordInput) return;

        const eyeIcon = '<i class="fas fa-eye"></i>';
        const eyeSlashIcon = '<i class="fas fa-eye-slash"></i>';

        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Thay đổi icon
            if (type === 'password') {
                this.innerHTML = eyeIcon;
            } else {
                this.innerHTML = eyeSlashIcon;
            }
        });
        // Đặt icon ban đầu
        toggleButton.innerHTML = eyeIcon;
    }

    setupPasswordToggle('toggleCurrentPassword', 'current_password');
    setupPasswordToggle('toggleNewPassword', 'new_password');
    setupPasswordToggle('toggleConfirmPassword', 'confirm_new_password');
});
</script>
