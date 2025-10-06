
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Đăng ký tài khoản</h3>
                </div>
                <div class="card-body">
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
                    <form action="index.php?act=xu_ly_dang_ky" method="POST">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Đăng ký</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Đã có tài khoản? <a href="index.php?act=dang_nhap">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>