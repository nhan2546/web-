<?php
// File: MoHinh/ThongKe.php

class ThongKe {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy một giá trị thống kê từ CSDL.
     * @param string $key Khóa của thống kê (ví dụ: 'total_visits').
     * @return int Giá trị của thống kê.
     */
    public function getStat($key) {
        $stmt = $this->pdo->prepare("SELECT stat_value FROM site_stats WHERE stat_key = ?");
        $stmt->execute([$key]);
        return (int)$stmt->fetchColumn();
    }
}
?>