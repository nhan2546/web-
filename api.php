<?php
// File: api.php

// --- QUAN TRỌNG: CHO PHÉP CHATBOT GỌI ĐẾN API NÀY (SỬA LỖI CORS) ---
// Thay thế bằng URL chatbot của bạn nếu khác
header("Access-Control-Allow-Origin: https://gemini-chat-web.onrender.com");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- PHẦN 1: KẾT NỐI CƠ SỞ DỮ LIỆU (ĐÃ ĐIỀN SẴN TỪ ẢNH CỦA BẠN) ---
$db_host = "switchyard.proxy.rlwy.net";
$db_name = "railway";
$db_user = "root";
$db_pass = "pNGodoOoiBfmwiaLmwE1FEyTjqELORte";
$db_port = "27755";


// Khởi tạo một mảng để chứa kết quả trả về
$response = ['products' => []];

// Kiểm tra xem có tham số 'q' (query) được gửi lên không
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $search_query = trim($_GET['q']);

    try {
        // Tạo chuỗi kết nối PDO (Data Source Name)
        $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
        $conn = new PDO($dsn, $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // --- PHẦN 2: CÂU LỆNH SQL (BẠN CẦN SỬA PHẦN NÀY) ---
        // QUAN TRỌNG: Hãy thay thế `products`, `name`, `price`, `description`
        // bằng tên bảng và tên các cột tương ứng trong cơ sở dữ liệu của bạn.
        $stmt = $conn->prepare("SELECT
                            ten_sp AS name,         -- Lấy cột ten_sp và đổi tên thành 'name'
                            gia_ban AS price,       -- Lấy cột gia_ban và đổi tên thành 'price'
                            mo_ta_ngan AS description -- Lấy cột mo_ta_ngan và đổi tên thành 'description'
                        FROM
                            san_pham                -- Từ bảng san_pham
                        WHERE
                            ten_sp LIKE :query");    -- Tìm kiếm 

        // Dùng LIKE để tìm kiếm tương đối (ví dụ: tìm "iphone" sẽ ra "iphone 15 pro")
        $like_query = "%" . $search_query . "%";
        $stmt->bindParam(':query', $like_query);
        
        $stmt->execute();

        // Lấy tất cả kết quả dưới dạng mảng kết hợp (associative array)
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            $response['products'] = $results;
        }

    } catch(PDOException $e) {
        // Trả về lỗi nếu không kết nối được hoặc có lỗi SQL
        http_response_code(500); // Internal Server Error
        $response['error'] = "Database error: " . $e->getMessage();
    }

    $conn = null; // Đóng kết nối

} else {
    // Trả về lỗi nếu không có từ khóa tìm kiếm
    http_response_code(400); // Bad Request
    $response['error'] = "Missing search query parameter 'q'.";
}

// Trả về kết quả cuối cùng dưới dạng JSON
echo json_encode($response);

?>

