<?php
require_once __DIR__ . '/MoHinh/CSDL.php';

try {
    $db = new CSDL();
    $pdo = $db->conn;

    // New categories
    $categories = [
        'Điện thoại',
        'Laptop',
        'Tablet',
        'Âm thanh',
        'Phụ kiện',
        'Thu cũ đổi mới'
    ];

    // Truncate the table to reset it
    $pdo->exec('TRUNCATE TABLE categories');

    // Insert new categories
    $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
    foreach ($categories as $category) {
        $stmt->execute([$category]);
    }

    echo 'Các danh mục đã được cập nhật thành công! Bạn có thể xóa tệp này đi.';

} catch (PDOException $e) {
    die("Lỗi kết nối hoặc truy vấn: " . $e->getMessage());
}
?>