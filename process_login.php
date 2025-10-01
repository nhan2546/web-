<?php
// Luôn bắt đầu session ở đầu file để có thể làm việc với biến $_SESSION
session_start();

// Nhúng file kết nối CSDL. Dựa vào cấu trúc thư mục của bạn, đường dẫn là 'database/database.php'
require 'database/database.php';

// Kiểm tra xem form đã được gửi đi bằng phương thức POST chưa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Tìm người dùng trong CSDL dựa trên email
    //    Đồng thời kiểm tra xem tài khoản có đang ở trạng thái 'active' không
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Nếu tìm thấy người dùng VÀ mật khẩu nhập vào khớp với mật khẩu đã mã hóa trong CSDL
    //    Lưu ý: CSDL của bạn đặt tên cột là 'password' nhưng nó phải chứa mật khẩu đã được mã hóa.
    if ($user && password_verify($password, $user['password'])) {
        
        // Đăng nhập thành công!
        
        // 3. Lưu các thông tin cần thiết của người dùng vào Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];

        // 4. Chuyển hướng người dùng dựa trên vai trò (role) của họ
        if ($user['role'] === 'admin') {
            // Nếu là admin, chuyển đến trang dashboard của admin
            header('Location: admin/dashboard.php');
        } else {
            // Nếu là khách hàng hoặc nhân viên, chuyển về trang chủ
            header('Location: index.php');
        }
        exit(); // Dừng chạy code sau khi chuyển hướng

    } else {
        // Đăng nhập thất bại, chuyển về trang login kèm theo thông báo lỗi
        $error_message = "Email hoặc mật khẩu không chính xác, hoặc tài khoản của bạn đã bị khóa.";
        header('Location: login.php?error=' . urlencode($error_message));
        exit();
    }
} else {
    // Nếu ai đó cố gắng truy cập file này trực tiếp mà không qua form, đá về trang chủ
    header('Location: index.php');
    exit();
}
?>