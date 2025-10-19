<?php
// File: MoHinh/BaoHanh.php

class BaoHanh {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy danh sách tất cả các yêu cầu bảo hành.
     * @return array Danh sách yêu cầu bảo hành.
     */
    public function getAllClaims() {
        $sql = "SELECT 
                    w.*, 
                    p.name as product_name, 
                    u.fullname as customer_name
                FROM warranties w
                JOIN products p ON w.product_id = p.id
                JOIN users u ON w.customer_id = u.id
                ORDER BY w.claim_date DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>