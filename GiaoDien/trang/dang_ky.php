<div class="container auth-page-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-6">
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
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                        <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Vui lòng nhập một địa chỉ email hợp lệ.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <div class="invalid-feedback">Mật khẩu phải có ít nhất 6 ký tự.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">Mật khẩu xác nhận không khớp.</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Đăng ký</button>
                    </div>
                </form>
                <div class="auth-form-footer text-center">
                    <p>Đã có tài khoản? <a href="index.php?act=dang_nhap">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>