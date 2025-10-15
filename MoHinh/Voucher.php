<?php

class Voucher {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Tìm voucher bằng mã và kiểm tra tính hợp lệ.
     * @param string $code Mã voucher.
     * @return mixed Mảng thông tin voucher nếu hợp lệ, ngược lại trả về false.
     */
    public function findVoucherByCode($code) {
        // Câu truy vấn kiểm tra mã, trạng thái active và ngày hết hạn
        // Giả sử bảng của bạn có các cột: code, is_active, expires_at
        $stmt = $this->pdo->prepare(
            "SELECT * FROM vouchers 
             WHERE code = ? 
             AND is_active = 1 
             AND (expires_at IS NULL OR expires_at > NOW())"
        );
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}