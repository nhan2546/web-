<?php
// File: api.php

// --- PHẦN XỬ LÝ CORS VÀ HEADERS ---
$allowed_origins = [
    'https://gemini-chat-web.onrender.com', // Frontend của bạn trên Render
    'http://localhost:3000',                 // (Tùy chọn) Nếu bạn chạy frontend local
    'http://localhost:5173'                  // (Tùy chọn) Nếu bạn dùng Vite
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Xử lý yêu cầu OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// --- HẾT PHẦN HEADERS ---


$response = ['products' => []];

// --- PHẦN 1: KẾT NỐI CƠ SỞ DỮ LIỆU ---
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASSWORD');
$db_port = getenv('DB_PORT');

// Kiểm tra biến môi trường
if (!$db_host || !$db_name || !$db_user || !$db_pass || !$db_port) {
    http_response_code(500);
    $response['error'] = "Database configuration is missing on the server.";
    echo json_encode($response);
    exit();
}

// Kiểm tra tham số 'q'
if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    http_response_code(400);
    $response['error'] = "Missing search query parameter 'q'.";
    echo json_encode($response);
    exit();
}

// --- PHẦN 2: TRUY VẤN DỮ LIỆU (ĐÃ SỬA CHÍNH XÁC) ---
$search_query = trim($_GET['q']);

try {
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    $conn = new PDO($dsn, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Câu lệnh SQL này khớp 100% với tệp store_db.sql của bạn
    $sql = "SELECT
                p.id,
                p.name,
                p.price,          -- Giá gốc
                p.sale_price,     -- Giá khuyến mãi (nếu có)
                p.description,    -- Mô tả
                p.quantity,       -- ĐÃ SỬA: Lấy cột 'quantity' thay vì 'stock_quantity'
                c.name AS category_name -- Tên danh mục
            FROM
                products p
            LEFT JOIN
                categories c ON p.category_id = c.id
            WHERE
                (p.name LIKE :query OR p.description LIKE :query) -- Tìm theo tên HOẶC mô tả
                AND p.quantity > 0 -- ĐÃ SỬA: Chỉ lấy sản phẩm còn hàng
            LIMIT 10"; // Giới hạn 10 kết quả

    $stmt = $conn->prepare($sql);
    $like_query = "%" . $search_query . "%";
    $stmt->bindParam(':query', $like_query);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        $response['products'] = $results;
    }

} catch(PDOException $e) {
    http_response_code(500);
    $response['error'] = "Database error: " . $e->getMessage();
}

$conn = null; // Đóng kết nối

// Trả về kết quả cuối cùng
echo json_encode($response);

?>
