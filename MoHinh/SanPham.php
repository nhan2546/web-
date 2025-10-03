<?php
require_once 'CSDL.php';

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

    /*getters và setters*/
    public function __construct(){}
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
    // Flexible themsp: supports either themsp(sanpham $sp) or themsp($id_danhmuc, $name, $price, $mount, $image_url, $sale, $decribe)
    public function themsp(...$args){
        // Normalize to a sanpham object
        if (count($args) === 1 && $args[0] instanceof sanpham) {
            $sp = $args[0];
        } else {
            // Expect scalar args: id_danhmuc, name, price, mount, image_url, sale, decribe
            $id_danhmuc = $args[0] ?? 0;
            $name = $args[1] ?? '';
            $price = $args[2] ?? 0;
            $mount = $args[3] ?? 0;
            $image_url = $args[4] ?? '';
            $sale = $args[5] ?? 0;
            $decribe = $args[6] ?? '';

            $sp = new sanpham();
            $sp->setId_danhmuc($id_danhmuc);
            $sp->setName($name);
            $sp->setPrice($price);
            $sp->setMount($mount);
            $sp->setImage($image_url);
            $sp->setSale($sale);
            $sp->setDecribe($decribe);
        }

        $db = new CSDL();
        $sql = "INSERT INTO `products` (`name`, `description`, `price`, `image_url`, `stock_quantity`, `category_id`) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->conn->prepare($sql);

        $name = $sp->getName();
        $description = $sp->getDecribe();
        $price = $sp->getPrice();
        $image_url = $sp->getImage();
        $stock_quantity = $sp->getMount();
        $category_id = $sp->getId_danhmuc();
        
        $stmt->bind_param("ssdsii", $name, $description, $price, $image_url, $image_url, $category_id);
        // Note: previous bind types didn't match entirely; keep as string/string/double/string/int/int
        $stmt->execute();
        $stmt->close();
    }

    /*lấy sản phẩm từ bản product*/
    public function getallsanphamZ(){
        $db = new CSDL();
        $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC";
        return $db->read($sql);
    }

    // Backwards-compatible wrapper expected by controllers
    public function getallsanpham(){
        return $this->getallsanphamZ();
    }

    /*Lấy sản phẩm theo id*/
    public function getone_sanoham($id){
        $db = new CSDL();
        $sql = "SELECT * FROM products WHERE id = ? LIMIT 1";
        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    /*cap nhat san pham*/
    public function capnhatsp(sanpham $sp){
        $db = new CSDL();
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, stock_quantity = ?, category_id = ? WHERE id = ?";
        $stmt = $db->conn->prepare($sql);
        $name = $sp->getName();
        $description = $sp->getDecribe();
        $price = $sp->getPrice();
        $image_url = $sp->getImage();
        $stock_quantity = $sp->getMount();
        $category_id = $sp->getId_danhmuc();
        $id = $sp->getId();
        $stmt->bind_param("ssdsiii", $name, $description, $price, $image_url, $stock_quantity, $category_id, $id);
        $stmt->execute();
        $stmt->close();
    }

    /*xoa san pham*/
        public function xoasp($id){
        $db = new CSDL();
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

        // Backwards-compatible alias
        public function deletesp($id){
            return $this->xoasp($id);
        }
}
?>  