<?php
// Tệp: login-google.php
session_start();
require_once 'vendor/autoload.php';
// Use PDO-based database connection which provides $pdo
require_once __DIR__ . '/database/database.php';
// Include user model (case-insensitive on Windows but use actual filename)
require_once __DIR__ . '/MoHinh/nguoidung.php';

// Khởi tạo Google Client (lặp lại cấu hình)
$client = new Google_Client();
// VUI LÒNG THAY THẾ BẰNG THÔNG TIN CỦA BẠN
// 1. Truy cập Google Cloud Console: https://console.cloud.google.com/
// 2. Tạo hoặc chọn một dự án.
// 3. Đi tới "APIs & Services" -> "Credentials".
// 4. Tạo một "OAuth 2.0 Client ID" cho "Web application".
// 5. Sao chép Client ID và Client Secret vào đây.
// 6. Đảm bảo "Authorized redirect URIs" được đặt thành: http://localhost/store/login-google.php
$client->setClientId('YOUR_CLIENT_ID.apps.googleusercontent.com'); // <-- THAY THẾ ID CỦA BẠN VÀO ĐÂY
$client->setClientSecret('YOUR_CLIENT_SECRET'); // <-- THAY THẾ SECRET CỦA BẠN VÀO ĐÂY
$client->setRedirectUri('http://localhost/store/login-google.php');

if (isset($_GET['code'])) {
    // Lấy access token từ mã code
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Ensure Google Service class exists (vendor packages may be missing)
    if (!class_exists('Google_Service_Oauth2')) {
        // Graceful failure: redirect back with error
        header('Location: index.php?act=dang_nhap&error=google_sdk_missing');
        exit;
    }

    // Lấy thông tin người dùng từ Google
    $oauth2 = new Google_Service_Oauth2($client);
    $google_account_info = $oauth2->userinfo->get();
    
    $email = $google_account_info->email;
    $fullname = $google_account_info->name;
    // $google_id = $google_account_info->id;

    // Bắt đầu xử lý logic với database
    $userModel = new NguoiDung($pdo);
    
    // 1. Kiểm tra xem email đã tồn tại trong database chưa
    $user = $userModel->findUserByEmail($email);

    if ($user) {
        // Nếu đã tồn tại, tiến hành đăng nhập
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_fullname'] = $user['fullname'];
        $_SESSION['user_role'] = $user['role'];
    } else {
        // Nếu chưa tồn tại, tạo tài khoản mới
        $newUserId = $userModel->registerFromGoogle($fullname, $email);
        if ($newUserId) {
            // Đăng nhập với tài khoản vừa tạo
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['user_fullname'] = $fullname;
            $_SESSION['user_role'] = 'customer'; // Mặc định là customer
        } else {
            // Xử lý lỗi nếu không tạo được tài khoản
            header('Location: index.php?act=dang_ky&error=google_register_failed');
            exit;
        }
    }
    
    // Chuyển hướng về trang chủ sau khi đăng nhập thành công
    header('Location: index.php?act=trangchu');
    exit;
} else {
    // Nếu không có mã code, quay về trang đăng nhập
    header('Location: index.php?act=dang_nhap');
    exit;
}
?>