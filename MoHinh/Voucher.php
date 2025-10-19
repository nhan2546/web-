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
             AND (usage_limit IS NULL OR usage_count < usage_limit)
             AND (expiry_date IS NULL OR expiry_date > NOW())
             LIMIT 1"
        );
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm voucher chỉ bằng mã code (dùng cho admin kiểm tra trùng lặp).
     * @param string $code Mã voucher.
     * @return mixed Mảng thông tin voucher nếu tìm thấy, ngược lại trả về false.
     */
    public function findVoucherByCodeForAdmin($code) {
        $stmt = $this->pdo->prepare("SELECT * FROM vouchers WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // --- CÁC HÀM CHO ADMIN ---

    /**
     * Lấy tất cả voucher để hiển thị trong trang admin.
     */
    public function getAllVouchers() {
        $stmt = $this->pdo->query("SELECT * FROM vouchers ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin một voucher bằng ID.
     */
    public function getVoucherById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM vouchers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm một voucher mới.
     */
    public function addVoucher($code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active) {
        $sql = "INSERT INTO vouchers (code, description, discount_type, discount_value, min_order_amount, usage_limit, expiry_date, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        // Nếu usage_limit hoặc expires_at rỗng, chèn NULL vào CSDL
        $expires_at = empty($expires_at) ? null : $expires_at;
        $usage_limit = empty($usage_limit) ? null : (int)$usage_limit;
        return $stmt->execute([$code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active]);
    }

    /**
     * Cập nhật thông tin một voucher.
     */
    public function updateVoucher($id, $code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active) {
        $sql = "UPDATE vouchers SET code = ?, description = ?, discount_type = ?, discount_value = ?, min_order_amount = ?, usage_limit = ?, expiry_date = ?, is_active = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        // Nếu usage_limit hoặc expires_at rỗng, chèn NULL vào CSDL
        $expires_at = empty($expires_at) ? null : $expires_at;
        $usage_limit = empty($usage_limit) ? null : (int)$usage_limit;
        return $stmt->execute([$code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active, $id]);
    }

    /**
     * Xóa một voucher.
     */
    public function deleteVoucher($id) {
        $stmt = $this->pdo->prepare("DELETE FROM vouchers WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Tăng số lần sử dụng của một voucher.
     */
    public function incrementVoucherUsage($code) {
        $stmt = $this->pdo->prepare("UPDATE vouchers SET usage_count = usage_count + 1 WHERE code = ?");
        return $stmt->execute([$code]);
    }
}