<?php
class sanpham {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getallsanpham($limit = 8) {
        $sql = "SELECT id, name, price, image FROM products ORDER BY id DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function themsp($id_danhmuc, $name, $price, $mount, $image_name, $sale, $decribe) {
        // Lưu ý: Cột 'sale' không được sử dụng trong câu lệnh SQL này.
        $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, image) VALUES (:name, :description, :price, :stock_quantity, :category_id, :image)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $decribe);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $mount);
        $stmt->bindParam(':category_id', $id_danhmuc);
        $stmt->bindParam(':image', $image_name);
        
        return $stmt->execute();
    }

    public function deletesp($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>