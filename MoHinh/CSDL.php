<?php
// Tệp: CSDL.php - Phiên bản Hardcode (Điền trực tiếp) để Test Kết Nối

class CSDL {
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $pass;
    public $conn;

    public function __construct() {
        // --- TEST KẾT NỐI TRỰC TIẾP (HARDCODE) ---
        // Bỏ qua getenv, dùng trực tiếp thông tin bạn cung cấp
        
        $this->host   = 'nozomi.proxy.rlw.net';
        $this->port   = '15793';
        $this->dbname = 'railway';
        $this->user   = 'root';
        $this->pass   = 'kbHkzejsqMfjxVRxWmEkUTbVjPjafvas';

        // Debug: In ra màn hình để chắc chắn đang dùng đúng thông tin
        // (Xóa dòng này khi đã chạy ổn)
        // echo "Đang kết nối tới: {$this->host}:{$this->port} ... <br>";

        try {
            // Chuỗi kết nối DSN
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 10, // Tăng timeout lên 10s cho chắc
            ];

            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            
            // Nếu code chạy đến đây nghĩa là thành công!
            // echo "Kết nối thành công!"; 
            
        } catch (PDOException $e) {
            // Nếu vẫn lỗi thì in ra chi tiết
            die("Lỗi kết nối (Hardcode): " . $e->getMessage());
        }
    }
    
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
