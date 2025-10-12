<div class="auth-page-wrapper">
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
            <div class="mb-3 position-relative">
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Họ và Tên" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Xác nhận Mật khẩu" required>
            </div>
            <div class="main-login-btn-wrapper mb-3">
                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </div>
        </form>
        <div class="auth-form-footer text-center">
            <p>Đã có tài khoản? <a href="index.php?act=dang_nhap">Đăng nhập ngay</a></p>
        </div>
    </div>
</div>