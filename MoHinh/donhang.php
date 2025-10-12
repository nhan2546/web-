<?php
// Tệp: STORE/MoHinh/DonHang.php

require_once 'CSDL.php';

class donhang {

    /**
     * Lấy danh sách TẤT CẢ đơn hàng để hiển thị cho admin.
     * Hàm này sẽ nối (JOIN) với bảng `users` để lấy tên khách hàng.
     * @return array Mảng chứa tất cả các đơn hàng.
     */
    public function getAllOrders() {
        $db = new CSDL();
        // Sắp xếp theo ngày đặt hàng mới nhất lên đầu (DESC)
        $sql = "SELECT o.*, u.fullname as customer_name 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.order_date DESC";
        return $db->read($sql);
    }

    /**
     * Lấy danh sách đơn hàng của một người dùng cụ thể.
     * @param int $userId ID của người dùng.
     * @return array Mảng chứa các đơn hàng của người dùng đó.
     */
    public function getOrdersByUserId($userId) {
        $db = new CSDL();
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $db->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy toàn bộ thông tin chi tiết của MỘT đơn hàng.
     * Bao gồm thông tin đơn hàng, thông tin khách hàng và danh sách sản phẩm trong đơn.
     * @param int $orderId ID của đơn hàng cần xem.
     * @return array Mảng chứa 'order_info' và 'order_items'.
     */
    public function getOrderDetail($orderId) {
        $db = new CSDL();
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
        $stmt_info = $db->conn->prepare($sql_info);
        $stmt_info->bind_param("i", $orderId);
        $stmt_info->execute();
        $result_info = $stmt_info->get_result();
        $details['order_info'] = $result_info->fetch_assoc();
        $stmt_info->close();

        // 2. Lấy danh sách các sản phẩm trong đơn hàng
        $sql_items = "SELECT od.quantity, od.price, p.name as product_name, p.image_url 
                      FROM order_details od 
                      JOIN products p ON od.product_id = p.id 
                      WHERE od.order_id = ?";
        $stmt_items = $db->conn->prepare($sql_items);
        $stmt_items->bind_param("i", $orderId);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();
        while ($row = $result_items->fetch_assoc()) {
            $details['order_items'][] = $row;
        }
        $stmt_items->close();

        return $details;
    }

    /**
     * Cập nhật trạng thái của một đơn hàng.
     * @param int $orderId ID của đơn hàng cần cập nhật.
     * @param string $newStatus Trạng thái mới (ví dụ: 'processing', 'shipped', 'delivered').
     */
    public function updateOrderStatus($orderId, $newStatus) {
        $db = new CSDL();
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $db->conn->prepare($sql);
        // "s" là string (cho status), "i" là integer (cho id)
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
    }
}
?>