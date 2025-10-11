<div class="container auth-page-wrapper">
    <div class="auth-form-container">
        <h3 class="auth-form-title">Đăng ký tài khoản</h3>
        <?php
            if (isset($_GET['error'])) { 
                $error_msg = '';
                switch ($_GET['error']) {
                    case 'empty_fields':
                        $error_msg = 'Vui lòng điền đầy đủ thông tin.';
                        break;
                    case 'password_mismatch':
                        $error_msg = 'Mật khẩu xác nhận không khớp.';
                        break;
                    case 'email_exists':
                        $error_msg = 'Email này đã được sử dụng.';
                        break;
                    case 'registration_failed':
                        $error_msg = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
                        break;
                }
                if ($error_msg) {
                    echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
                }
            }
        ?>
        <form action="index.php?act=xu_ly_dang_ky" method="POST" id="register-form" novalidate>
            <div class="row mb-3">
                <label for="fullname" class="col-sm-4 col-form-label">Họ và Tên</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="row mb-4">
                <label for="password" class="col-sm-4 col-form-label">Mật khẩu</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="confirm_password" class="col-sm-4 col-form-label">Xác nhận Mật khẩu</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </div>
        </form>
        <div class="auth-form-footer text-center">
            <p>Đã có tài khoản? <a href="index.php?act=dang_nhap">Đăng nhập ngay</a></p>
        </div>
    </div>
</div>