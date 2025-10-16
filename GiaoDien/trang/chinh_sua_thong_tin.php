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
    <div class="account-form-container">
        <a href="index.php?act=thong_tin_tai_khoan" class="back-to-dashboard-link">&larr; Quay lại trang tài khoản</a>
        <h1 class="mb-4">Chỉnh sửa thông tin</h1>

        <!-- Hiển thị thông báo -->
        <?php if ($success_msg): ?>
            <div class="alert alert-success mb-4"><?= $success_msg ?></div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="alert alert-danger mb-4"><?= $error_msg ?></div>
        <?php endif; ?>

        <!-- Form cập nhật thông tin cá nhân -->
        <div class="card mb-4">
            <div class="card-header"><h3>Thông tin cá nhân</h3></div>
            <div class="card-body">
                <form action="index.php?act=cap_nhat_tai_khoan" method="POST">
                    <div class="mb-3"><label for="email" class="form-label">Địa chỉ Email</label><input type="email" id="email" class="form-control" value="<?= htmlspecialchars($user_info['email']) ?>" readonly></div>
                    <div class="mb-3"><label for="fullname" class="form-label">Họ và tên</label><input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($user_info['fullname']) ?>"></div>
                    <div class="mb-3"><label for="phone_number" class="form-label">Số điện thoại</label><input type="text" id="phone_number" name="phone_number" class="form-control" value="<?= htmlspecialchars($user_info['phone_number'] ?? '') ?>"></div>
                    <div class="mb-3"><label for="address" class="form-label">Địa chỉ</label><input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($user_info['address'] ?? '') ?>"></div>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>

        <!-- Form đổi mật khẩu -->
        <div class="card">
            <div class="card-header"><h3>Đổi mật khẩu</h3></div>
            <div class="card-body">
                <form action="index.php?act=doi_mat_khau" method="POST">
                    <div class="mb-3"><label for="current_password" class="form-label">Mật khẩu hiện tại</label><input type="password" id="current_password" name="current_password" class="form-control" required></div>
                    <div class="mb-3"><label for="new_password" class="form-label">Mật khẩu mới</label><input type="password" id="new_password" name="new_password" class="form-control" required></div>
                    <div class="mb-3"><label for="confirm_new_password" class="form-label">Xác nhận mật khẩu mới</label><input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required></div>
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>