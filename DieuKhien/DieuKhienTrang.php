<?php
// Tệp: DieuKhien/DieuKhienTrang.php

require_once __DIR__ . '/../MoHinh/SanPham.php';
require_once __DIR__ . '/../MoHinh/DanhMuc.php';

class controller {
    private $pdo; // Thuộc tính để lưu trữ kết nối CSDL

    // Hàm khởi tạo, nhận kết nối CSDL từ index.php
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Hàm xử lý cho trang chủ
    public function trangchu() {
        // Tạo model sản phẩm, truyền kết nối CSDL vào
        $sanpham_model = new sanpham($this->pdo); 
        $danh_sach_san_pham = $sanpham_model->getallsanpham();
        // Hiển thị trang chủ với danh sách sản phẩm
        include __DIR__ . '/../GiaoDien/trang/trang_chu.php';
    }

    // Các hàm khác cho sản phẩm, danh mục...
    public function hienthi_sp() {
        // Tương tự, tạo model và gọi hàm
        $sanpham_model = new sanpham($this->pdo);
        $danh_sach_san_pham = $sanpham_model->getallsanpham();
        // ... có thể cần lấy cả danh mục ở đây
        include __DIR__ . '/../GiaoDien/QuanTri/san_pham/danh_sach.php'; 
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/dau_trang.php';
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/chan_trang.php';
    }
    
    public function xl_themsp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Lấy dữ liệu từ form
            $name = $_POST['name'] ?? '';                     
            // 2. Xử lý upload hình ảnh 
            $image_url = 'default.jpg'; 
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../TaiLen/san_pham/";
                $image_url = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image_url;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                header('Location: index.php?act=hienthi_sp');
                exit;
            }

            // 3. Tạo đối tượng model và gọi hàm thêm
            $sanpham_model = new sanpham($this->pdo);
            $sp = new sanpham($this->pdo); // Tạo một đối tượng sanpham để chứa dữ liệu
            $sp->setName($name);
            $sp->setImage($image_url);
            // ... dùng các hàm set...() khác
            
            $sanpham_model->themsp($sp);

            // 4. Chuyển hướng về trang danh sách sản phẩm
            header('Location: index.php?act=hienthi_sp');
            exit;
        }
    }
    public function xoa_sp() {
        // Lấy id từ URL
        $id = $_GET['idsp_del'] ?? 0;

        if ($id > 0) {
            $sanpham_model = new sanpham($this->pdo);
            $sanpham_model->xoasp($id);
        }
        // Chuyển hướng về trang danh sách sản phẩm
        header('Location: index.php?act=hienthi_sp');
        exit;
    }
}
?>