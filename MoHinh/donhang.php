<?php
// Tệp: STORE/MoHinh/DonHang.php

require_once 'CSDL.php';

class donhang {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    /**
     * Lấy danh sách TẤT CẢ đơn hàng để hiển thị cho admin.
     * Hàm này sẽ nối (JOIN) với bảng `users` để lấy tên khách hàng.
     * @return array Mảng chứa tất cả các đơn hàng.
     */
    public function getAllOrders() {
        $db = new CSDL();
        $sql = "SELECT o.*, u.fullname as customer_name 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.order_date DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách đơn hàng có phân trang, lọc và tìm kiếm.
     * @param string $status Trạng thái cần lọc.
     * @param string $searchTerm Từ khóa tìm kiếm.
     * @return array Mảng chứa các đơn hàng.
     */
    public function getOrders($status = '', $searchTerm = '') {
        $sql = "SELECT o.*, u.fullname as customer_name 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE 1=1"; // Mệnh đề WHERE luôn đúng để dễ dàng nối thêm điều kiện

        $params = [];

        if (!empty($status)) {
            $sql .= " AND o.status = ?";
            $params[] = $status;
        }

        if (!empty($searchTerm)) {
            // Tìm kiếm theo tên khách hàng hoặc mã đơn hàng (không có #)
            $sql .= " AND (u.fullname LIKE ? OR o.id = ?)";
            $search_like = "%" . $searchTerm . "%";
            $params[] = $search_like;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY o.order_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách đơn hàng của một người dùng cụ thể.
     * @param int $userId ID của người dùng.
     * @param string $status Trạng thái cần lọc (tùy chọn).
     * @return array Mảng chứa các đơn hàng của người dùng đó.
     */
    public function getOrdersByUserId($userId, $status = '') {
        $sql = "SELECT * FROM orders WHERE user_id = ?";
        $params = [$userId];

        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY order_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy toàn bộ thông tin chi tiết của MỘT đơn hàng.
     * Bao gồm thông tin đơn hàng, thông tin khách hàng và danh sách sản phẩm trong đơn.
     * @param int $orderId ID của đơn hàng cần xem.
     * @return array Mảng chứa 'order_info' và 'order_items'.
     */
    public function getOrderDetail($orderId) {
        $details = [
            'order_info' => null,
            'order_items' => []
        ];

        // 1. Lấy thông tin chính của đơn hàng và thông tin người dùng
        $sql_info = "SELECT o.*, u.fullname, u.email, u.phone_number, u.address 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     WHERE o.id = ? 
                     LIMIT 1"; 
        $stmt_info = $this->pdo->prepare($sql_info);
        $stmt_info->execute([$orderId]);
        $details['order_info'] = $stmt_info->fetch(PDO::FETCH_ASSOC);

        // 2. Lấy danh sách các sản phẩm trong đơn hàng
        $sql_items = "SELECT od.quantity, od.price, p.name as product_name, p.image_url 
                      FROM order_details od 
                      JOIN products p ON od.product_id = p.id 
                      WHERE od.order_id = ?";
        $stmt_items = $this->pdo->prepare($sql_items);
        $stmt_items->execute([$orderId]);
        $details['order_items'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        return $details;
    }

    /**
     * Tạo một đơn hàng mới từ giỏ hàng.
     * Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu.
     * @param int $userId ID người dùng.
     * @param array $cartItems Mảng các sản phẩm trong giỏ hàng.
     * @param float $totalAmount Tổng số tiền cuối cùng.
     * @param string $shippingAddress Địa chỉ giao hàng.
     * @param string $paymentMethod Phương thức thanh toán.
     * @return int|false ID của đơn hàng mới nếu thành công, ngược lại là false.
     */
    public function createOrder($userId, $cartItems, $totalAmount, $shippingAddress, $paymentMethod) {
        try {
            // Bắt đầu một transaction
            $this->pdo->beginTransaction();

            // 1. Thêm vào bảng `orders`
            $sql_order = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, status) 
                          VALUES (?, ?, ?, ?, 'pending')";
            $stmt_order = $this->pdo->prepare($sql_order);
            $stmt_order->execute([$userId, $totalAmount, $shippingAddress, $paymentMethod]);
            
            // Lấy ID của đơn hàng vừa tạo
            $orderId = $this->pdo->lastInsertId();

            // 2. Thêm vào bảng `order_details` và cập nhật kho
            $sql_details = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_details = $this->pdo->prepare($sql_details);

            $sql_update_stock = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
            $stmt_update_stock = $this->pdo->prepare($sql_update_stock);

            foreach ($cartItems as $item) {
                // Thêm chi tiết đơn hàng
                $stmt_details->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
                // Cập nhật số lượng tồn kho
                $stmt_update_stock->execute([$item['quantity'], $item['id']]);
            }

            // Nếu mọi thứ thành công, commit transaction
            $this->pdo->commit();

            return $orderId;

        } catch (Exception $e) {
            // Nếu có lỗi, rollback transaction
            $this->pdo->rollBack();
            // Ghi lại lỗi (tùy chọn) error_log($e->getMessage());
            return false;
        }
    }
    /**
     * Cập nhật trạng thái của một đơn hàng.
     * @param int $orderId ID của đơn hàng cần cập nhật.
     * @param string $newStatus Trạng thái mới (ví dụ: 'processing', 'shipped', 'delivered').
     */
    public function updateOrderStatus($orderId, $newStatus) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$newStatus, $orderId]);
    }

    /**
     * Tính tổng doanh thu từ các đơn hàng đã giao thành công.
     * @return float Tổng doanh thu.
     */
    public function getTotalRevenue() {
        // Chỉ tính tổng tiền của các đơn hàng có trạng thái 'delivered'
        $sql = "SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Đếm số lượng đơn hàng mới (đang chờ xử lý).
     * @return int Số lượng đơn hàng mới.
     */
    public function countNewOrders() {
        $sql = "SELECT COUNT(id) as count FROM orders WHERE status = 'pending'";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['count'] ?? 0;
    }

    /**
     * Lấy dữ liệu doanh thu theo tháng cho biểu đồ (12 tháng gần nhất).
     * @return array Mảng chứa các tháng và doanh thu tương ứng.
     * @param int|null $year Năm cần lọc. Nếu null, lấy 12 tháng gần nhất.
     */
    public function getMonthlyRevenue($year = null) {
        $sql = "SELECT 
                    DATE_FORMAT(order_date, '%c') as month, 
                    SUM(total_amount) as revenue 
                FROM orders 
                WHERE status = 'delivered'";
        if ($year) {
            $sql .= " AND YEAR(order_date) = " . (int)$year;
        } else {
            $sql .= " AND order_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)";
        }
        $sql .= " GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                ORDER BY month ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>