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

<div class="account-page-wrapper">
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

    <div class="account-edit-layout container-narrow">
        <!-- Cột trái: Avatar và các nút -->
        <div class="card profile-summary-card text-center">
            <form action="index.php?act=cap_nhat_avatar" method="POST" enctype="multipart/form-data" id="avatar-form">
                <div class="profile-avatar-wrapper mx-auto">
                    <label for="avatar-upload-input" class="profile-avatar" style="cursor: pointer;">
                        <?php if (!empty($user_info['avatar_url'])): ?>
                            <img id="avatar-preview" src="TaiLen/avatars/<?= htmlspecialchars($user_info['avatar_url']) ?>" alt="Avatar">
                        <?php else: ?>
                            <img id="avatar-preview" src="TaiNguyen/hinh_anh/avatar-default.jpg" alt="Avatar mặc định">
                        <?php endif; ?>
                    </label>
                    <label for="avatar-upload-input" class="avatar-upload-overlay">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" name="avatar" id="avatar-upload-input" class="d-none" accept="image/*">
                </div>
            </form>
            <h5 class="profile-name mt-3"><?= htmlspecialchars($user_info['fullname']) ?></h5>
            <p class="profile-email mb-4"><?= htmlspecialchars($user_info['email']) ?></p>
            <div class="d-grid gap-2">
                <button type="submit" form="info-form" class="btn btn-primary">Lưu thông tin</button>
                <button type="submit" form="password-form" class="btn btn-outline-primary">Đổi mật khẩu</button>
            </div>
        </div>

        <!-- Cột phải: Các form -->
        <div>
            <!-- Form cập nhật thông tin cá nhân -->
            <div class="card mb-4">
                <div class="card-header"><h3>Thông tin cá nhân</h3></div>
                <div class="card-body">
                    <form action="index.php?act=cap_nhat_tai_khoan" method="POST" id="info-form">
                        <div class="mb-3"><label for="email" class="form-label">Địa chỉ Email</label><input type="email" id="email" class="form-control" value="<?= htmlspecialchars($user_info['email']) ?>" readonly></div>
                        <div class="mb-3"><label for="fullname" class="form-label">Họ và tên</label><input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($user_info['fullname']) ?>"></div>
                        <div class="mb-3"><label for="phone_number" class="form-label">Số điện thoại</label><input type="text" id="phone_number" name="phone_number" class="form-control" value="<?= htmlspecialchars($user_info['phone_number'] ?? '') ?>"></div>
                        <div class="mb-3"><label for="address" class="form-label">Địa chỉ</label><input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($user_info['address'] ?? '') ?>"></div>
                    </form>
                </div>
            </div>

            <!-- Form đổi mật khẩu -->
            <div class="card">
                <div class="card-header"><h3>Đổi mật khẩu</h3></div>
                <div class="card-body">
                    <form action="index.php?act=doi_mat_khau" method="POST" id="password-form">
                        <div class="mb-3 password-wrapper"><label for="current_password" class="form-label">Mật khẩu hiện tại</label><input type="password" id="current_password" name="current_password" class="form-control" required><button type="button" class="password-toggle-btn" id="toggleCurrentPassword"><i class="fas fa-eye"></i></button></div>
                        <div class="mb-3 password-wrapper"><label for="new_password" class="form-label">Mật khẩu mới</label><input type="password" id="new_password" name="new_password" class="form-control" required><button type="button" class="password-toggle-btn" id="toggleNewPassword"><i class="fas fa-eye"></i></button></div>
                        <div class="mb-3 password-wrapper"><label for="confirm_new_password" class="form-label">Xác nhận mật khẩu mới</label><input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required><button type="button" class="password-toggle-btn" id="toggleConfirmPassword"><i class="fas fa-eye"></i></button></div>
                    </form>
                </div>
            </div>
        </div>
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

        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    setupPasswordToggle('toggleCurrentPassword', 'current_password');
    setupPasswordToggle('toggleNewPassword', 'new_password');
    setupPasswordToggle('toggleConfirmPassword', 'confirm_new_password');
});
</script>