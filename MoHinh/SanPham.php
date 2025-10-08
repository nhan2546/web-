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
    public function themsp(...$args){
        if (count($args) === 1 && $args[0] instanceof sanpham) {
            $sp = $args[0];
        } else {
            $id_danhmuc = $args[0] ?? 0;
            $name = $args[1] ?? '';
            $price = $args[2] ?? 0;
            $mount = $args[3] ?? 0;
            $image_url = $args[4] ?? '';
            $sale = $args[5] ?? 0;
            $decribe = $args[6] ?? '';

            // Pass the connection when creating a new instance
            $sp = new sanpham($this->db);
            $sp->setId_danhmuc($id_danhmuc);
            $sp->setName($name);
            $sp->setPrice($price);
            $sp->setMount($mount);
            $sp->setImage($image_url);
            $sp->setSale($sale);
            $sp->setDecribe($decribe);
        }

        $sql = "INSERT INTO `products` (`name`, `description`, `price`, `image_url`, `stock_quantity`, `category_id`) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $name = $sp->getName();
        $description = $sp->getDecribe();
        $price = $sp->getPrice();
        $image_url = $sp->getImage();
        $stock_quantity = $sp->getMount();
        $category_id = $sp->getId_danhmuc();
        
        $stmt->execute([$name, $description, $price, $image_url, $stock_quantity, $category_id]);
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

    /*Lấy sản phẩm theo id*/
    public function getone_sanoham($id){
        $sql = "SELECT * FROM products WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*cap nhat san pham*/
    public function capnhatsp(sanpham $sp){
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, stock_quantity = ?, category_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $name = $sp->getName();
        $description = $sp->getDecribe();
        $price = $sp->getPrice();
        $image_url = $sp->getImage();
        $stock_quantity = $sp->getMount();
        $category_id = $sp->getId_danhmuc();
        $id = $sp->getId();
        $stmt->execute([$name, $description, $price, $image_url, $stock_quantity, $category_id, $id]);
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