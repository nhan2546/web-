<?php
// File: MoHinh/Voucher.php

class Voucher {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả các voucher.
     * @return array
     */
    public function getAllVouchers() {
        $stmt = $this->pdo->query("SELECT * FROM vouchers ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin một voucher bằng ID.
     * @param int $id
     * @return mixed
     */
    public function getVoucherById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM vouchers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm voucher bằng mã code (dùng để kiểm tra trùng lặp).
     * @param string $code
     * @return mixed
     */
    public function findVoucherByCode($code) {
        $stmt = $this->pdo->prepare("SELECT * FROM vouchers WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm một voucher mới.
     * @return bool
     */
    public function addVoucher($code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active) {
        $sql = "INSERT INTO vouchers (code, description, discount_type, discount_value, min_order_amount, usage_limit, expires_at, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active]);
    }

    /**
     * Cập nhật thông tin voucher.
     * @return bool
     */
    public function updateVoucher($id, $code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active) {
        // Xử lý giá trị null cho usage_limit và expires_at
        $usage_limit = empty($usage_limit) ? null : $usage_limit;
        $expires_at = empty($expires_at) ? null : $expires_at;

        $sql = "UPDATE vouchers SET 
                    code = ?, 
                    description = ?, 
                    discount_type = ?, 
                    discount_value = ?, 
                    min_order_amount = ?, 
                    usage_limit = ?, 
                    expires_at = ?, 
                    is_active = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active, $id]);
    }

    /**
     * Xóa một voucher.
     * @param int $id
     * @return bool
     */
    public function deleteVoucher($id) {
        $stmt = $this->pdo->prepare("DELETE FROM vouchers WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Tăng số lượt đã sử dụng của voucher.
     * @param string $code
     */
    public function incrementVoucherUsage($code) {
        $sql = "UPDATE vouchers SET usage_count = usage_count + 1 WHERE code = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$code]);
    }
}
?>