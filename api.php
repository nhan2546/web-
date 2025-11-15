<?php
// File: api.php

// --- PHẦN XỬ LÝ CORS VÀ HEADERS (ĐÃ GỘP VÀ SỬA LỖI) ---
$allowed_origins = [
    'https://gemini-chat-web.onrender.com', // Frontend của bạn trên Render
    'http://localhost:3000',                 // (Tùy chọn) Nếu bạn chạy frontend local
    'http://localhost:5173'                  // (Tùy chọn) Nếu bạn dùng Vite
];

// 1. Chỉ cho phép nếu nguồn gốc yêu cầu nằm trong danh sách
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

// 2. Các header bắt buộc khác
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS"); // Thêm OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 3. Xử lý yêu cầu OPTIONS (preflight) mà trình duyệt gửi trước
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(); // Dừng lại ở đây, không chạy mã bên dưới
}
// --- HẾT PHẦN HEADERS ---


// Khởi tạo một mảng để chứa kết quả trả về
$response = ['products' => []];

// --- PHẦN 1: KẾT NỐI CƠ SỞ DỮ LIỆU (Lấy từ biến môi trường của Render) ---
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASSWORD');
$db_port = getenv('DB_PORT');

// Kiểm tra xem các biến môi trường có tồn tại không
if (!$db_host || !$db_name || !$db_user || !$db_pass || !$db_port) {
    http_response_code(500);
    $response['error'] = "Database configuration is missing on the server.";
    echo json_encode($response);
    exit();
}

// Kiểm tra xem có tham số 'q' (query) được gửi lên không
if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    http_response_code(400);
    $response['error'] = "Missing search query parameter 'q'.";
    echo json_encode($response);
    exit();
}

// --- PHẦN 2: TRUY VẤN DỮ LIỆU ---
$search_query = trim($_GET['q']);

try {
    // Tạo chuỗi kết nối PDO (Data Source Name)
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    $conn = new PDO($dsn, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- CÂU LỆNH SQL (BẠN PHẢI KIỂM TRA LẠI PHẦN NÀY) ---
    // QUAN TRỌNG: Tên bảng (products, categories) và tên cột (p.name, p.price, v.v.)
    // phải khớp 100% với cơ sở dữ liệu của bạn.
    $sql = "SELECT
                p.id,
                p.name,
                p.price,
                p.sale_price,
                p.description,
                p.stock_quantity,
                c.name AS category_name
            FROM
                products p
            LEFT JOIN
                categories c ON p.category_id = c.id
            WHERE
                (p.name LIKE :query OR p.description LIKE :query)
                AND p.stock_quantity > 0
            LIMIT 10"; // Giới hạn 10 kết quả để AI không bị quá tải

    $stmt = $conn->prepare($sql);
    $like_query = "%" . $search_query . "%";
    $stmt->bindParam(':query', $like_query);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        $response['products'] = $results;
    }
    // Nếu không có kết quả, $response['products'] sẽ là một mảng rỗng (vẫn hợp lệ)

} catch(PDOException $e) {
    http_response_code(500);
    $response['error'] = "Database error: " . $e->getMessage();
}

$conn = null; // Đóng kết nối

// Trả về kết quả cuối cùng dưới dạng JSON
echo json_encode($response);

?>
