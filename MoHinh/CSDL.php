<?php
// Tệp: CSDL.php - Đã tối ưu hóa để fix lỗi khoảng trắng (trim)

class CSDL {
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $pass;
    public $conn;

    public function __construct() {
        // --- QUAN TRỌNG: Dùng trim() để xóa khoảng trắng thừa do copy/paste ---
        // Nhiều khi copy từ Railway sang Render sẽ bị dính dấu cách ở cuối
        $this->host   = trim(getenv('DB_HOST'));
        $this->port   = trim(getenv('DB_PORT')) ?: '3306';
        $this->dbname = trim(getenv('DB_NAME'));
        $this->user   = trim(getenv('DB_USER'));
        $this->pass   = trim(getenv('DB_PASSWORD'));
        
        // Kiểm tra kỹ xem biến có rỗng không sau khi trim
        if (empty($this->host) || empty($this->user)) {
            // In ra màn hình để debug nếu thiếu biến
            die("Lỗi Cấu hình: Biến môi trường DB_HOST hoặc DB_USER đang trống trên Render.");
        }
        
        try {
            // Tạo chuỗi DSN (Data Source Name)
            // Lưu ý: charset=utf8mb4 quan trọng để hiển thị tiếng Việt không bị lỗi font
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 5, // Timeout sau 5s nếu mạng lag (đặc biệt khi kết nối cross-cloud)
            ];

            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            
        } catch (PDOException $e) {
            // Hiển thị lỗi chi tiết để debug
            // In giá trị host trong dấu ngoặc [] để bạn nhìn thấy nếu có khoảng trắng thừa
            $errorMsg = "Lỗi kết nối Database Railway: " . $e->getMessage();
            $errorMsg .= " | Host đang nhận: [" . $this->host . "]"; 
            die($errorMsg);
        }
    }
    
    /**
     * Executes a SELECT query and returns all results.
     */
    public function read($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Lỗi SQL (Read): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Executes an INSERT, UPDATE, or DELETE query.
     */
    public function write($sql, $params = []) {
         try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
         } catch (PDOException $e) {
             error_log("Lỗi SQL (Write): " . $e->getMessage());
             return false;
         }
    }

    public function __destruct() {
        $this->conn = null;
    }
}
?>
