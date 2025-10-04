<?php
class NguoiDung {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function findUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($fullname, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        
        return $stmt->execute();
    }
    /*
    @param string $email;
    @param string $password;
    @return array|false Thông tin người dùng nếu đăng nhập thành công, ngược lại trả về false.
     */
    public function login($email, $password) {
        //tìm người dùng
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = $this->findUserByEmail($email);
        
        // tìm thấy người dùng xác thực mật khẩu 
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
?>