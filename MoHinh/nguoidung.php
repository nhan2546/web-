<?php

class NguoiDung {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Tìm người dùng bằng email.
     * @param string $email Email của người dùng.
     * @return mixed Mảng thông tin người dùng nếu tìm thấy, ngược lại trả về false.
     */
    public function findUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Tìm người dùng bằng ID.
     * @param int $id ID của người dùng.
     * @return mixed Mảng thông tin người dùng nếu tìm thấy, ngược lại trả về false.
     */
    public function findUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Đăng ký tài khoản mới.
     * @param string $fullname Họ và tên.
     * @param string $email Email.
     * @param string $password Mật khẩu (chưa mã hóa).
     * @return bool True nếu đăng ký thành công, ngược lại false.
     */
    public function register($fullname, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, 'customer')");
        return $stmt->execute([$fullname, $email, $hashed_password]);
    }

    /**
     * Tạo người dùng mới bởi admin.
     * @param string $fullname Họ và tên.
     * @param string $email Email.
     * @param string $password Mật khẩu (chưa mã hóa).
     * @param string $role Vai trò.
     * @return bool True nếu tạo thành công, ngược lại false.
     */
    public function createUserByAdmin($fullname, $email, $password, $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$fullname, $email, $hashed_password, $role]);
    }

    /**
     * Xử lý đăng nhập.
     * @param string $email Email.
     * @param string $password Mật khẩu.
     * @return mixed Mảng thông tin người dùng nếu đăng nhập thành công, ngược lại false.
     */
    public function login($email, $password) {
        $user = $this->findUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Cập nhật thông tin người dùng.
     * @param int $user_id ID người dùng.
     * @param string $fullname Họ và tên mới.
     * @param string $phone_number Số điện thoại mới.
     * @param string $address Địa chỉ mới.
     * @return bool True nếu cập nhật thành công.
     */
    public function updateUserInfo($user_id, $fullname, $phone_number, $address) {
        $stmt = $this->pdo->prepare("UPDATE users SET fullname = ?, phone_number = ?, address = ? WHERE id = ?");
        return $stmt->execute([$fullname, $phone_number, $address, $user_id]);
    }

    /**
     * Cập nhật URL ảnh đại diện cho người dùng.
     * @param int $user_id ID người dùng.
     * @param string $avatar_url Tên tệp ảnh mới.
     * @return bool True nếu cập nhật thành công.
     */
    public function updateAvatar($user_id, $avatar_url) {
        $stmt = $this->pdo->prepare("UPDATE users SET avatar_url = ? WHERE id = ?");
        return $stmt->execute([$avatar_url, $user_id]);
    }
    // Các hàm cho chức năng quên mật khẩu
    public function savePasswordResetToken($email, $token, $expires) {
        $stmt = $this->pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires_at = ? WHERE email = ?");
        return $stmt->execute([$token, $expires, $email]);
    }

    public function findUserByResetToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expires_at > ?");
        $stmt->execute([$token, date("U")]);
        return $stmt->fetch();
    }

    public function updatePassword($user_id, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Xóa token sau khi đã sử dụng
        $stmt = $this->pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?");
        return $stmt->execute([$hashed_password, $user_id]);
    }

    // Hàm cho đăng nhập Google
    public function registerFromGoogle($fullname, $email) {
        // Tạo một mật khẩu ngẫu nhiên vì trường password không được để trống
        $random_password = bin2hex(random_bytes(16)); 
        $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, email, password, role, is_verified) VALUES (?, ?, ?, 'customer', 1)");
        if ($stmt->execute([$fullname, $email, $hashed_password])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Lấy danh sách tất cả người dùng cho trang admin.
     * @param array $roles Mảng các vai trò cần lọc.
     * @param string $searchTerm Từ khóa tìm kiếm (tên hoặc email).
     * @param int|null $limit Giới hạn số lượng kết quả.
     * @param int|null $offset Vị trí bắt đầu lấy kết quả.
     * @return array Mảng chứa danh sách người dùng.
     */
    public function getDS_NguoiDung($roles = [], $searchTerm = '', $limit = null, $offset = null) {
        // Câu truy vấn SQL cơ bản, đã bao gồm cột 'status'
        $sql = "SELECT id, fullname, email, role, status, phone_number, created_at FROM users";
        $conditions = [];
        $params = [];
        
        // Nếu có truyền vào vai trò, thêm điều kiện WHERE
        if (!empty($roles)) {
            // Tạo chuỗi placeholder (?, ?, ...) cho mệnh đề IN
            $placeholders = implode(',', array_fill(0, count($roles), '?'));
            $conditions[] = "role IN ($placeholders)";
            $params = array_merge($params, $roles);
        }

        // Nếu có từ khóa tìm kiếm, thêm điều kiện
        if (!empty($searchTerm)) {
            $conditions[] = "(fullname LIKE ? OR email LIKE ?)";
            $params[] = "%" . $searchTerm . "%";
            $params[] = "%" . $searchTerm . "%";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY created_at DESC";

        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT ? OFFSET ?";
        }
        
        $stmt = $this->pdo->prepare($sql);

        // Bind các tham số của WHERE
        $paramIndex = 1;
        foreach ($params as $value) {
            $stmt->bindValue($paramIndex++, $value);
        }

        // Bind các tham số của LIMIT/OFFSET một cách tường minh với kiểu INT
        if ($limit !== null && $offset !== null) {
            $stmt->bindValue($paramIndex++, (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue($paramIndex++, (int) $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng số nhân viên/admin dựa trên điều kiện lọc.
     * @param array $roles Mảng các vai trò cần đếm.
     * @param string $searchTerm Từ khóa tìm kiếm.
     * @return int Tổng số người dùng khớp điều kiện.
     */
    public function countNhanVien($roles = [], $searchTerm = '') {
        $sql = "SELECT COUNT(id) FROM users";
        $conditions = [];
        $params = [];

        if (!empty($roles)) {
            $placeholders = implode(',', array_fill(0, count($roles), '?'));
            $conditions[] = "role IN ($placeholders)";
            $params = array_merge($params, $roles);
        }
        if (!empty($searchTerm)) {
            $conditions[] = "(fullname LIKE ? OR email LIKE ?)";
            $params[] = "%" . $searchTerm . "%";
            $params[] = "%" . $searchTerm . "%";
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Cập nhật thông tin và vai trò của người dùng (dành cho admin).
     * @param int $id ID người dùng.
     * @param string $fullname Họ và tên.
     * @param string $email Email.
     * @param string $role Vai trò.
     * @return bool True nếu cập nhật thành công.
     */
    public function updateUserByAdmin($id, $fullname, $email, $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET fullname = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([$fullname, $email, $role, $id]);
    }

    /**
     * Xóa người dùng theo ID.
     * @param int $id ID người dùng.
     * @return bool True nếu xóa thành công.
     */
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Đếm tổng số khách hàng.
     * @return int Số lượng người dùng có vai trò 'customer'.
     */
    public function countCustomers() {
        $stmt = $this->pdo->prepare("SELECT COUNT(id) FROM users WHERE role = 'customer'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Lấy danh sách khách hàng cùng với tổng chi tiêu của họ.
     * @return array Mảng chứa danh sách khách hàng và chi tiêu.
     */
    public function getDS_KhachHang() {
        $sql = "SELECT 
                    u.id, 
                    u.fullname, 
                    u.email,
                    u.status,
                    SUM(CASE WHEN o.status = 'delivered' THEN o.total_amount ELSE 0 END) as total_spending
                FROM 
                    users u
                LEFT JOIN 
                    orders o ON u.id = o.user_id
                WHERE 
                    u.role = 'customer'
                GROUP BY 
                    u.id, u.fullname, u.email, u.status
                ORDER BY 
                    total_spending DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thay đổi trạng thái khóa của người dùng.
     * @param int $id ID người dùng cần cập nhật.
     * @param string $status Trạng thái mới ('active' hoặc 'locked').
     * @return bool True nếu cập nhật thành công.
     */
    public function toggleUserStatus($id, $status) {
        // Đảm bảo trạng thái hợp lệ để tránh lỗi SQL Injection
        if (!in_array($status, ['active', 'locked'])) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Lấy tổng chi tiêu của một người dùng cụ thể.
     * @param int $user_id ID của người dùng.
     * @return float Tổng số tiền đã chi tiêu cho các đơn hàng đã giao.
     */
    public function getTotalSpendingByUserId($user_id) {
        $sql = "SELECT 
                    SUM(CASE WHEN status = 'delivered' THEN total_amount ELSE 0 END) as total_spending
                FROM 
                    orders
                WHERE 
                    user_id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return (float) $stmt->fetchColumn();
    }

    /**
     * Lấy danh sách sản phẩm yêu thích (wishlist) của người dùng.
     * @param int $user_id ID của người dùng.
     * @param int|null $limit Giới hạn số lượng sản phẩm.
     * @return array Mảng các sản phẩm trong wishlist.
     */
    public function getWishlist($user_id, $limit = null) {
        $sql = "SELECT p.* FROM products p
                JOIN wishlist w ON p.id = w.product_id
                WHERE w.user_id = ?";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}