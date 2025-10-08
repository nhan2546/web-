<?php
// Tệp: MoHinh/DanhMuc.php

class danhmuc {
    // Thuộc tính để lưu kết nối CSDL
    private $pdo;

    /**
     * Hàm khởi tạo, nhận kết nối $pdo từ Controller
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả danh mục từ bảng `categories`
     */
    public function getDS_Danhmuc() {
        $sql = "SELECT * FROM `categories` ORDER BY name ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm một danh mục mới
     * @param string $name Tên của danh mục mới
     */
    public function themDM($name) {
        $sql = "INSERT INTO `categories` (`name`) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name]);
    }

    /**
     * Xóa một danh mục theo ID
     * @param int $id ID của danh mục cần xóa
     */
    public function xoaDM($id) {
        $sql = "DELETE FROM `categories` WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Lấy thông tin một danh mục theo ID
     * @param int $id ID của danh mục
     */
    public function getDanhMucById($id) {
        $sql = "SELECT * FROM `categories` WHERE id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Bạn có thể thêm các hàm khác như cập nhật danh mục ở đây...
}
?>