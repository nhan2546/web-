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
            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <div style="position: relative;"> <!-- Bọc trong một div có position: relative -->
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                    </button>
                </div>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Đăng nhập</button>
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
        <div class="d-grid gap-3">
            <!-- Nút Đăng nhập với Facebook -->            <a href="index.php?act=login_facebook" class="btn btn-facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16" style="vertical-align: -2px; margin-right: 8px;">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                </svg>
                Đăng nhập với Facebook
            </a>
            <!-- Nút Đăng nhập với Google -->
            <a href="<?php echo $google_login_url ?? 'https://accounts.google.com/v3/signin/identifier?dsh=S-1053462655%3A1759761351987161&flowEntry=ServiceLogin&flowName=GlifWebSignIn&ifkv=AfYwgwVmp6ePggGNb6Hb9MBYUvF5SdXZ6fKLB3eGXNhYpG-lZAHoQBDeJiZrNGXH38UVsKt_EV0_'; ?>" class="btn btn-google">
                <span class="btn-icon">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google logo" width="18" height="18">
                </span>
                Đăng nhập với Google
            </a>
            <!-- Nút Đăng nhập bằng SĐT -->
            <a href="index.php?act=login_phone" class="btn btn-phone">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16" style="vertical-align: -2px; margin-right: 8px;">
                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.28 1.465l-2.135 2.136a11.942 11.942 0 0 0 5.583 5.583l2.136-2.135a1.465 1.465 0 0 1 1.465.28l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                </svg>
                Đăng nhập bằng số điện thoại
            </a>
        </div>
        <div class="auth-form-footer text-center">
            <p>Chưa có tài khoản? <a href="index.php?act=dang_ky">Đăng ký ngay</a></p>
        </div>
    </div>
</div>

<?php
/*
require_once __DIR__ . '/../../vendor/autoload.php';
$client = new Google_Client();
$client->setClientId('789393795701-ih9c3nq1g3sb3fprfohi498r8sussgbc.apps.googleusercontent.com'); // <-- THAY BẰNG CLIENT ID CỦA BẠN
$client->setClientSecret('YOUR_CLIENT_SECRET');                   // <-- THAY BẰNG CLIENT SECRET CỦA BẠN
$client->setRedirectUri('http://localhost/store/login-google.php');
$client->addScope("email");
$client->addScope("profile");
$google_login_url = $client->createAuthUrl();
*/
?>