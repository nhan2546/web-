<?php
$host = 'localhost';
$dbname = 'store_db'; 
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Thiết lập chế độ báo lỗi để dễ dàng gỡ lỗi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Nếu kết nối thất bại, hiển thị lỗi và dừng chương trình
    die("Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage());
}
?>