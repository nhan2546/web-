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
}