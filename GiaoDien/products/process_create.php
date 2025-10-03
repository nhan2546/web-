<?php
session_start();
require_once '../../includes/db_connection.php';
require_once '../../includes/product_functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Xử lý upload ảnh
    $image_url = 'default.jpg'; // Ảnh mặc định nếu upload lỗi
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Tạo thư mục uploads ở thư mục gốc nếu chưa có
        $upload_dir = '../../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $image_name;
        }
    }

    // 2. Chuẩn bị dữ liệu để thêm vào CSDL
    $product_data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'stock_quantity' => $_POST['stock_quantity'],
        'category_id' => $_POST['category_id'] ?? null,
        'image_url' => $image_url
    ];

    // 3. Gọi hàm từ "Model" để thêm sản phẩm
    if (addProduct($conn, $product_data)) {
        $_SESSION['message'] = "Thêm sản phẩm thành công!";
    } else {
        $_SESSION['message'] = "Thêm sản phẩm thất bại.";
    }

    // 4. Chuyển hướng về trang quản lý
    header("Location: ../manage_products.php"); // Hoặc dashboard.php
    exit();
}
?>