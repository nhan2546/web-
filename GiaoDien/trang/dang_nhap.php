<?php
// Lấy URL chuyển hướng từ tham số GET
$redirect_url = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : '';
?>
<div class="auth-page-wrapper">
    <div class="auth-form-container">
        <h3 class="auth-form-title">Đăng nhập</h3>
        <?php
            if (isset($_GET['error'])) { 
                $error_msg = '';
                switch ($_GET['error']) {
                    case 'empty_fields':
                        $error_msg = 'Vui lòng điền đầy đủ email và mật khẩu.';
                        break;
                    case 'invalid_credentials':
                        $error_msg = 'Email hoặc mật khẩu không chính xác.';
                        break;
                    case 'unauthorized':
                        $error_msg = 'Bạn không có quyền truy cập trang này.';
                        break;
                }
                if ($error_msg) {
                    echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
                }
            }
            if (isset($_GET['success']) && $_GET['success'] == 'registered') {
                echo '<div class="alert alert-success" role="alert">Đăng ký thành công! Vui lòng đăng nhập.</div>';
            }
        ?>
        <form action="index.php?act=xu_ly_dang_nhap" method="POST" id="login-form" novalidate>
            <!-- Thêm trường hidden để lưu lại URL cần chuyển hướng đến -->
            <input type="hidden" name="redirect" value="<?= $redirect_url ?>">

            <div class="mb-3 position-relative">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="d-flex justify-content-end mb-3">
                <a href="index.php?act=quen_mat_khau" class="forgot-password-link">Quên mật khẩu?</a>
            </div>
            <div class="main-login-btn-wrapper mb-3">
                <button type="submit" class="btn btn-primary">Đăng nhập</button>
            </div>
        </form>
        <div class="divider-text my-4">hoặc</div>
        <div class="social-login-wrapper">
            <?php
                // Tạo URL đăng nhập Google
                // require_once 'vendor/autoload.php';
                // $client = new Google_Client();
                // $client->setClientId('YOUR_CLIENT_ID');
                // $client->setRedirectUri('http://localhost/web-/login-google.php');
                // $client->addScope("email");
                // $client->addScope("profile");
                // $google_login_url = $client->createAuthUrl();
            ?>
            <!-- <a href="<?php //echo $google_login_url; ?>" class="btn btn-light w-100 mb-2"><span class="btn-icon"><img src="TaiNguyen/hinh_anh/google-icon.svg" alt="Google"></span> Đăng nhập với Google</a> -->
        </div>
        <div class="auth-form-footer text-center">
            <p>Chưa có tài khoản? <a href="index.php?act=dang_ky">Đăng ký ngay</a></p>
        </div>
    </div>
</div>