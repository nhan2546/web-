<?php
class donhang {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Lấy tất cả đơn hàng từ CSDL (dùng cho admin)
     */
    public function getAllOrders() {
        $sql = "SELECT * FROM orders ORDER BY order_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả đơn hàng của một người dùng cụ thể
     */
    public function getOrdersByUserId($user_id) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết một đơn hàng
     */
    public function getOrderDetail($order_id) {
        $sql = "SELECT od.*, p.name as product_name, p.image_url 
                FROM order_details od 
                JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($order_id, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $order_id]);
    }
}
?>