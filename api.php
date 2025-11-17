<?php
// File: api.php
// API endpoint cho Gemini AI tìm kiếm sản phẩm

// 1. Set header là JSON để frontend hiểu
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép gọi từ domain khác (Vite dev server)

// 2. Tải lớp CSDL
// (Sử dụng đường dẫn tương đối giống như file index.php của bạn)
require_once __DIR__ . '/MoHinh/CSDL.php';

try {
    // 3. Khởi tạo kết nối CSDL
    $db = new CSDL();

    // 4. Lấy tham số từ frontend (geminiService.ts)
    $query = $_GET['q'] ?? ''; // Tên sản phẩm, ví dụ: "iphone"
    $filter = $_GET['filter'] ?? ''; // Lọc, ví dụ: "discounted"

    // 5. Xây dựng câu truy vấn SQL
    // Chọn các cột mà AI cần để trả lời
    $sql = "SELECT id, name, description, price, sale_price, image_url FROM products WHERE 1=1";
    $params = [];

    // 5a. Thêm điều kiện tìm kiếm theo tên (nếu có)
    if (!empty($query)) {
        // Dùng cột 'name' từ bảng 'products'
        $sql .= " AND name LIKE ?";
        $params[] = '%' . $query . '%';
    }

    // 5b. Thêm điều kiện lọc sản phẩm giảm giá (nếu có)
    if ($filter === 'discounted') {
        // Dựa trên cấu trúc bảng 'products'
        // Sản phẩm giảm giá là sản phẩm có sale_price > 0 VÀ sale_price < price
        // (Ví dụ: AirPods 4 có price=3790000 và sale_price=3500000)
        $sql .= " AND sale_price > 0 AND sale_price < price";
    }

    // 6. Thực thi truy vấn bằng phương thức read() của lớp CSDL
    $products = $db->read($sql, $params);
    
    // 7. Trả về kết quả dưới dạng JSON mà frontend mong đợi
    echo json_encode(['products' => $products]);

} catch (PDOException $e) {
    // Xử lý lỗi CSDL
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Xử lý các lỗi chung khác
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}

?>
