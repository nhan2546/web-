<?php
// Tệp: includes/db_connection.php

// 1. Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";    // Thường là "localhost" hoặc "127.0.0.1"
$username = "root";           // Tên người dùng MySQL, mặc định của XAMPP là "root"
$password = "";               // Mật khẩu MySQL, mặc định của XAMPP là để trống
$dbname = "store_db";         // Tên cơ sở dữ liệu bạn đã tạo

// 2. Tạo kết nối bằng MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// 3. Kiểm tra kết nối
if ($conn->connect_error) {
    // Nếu có lỗi, dừng chương trình và hiển thị thông báo lỗi
    die("Kết nối thất bại: " . $conn->connect_error);
}

// 4. Thiết lập bảng mã UTF-8 để hiển thị tiếng Việt chính xác
// Bảng mã này phải khớp với 'utf8mb4_unicode_ci' trong tệp .sql của bạn
$conn->set_charset("utf8mb4");

// Nếu mọi thứ thành công, biến $conn sẽ giữ kết nối và sẵn sàng để sử dụng
// trong các tệp khác.
?>