<?php
/**
 * Tệp: api.php
 * Endpoint để AI Sales Assistant tìm kiếm sản phẩm.
 * Sử dụng class CSDL để kết nối và truy vấn database.
 */

// --- 1. Cho phép truy cập từ frontend (CORS) ---
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Xử lý request pre-flight của trình duyệt
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// --- 2. Nạp class CSDL và khởi tạo kết nối ---
// Đảm bảo tệp CSDL.php nằm cùng cấp hoặc có đường dẫn đúng
require_once 'CSDL.php';

try {
    // Khởi tạo đối tượng CSDL, nó sẽ tự động kết nối
    $db = new CSDL();
} catch (Exception $e) {
    // Nếu kết nối thất bại, trả về lỗi 500
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Không thể khởi tạo kết nối cơ sở dữ liệu: ' . $e->getMessage()]);
    exit;
}

// --- 3. Lấy từ khóa tìm kiếm từ URL (?q=...) ---
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

// Nếu không có từ khóa, trả về mảng rỗng
if ($searchQuery === '') {
    header('Content-Type: application/json');
    echo json_encode(['products' => []]);
    exit;
}

// --- 4. Xây dựng và thực thi câu lệnh SQL ---
// QUAN TRỌNG: Câu lệnh này đã được sửa để không lấy cột 'promotion'
// không tồn tại, giúp tránh lỗi "Unknown column".
$sql = <<<SQL
SELECT
    id,
    name,
    price,
    sale_price,
    description
FROM
    products
WHERE
    name LIKE :query
ORDER BY
    name
LIMIT 10
SQL;

// Chú thích: SAU KHI bạn đã thêm cột 'promotion' vào database,
// bạn có thể dùng câu lệnh đầy đủ hơn ở dưới đây:
/*
$sql = <<<SQL
SELECT
    id,
    name,
    price,
    sale_price,
    description,
    promotion
FROM
    products
WHERE
    name LIKE :query
ORDER BY
    name
LIMIT 10
SQL;
*/

try {
    // Sử dụng phương thức `read` từ class CSDL của bạn
    $results = $db->read($sql, [':query' => "%$searchQuery%"]);
    
    // --- 5. Trả về kết quả dưới dạng JSON ---
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['products' => $results]);

} catch (PDOException $e) {
    // Nếu truy vấn có lỗi, trả về lỗi 500
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage()]);
    exit;
}
?>
