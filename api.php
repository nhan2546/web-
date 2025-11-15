<?php
// File: api.php

// --- QUAN TRỌNG: CHO PHÉP CHATBOT GỌI ĐẾN API NÀY (SỬA LỖI CORS) ---
// URL này phải khớp chính xác với URL của ứng dụng chatbot trên Render
header("Access-Control-Allow-Origin: https://gemini-chat-web.onrender.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Trình duyệt sẽ gửi một yêu cầu OPTIONS trước, chúng ta cần xử lý nó
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- PHẦN 1: KẾT NỐI CƠ SỞ DỮ LIỆU (Lấy từ biến môi trường của Render) ---
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASSWORD');
$db_port = getenv('DB_PORT');
// Khởi tạo một mảng để chứa kết quả trả về
$response = ['products' => []];

// Kiểm tra xem có tham số 'q' (query) được gửi lên không
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $search_query = trim($_GET['q']);

    // Kiểm tra xem các biến môi trường có tồn tại không
    if (!$db_host || !$db_name || !$db_user || !$db_pass || !$db_port) {
        http_response_code(500);
        $response['error'] = "Database configuration is missing on the server.";
    } else {
        try {
            // Tạo chuỗi kết nối PDO (Data Source Name)
            $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
            $conn = new PDO($dsn, $db_user, $db_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

           // --- PHẦN 2: CÂU LỆNH SQL (ĐÃ ĐƯỢC CẢI THIỆN) ---
// Câu lệnh này lấy tất cả thông tin quan trọng mà AI cần

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

        } catch(PDOException $e) {
            http_response_code(500);
            $response['error'] = "Database error: " . $e->getMessage();
        }
        $conn = null;
    }
} else {
    http_response_code(400);
    $response['error'] = "Missing search query parameter 'q'.";
}

// Trả về kết quả cuối cùng dưới dạng JSON
echo json_encode($response);

?>
