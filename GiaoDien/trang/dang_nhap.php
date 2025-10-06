<div class="container auth-page-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-6">
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
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Vui lòng nhập một địa chỉ email hợp lệ.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">Vui lòng nhập mật khẩu.</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                            <label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="index.php?act=quen_mat_khau" class="forgot-password-link ms-auto">Quên mật khẩu?</a>
                    </div>
                </form>
                <div class="divider-text">hoặc</div>
                <div class="d-grid">
                    <a href="<?php echo $google_login_url ?? 'https://accounts.google.com/v3/signin/identifier?dsh=S-1053462655%3A1759761351987161&flowEntry=ServiceLogin&flowName=GlifWebSignIn&ifkv=AfYwgwVmp6ePggGNb6Hb9MBYUvF5SdXZ6fKLB3eGXNhYpG-lZAHoQBDeJiZrNGXH38UVsKt_EV0_'; ?>" class="btn btn-google">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16" style="vertical-align: -2px; margin-right: 8px;">
                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.25C2.999 7.58 2.5 8.983 2.5 10.5s.499 2.92 1.008 3.584C4.14 15.592 5.913 16.5 8 16.5c1.834 0 3.356-.922 4.19-2.215l.002-.002c.787-1.31 1.3-2.955 1.3-4.785a9.426 9.426 0 0 0-.055-1.958H8V6.558h7.545z"/>
                        </svg>
                        Đăng nhập với Google
                    </a>
                </div>
                <div class="auth-form-footer text-center">
                    <p>Chưa có tài khoản? <a href="index.php?act=dang_ky">Đăng ký ngay</a></p>
                </div>
            </div>
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