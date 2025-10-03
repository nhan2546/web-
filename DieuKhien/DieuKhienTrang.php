<?php
// No need to include CSDL.php here, it's handled by the main index.php
include_once __DIR__ . '/../MoHinh/SanPham.php';

class controller {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // ---- TRANG CHỦ ----
    public function trangchu() {
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/dau_trang.php';
        include __DIR__ . '/../GiaoDien/trang/trang_chu.php';
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/chan_trang.php';
    }

    // ---- SẢN PHẨM ----
    public function hienthi_sp() {
        $sp_model = new sanpham($this->db);
        $danhsach = $sp_model->getallsanpham();
        
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/dau_trang.php';
        // Nạp view và truyền dữ liệu qua
        include __DIR__ . '/../GiaoDien/trang/danh_sach_san_pham.php';
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/chan_trang.php';
    }

    public function xl_themsp() {
        // This logic should ideally be in an admin controller, but we'll leave it for now.
        $id_danhmuc = $_POST['id_danhmuc'] ?? 1;
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $mount = $_POST['mount'] ?? 0;
        $sale = $_POST['sale'] ?? 0;
        $decribe = $_POST['decribe'] ?? '';

        $image_name = "default.jpg";
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = __DIR__ . "/../TaiLen/san_pham/";
            $image_name = time() . "_" . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_name = "default.jpg";
            }
        }

        $sp_model = new sanpham($this->db);
        $sp_model->themsp($id_danhmuc, $name, $price, $mount, $image_name, $sale, $decribe);

        header('Location: index.php?act=hienthi_sp');
    }

    public function xoa_sp() {
        // This logic should also be in an admin controller.
        $id = $_GET['idsp_del'] ?? 0;
        $sp_model = new sanpham($this->db);
        $sp_model->deletesp($id);
        header('Location: index.php?act=hienthi_sp');
    }
}
?>