<?php
class CSDL {
    private $host = 'localhost';
    private $dbname = 'store_db';
    private $user = 'root';
    private $pass = '';
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage());
        }
    }

    /**
     * Executes a SELECT query and returns all results.
     * @param string $sql The SQL query to execute.
     * @param array $params An array of parameters to bind to the query.
     * @return array The result set.
     */
    public function read($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function __destruct() {
        $this->conn = null;
    }
}
?>