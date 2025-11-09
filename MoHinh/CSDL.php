<?php
// Tệp: CSDL.php - Dùng cho Railway (Cloud MySQL)

class CSDL {
    // --- Lấy cấu hình từ Biến Môi trường của Render ---
    // (Chúng ta sẽ KHÔNG gõ cứng mật khẩu vào code)
    private $host = getenv('DB_HOST');
    private $port = getenv('DB_PORT') ?: '3306';
    private $dbname = getenv('DB_NAME');
    private $user = getenv('DB_USER');
    private $pass = getenv('DB_PASSWORD');
    public $conn;

    public function __construct() {
        if (!$this->host) {
            die("Lỗi: Biến môi trường DB_HOST chưa được cài đặt.");
        }
        
        try {
            // Chuỗi kết nối MySQL với Cổng (Port)
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            
            $this->conn = new PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            die("Không thể kết nối đến cơ sở dữ liệu Railway: " . $e->getMessage());
        }
    }
    
    // ... (Các hàm read() và write() của bạn giữ nguyên) ...
    
    public function read($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function write($sql, $params = []) {
         try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
         } catch (PDOException $e) {
             error_log("Lỗi CSDL (write): " . $e->getMessage());
             return false;
         }
    }

    public function __destruct() {
        $this->conn = null;
    }
}
?>
