<?php
// Tệp: CSDL.php - Dùng cho Railway (Cloud MySQL)
// PHIÊN BẢN ĐÃ SỬA LỖI: "public_function" (Lỗi của tôi)

class CSDL {
    // --- 1. Khai báo thuộc tính (KHÔNG gán giá trị) ---
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $pass;
    public $conn;

    public function __construct() {
        // --- 2. Gán giá trị (từ Biến Môi trường) BÊN TRONG constructor ---
        $this->host = getenv('DB_HOST');
        $this->port = getenv('DB_PORT'); // Mặc định cổng 3306 nếu không có
        $this->dbname = getenv('DB_NAME');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASSWORD');
        
        // Kiểm tra xem biến đã được thiết lập chưa
        if (!$this->host) {
            die("Lỗi nghiêm trọng: Biến môi trường DB_HOST chưa được cài đặt trên Render.");
        }
        if (!$this->user) {
            die("Lỗi nghiêm trọng: Biến môi trường DB_USER chưa được cài đặt trên Render.");
        }
        
        try {
            // --- 3. Kết nối bằng các thuộc tính đã gán ---
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            
            $this->conn = new PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            die("Không thể kết nối đến cơ sở dữ liệu Railway: " . $e->getMessage());
        }
    }
    
    /**
     * Executes a SELECT query and returns all results.
     */
    public function read($sql, $params = []) { // <-- ĐÃ SỬA LỖI TẠI ĐÂY
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Executes an INSERT, UPDATE, or DELETE query.
     */
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
