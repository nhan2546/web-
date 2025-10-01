<?php
session_start();
require 'database/database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy fullname 
    $fullname = $_POST['fullname']; 
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kiểm tra xem email đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        die("Email này đã được đăng ký. Vui lòng sử dụng email khác.");
    }

    // Mã hóa mật khẩu
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Thêm người dùng mới vào CSDL với cột `fullname`
    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    
    // Lưu ý: Tên cột mật khẩu trong CSDL của bạn là `password`, không phải `password_hash`
    if ($stmt->execute([$fullname, $email, $password_hash])) {
        header('Location: login.php');
        exit();
    } else {
        die("Đã có lỗi xảy ra. Vui lòng thử lại.");
    }
}
?>