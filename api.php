<?php
// File: api.php
// API endpoint TOÀN DIỆN cho Gemini AI

// 1. Thiết lập Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// 2. Tải CSDL
require_once __DIR__ . '/MoHinh/CSDL.php';

// 3. Khởi tạo CSDL
try {
    $db = new CSDL();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Không thể kết nối CSDL: ' . $e->getMessage()]);
    exit;
}

// 4. Lấy hành động (action) mà AI yêu cầu
$action = $_GET['action'] ?? '';

// 5. Bộ định tuyến (Router) cho các action
switch ($action) {
    case 'find_products':
        findProducts($db);
        break;
        
    case 'check_order_status':
        checkOrderStatus($db);
        break;
        
    case 'find_products_by_category':
        findProductsByCategory($db);
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Hành động không hợp lệ.']);
}

// ===================================================================
// CÁC HÀM CÔNG CỤ (TOOLS)
// ===================================================================

/**
 * CÔNG CỤ 1: TÌM SẢN PHẨM
 */
function findProducts($db) {
    $query = $_GET['query'] ?? '';
    $filter = $_GET['filter'] ?? '';

    // NÂNG CẤP: Lấy cả 'stock_quantity'
    $sql = "SELECT id, name, description, price, sale_price, image_url, stock_quantity FROM products WHERE 1=1";
    $params = [];

    if (!empty($query)) {
        $sql .= " AND name LIKE ?";
        $params[] = '%' . $query . '%';
    }

    if ($filter === 'discounted') {
        $sql .= " AND sale_price > 0 AND sale_price < price";
    }

    $products = $db->read($sql, $params);
    echo json_encode(['products' => $products]);
}

/**
 * CÔNG CỤ 2: KIỂM TRA ĐƠN HÀNG
 */
function checkOrderStatus($db) {
    $order_id = $_GET['order_id'] ?? '';

    if (empty($order_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Thiếu mã đơn hàng (order_id).']);
        return;
    }

    $sql = "SELECT id, status, order_date, total_amount, payment_method FROM orders WHERE id = ?";
    $params = [$order_id];
    
    $order = $db->read($sql, $params);

    if (empty($order)) {
        echo json_encode(['error' => 'Không tìm thấy đơn hàng với mã này.']);
    } else {
        echo json_encode(['order' => $order[0]]);
    }
}

/**
 * CÔNG CỤ 3: TÌM SẢN PHẨM THEO DANH MỤC (ĐÃ SỬA LỖI TỒN KHO)
 */
function findProductsByCategory($db) {
    $category_slug = $_GET['category_slug'] ?? '';

    if (empty($category_slug)) {
        http_response_code(400);
        echo json_encode(['error' => 'Thiếu mã danh mục (category_slug).']);
        return;
    }

    // SỬA LỖI Ở ĐÂY: BỎ ĐIỀU KIỆN 'AND p.stock_quantity > 0'
    $sql = "SELECT p.id, p.name, p.price, p.sale_price, p.image_url, p.stock_quantity 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE c.slug = ? 
            LIMIT 10"; // Tăng LIMIT để hiện thị được nhiều sản phẩm hơn
    $params = [$category_slug];

    $products = $db->read($sql, $params);
    echo json_encode(['products' => $products]);
}

?>
