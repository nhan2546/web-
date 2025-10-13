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

// Lấy chữ cái đầu của tên để làm avatar mặc định
$first_letter = mb_substr($user_info['fullname'], 0, 1, 'UTF-8');
?>

<div class="account-page-wrapper">
    <h1 class="mb-5">Thông tin tài khoản</h1>

    <!-- Hiển thị thông báo -->
    <?php if ($success_msg): ?>
        <div class="alert alert-success mb-4"><?= $success_msg ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger mb-4"><?= $error_msg ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Cột trái: Các form cập nhật -->
        <div class="col-lg-8">
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

        <!-- Cột phải: Tóm tắt thông tin -->
        <div class="col-lg-4">
            <div class="card profile-summary-card text-center">
                <form id="avatar-form" action="index.php?act=cap_nhat_avatar" method="post" enctype="multipart/form-data" class="d-none">
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" onchange="document.getElementById('avatar-form').submit();">
                </form>

                <div class="profile-avatar-wrapper mx-auto mb-3">
                    <div class="profile-avatar">
                        <?php if (!empty($user_info['avatar_url'])): ?>
                            <img src="TaiLen/avatars/<?= htmlspecialchars($user_info['avatar_url']) ?>" alt="Avatar">
                        <?php else: ?>
                            <span><?= htmlspecialchars(strtoupper($first_letter)) ?></span>
                        <?php endif; ?>
                    </div>
                    <label for="avatar-input" class="avatar-upload-overlay" title="Đổi ảnh đại diện">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16"><path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828-.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/></svg>
                    </label>
                </div>

                <h4 class="profile-name"><?= htmlspecialchars($user_info['fullname']) ?></h4>
                <p class="profile-email text-muted"><?= htmlspecialchars($user_info['email']) ?></p>

                <div class="d-grid gap-2 mt-3">
                    <a href="index.php?act=lich_su_mua_hang" class="btn btn-outline-primary">Lịch sử mua hàng</a>
                    <a href="index.php?act=dang_xuat" class="btn btn-outline-danger">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
</div>