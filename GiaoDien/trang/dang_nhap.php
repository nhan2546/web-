<div class="auth-page-wrapper">
    <div class="auth-form-container">
        <h3 class="auth-form-title">Đăng nhập</h3>
        <?php
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
        <form action="index.php?act=xu_ly_dang_nhap" method="POST" id="login-form" novalidate>
            <div class="mb-3 position-relative">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="main-login-btn-wrapper mb-3">
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </div>
            <div class="d-flex justify-content-center align-items-center mt-3 gap-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
                </div>
                <a href="index.php?act=quen_mat_khau" class="forgot-password-link">Quên mật khẩu?</a>
            </div>
        </form>
        <div class="divider-text my-4">Hoặc đăng nhập bằng</div>
        <div class="social-login-wrapper">
            <div class="d-grid gap-2">
                <!-- Nút Đăng nhập với Facebook -->
                <a href="index.php?act=login_facebook" class="btn btn-facebook">
                     <span class="btn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                     </span>
                     <span>Đăng nhập với Facebook</span>
                 </a>
                 <!-- Nút Đăng nhập với Google -->
                 <a href="<?php echo $google_login_url ?? '#'; ?>" class="btn btn-google">
                     <span class="btn-icon">
                         <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google logo" width="18" height="18">
                     </span>
                     <span>Đăng nhập với Google</span>
                 </a>
                 <!-- Nút Đăng nhập bằng SĐT -->
                 <a href="index.php?act=login_phone" class="btn btn-phone">
                     <span class="btn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.28 1.465l-2.135 2.136a11.942 11.942 0 0 0 5.586 5.586l2.136-2.135a1.745 1.745 0 0 1 1.465-.28l2.305 1.152a1.745 1.745 0 0 1 .163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03.003-2.137.703-2.877L1.885.511z"/></svg>
                     </span>
                     <span>Đăng nhập bằng số điện thoại</span>
                 </a>
            </div>
        </div>
        <div class="auth-form-footer text-center">
            <p>Chưa có tài khoản? <a href="index.php?act=dang_ky">Đăng ký ngay</a></p>
        </div>
    </div>
</div>