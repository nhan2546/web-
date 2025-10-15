<?php
class sanpham { 
    private $id_sp = 0;
    private $name = "";
    private $price = 0;
    private $date_import = "";
    private $viewsp = 0;
    private $decribe = "";
    private $mount = 0;
    private $sale = 0;
    private $image = "";
    private $id_danhmuc = 0;
    private $db; // Property to hold the database connection

    // Constructor now accepts a PDO database connection
    public function __construct($pdo){
        $this->db = $pdo;
    }

    /*getters và setters*/
    public function setId_danhmuc($id_danhmuc){
        $this->id_danhmuc = $id_danhmuc;
    }
    public function getId_danhmuc(){
        return $this->id_danhmuc;
    }
    public function setPrice($price){
        $this->price = $price;
    }
    public function getPrice(){
        return $this->price;
    }
    public function setImage($image){
        $this->image = $image;
    }
    public function getImage(){
        return $this->image;
    }
    public function setSale($sale){
        $this->sale = $sale;
    }
    public function getSale(){
        return $this->sale;
    }
    public function setMount($mount){
        $this->mount = $mount;
    }
    public function getMount(){
        return $this->mount;
    } 
    public function setDecribe($decribe){
        $this->decribe = $decribe;
    }
    public function getDecribe(){
        return $this->decribe;
    }
    public function setViewsp($viewsp){
        $this->viewsp = $viewsp;
    }
    public function getViewsp(){
        return $this->viewsp;
    }
    public function setDate_import($date_import){
        $this->date_import = $date_import;
    }
    public function getDate_import(){
        return $this->date_import;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getName(){
        return $this->name;
    }
    public function setId ($id){
        $this->id_sp = $id;
    }
    public function getId(){
        return $this->id_sp;
    }

    /*xử lý dữ liệu */
    public function themsp($name, $description, $price, $image_url, $stock_quantity, $category_id, $sale_price){
        $sql = "INSERT INTO `products` (`name`, `description`, `price`, `image_url`, `stock_quantity`, `category_id`, `sale_price`) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $description, $price, $image_url, $stock_quantity, $category_id, $sale_price]);
    }

    /*lấy sản phẩm từ bản product*/
    public function getallsanpham($limit = null, $offset = 0){
        $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC";
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $this->db->prepare($sql);
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Đếm tổng số sản phẩm */
    public function countAllSanPham() {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /* Lấy sản phẩm theo ID danh mục với phân trang */
    public function getSanPhamByDanhMuc($category_id, $limit = null, $offset = 0) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id 
                ORDER BY p.id DESC";
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Đếm tổng số sản phẩm trong một danh mục */
    public function countSanPhamByDanhMuc($category_id) {
        $sql = "SELECT COUNT(*) FROM products WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetchColumn();
    }

    /*Tìm kiếm sản phẩm theo từ khóa*/
    public function timKiemSanPham($keyword, $limit = null) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.name LIKE ? ORDER BY p.id DESC";
        if ($limit) $sql .= " LIMIT " . (int)$limit;
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy các sản phẩm đang giảm giá (Hot Sale).
     * @param int $limit Giới hạn số lượng sản phẩm.
     * @return array Mảng các sản phẩm giảm giá.
     */
    public function getHotSaleProducts($limit = 5) {
        // Lấy các sản phẩm có giá khuyến mãi (sale_price) và đang còn hàng
        // Sắp xếp theo % giảm giá cao nhất
        $sql = "SELECT *, ((price - sale_price) / price) * 100 AS discount_percentage 
                FROM products 
                WHERE sale_price IS NOT NULL AND sale_price > 0 AND sale_price < price AND stock_quantity > 0
                ORDER BY discount_percentage DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*Lấy sản phẩm theo id*/
    public function getone_sanoham($id){
        $sql = "SELECT * FROM products WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*cap nhat san pham*/
    public function capnhatsp($id, $name, $description, $price, $image_url, $stock_quantity, $category_id, $sale_price){
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, stock_quantity = ?, category_id = ?, sale_price = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $description, $price, $image_url, $stock_quantity, $category_id, $sale_price, $id]);
    }

    /*xoa san pham*/
    public function xoasp($id){
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
    }

    // Backwards-compatible alias
    public function deletesp($id){
        return $this->xoasp($id);
    }
}
?>