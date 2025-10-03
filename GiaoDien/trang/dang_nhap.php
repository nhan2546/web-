<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Đăng nhập</h3>
                </div>
                <div class="card-body">
                    <?php
                    // Hiển thị thông báo lỗi nếu có
                    if (isset($_GET['error'])) {
                        $error_msg = '';
                        switch ($_GET['error']) {
                            case 'empty_fields':
                                $error_msg = 'Vui lòng điền đầy đủ thông tin.';
                                break;
                            case 'invalid_credentials':
                                $error_msg = 'Email hoặc mật khẩu không chính xác.';
                                break;
                        }
                        if ($error_msg) {
                            echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
                        }
                    }
                    // Hiển thị thông báo thành công
                    if (isset($_GET['success']) && $_GET['success'] == 'registered') {
                        echo '<div class="alert alert-success" role="alert">Đăng ký thành công! Vui lòng đăng nhập.</div>';
                    }
                    ?>
                    <form action="index.php?act=xu_ly_dang_nhap" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                        </div>
                        <div class="mb-3 form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                            <label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Chưa có tài khoản? <a href="index.php?act=dang_ky">Đăng ký ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>