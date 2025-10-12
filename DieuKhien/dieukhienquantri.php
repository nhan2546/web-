<?php
// Tệp: DieuKhien/DieuKhienQuanTri.php

// 1. INCLUDE CÁC TỆP MODEL CẦN THIẾT
require_once __DIR__ . '/../MoHinh/CSDL.php';
require_once __DIR__ . '/../MoHinh/SanPham.php';
require_once __DIR__ . '/../MoHinh/DanhMuc.php';
require_once __DIR__ . '/../MoHinh/DonHang.php';

class DieuKhienQuanTri {
    private $pdo; // Thuộc tính để lưu trữ kết nối CSDL

    // Hàm khởi tạo, nhận kết nối CSDL từ tệp router chính
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- CÁC HÀM XỬ LÝ CHO TỪNG CHỨC NĂNG ---

    // Chức năng: Hiển thị danh sách sản phẩm
    public function ds_sanpham() {
        // Khởi tạo model và truyền kết nối CSDL vào -> ĐÂY LÀ CÁCH LÀM ĐÚNG
        $sp_model = new sanpham($this->pdo);
        $danh_sach_san_pham = $sp_model->getallsanpham();
        include __DIR__ . '/../GiaoDien/QuanTri/san_pham/danh_sach.php';
    }

    // Chức năng: Hiển thị form thêm sản phẩm
    public function hienthi_themsp() {
        include __DIR__ . '/../GiaoDien/QuanTri/san_pham/them.php';
    }

    // Chức năng: Hiển thị danh sách đơn hàng
    public function ds_donhang() {
        $dh_model = new donhang($this->pdo);
        $danh_sach_don_hang = $dh_model->getAllOrders();
        include __DIR__ . '/../GiaoDien/QuanTri/don_hang/danh_sach.php';
    }

    // Chức năng: Hiển thị chi tiết đơn hàng
    public function ct_donhang() {
        $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($order_id > 0) {
            $dh_model = new donhang($this->pdo);
            $chi_tiet_don_hang = $dh_model->getOrderDetail($order_id);
            include __DIR__ . '/../GiaoDien/QuanTri/don_hang/chi_tiet.php';
        } else {
            header('Location: admin.php?act=ds_donhang'); // Giả sử tệp admin là admin.php
        }
    }

    // Chức năng: Cập nhật trạng thái đơn hàng
    public function capnhat_trangthai_donhang() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
            $new_status = isset($_POST['status']) ? $_POST['status'] : '';

            if ($order_id > 0 && !empty($new_status)) {
                $dh_model = new donhang($this->pdo);
                $dh_model->updateOrderStatus($order_id, $new_status);
            }
            header('Location: admin.php?act=ct_donhang&id=' . $order_id);
        }
    }
    
    // Chức năng: Hiển thị trang dashboard mặc định
    public function dashboard() {
        include __DIR__ . '/../GiaoDien/QuanTri/bang_dieu_khien.php';
    }

    // Chức năng: Hiển thị form thêm sản phẩm
    public function them_sp() {
        // Lấy danh sách danh mục để hiển thị trong form
        $dm_model = new danhmuc($this->pdo);
        $danh_sach_danh_muc = $dm_model->getAllCategories();
        include __DIR__ . '/../GiaoDien/QuanTri/san_pham/them.php';
    }

    // Chức năng: Xử lý thêm sản phẩm mới
    public function xl_themsp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Lấy dữ liệu từ form
            $name = $_POST['name'] ?? '';
            // Thêm các trường khác như price, description...
            // $price = $_POST['price'] ?? 0;
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';
            $stock_quantity = $_POST['stock_quantity'] ?? 0;
            $category_id = $_POST['category_id'] ?? 0;

            // 2. Xử lý upload hình ảnh
            $image_url = ''; // Mặc định không có ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../TaiLen/san_pham/";
                // Đảm bảo thư mục tồn tại
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                // Tạo tên file duy nhất để tránh ghi đè
                $image_url = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image_url;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }

            // 3. Tạo đối tượng model và gọi hàm thêm
            $sanpham_model = new sanpham($this->pdo);
            // Sử dụng các setter để thiết lập giá trị
            $sanpham_model->themsp($category_id, $name, $price, $stock_quantity, $image_url, 0, $description);

            // 4. Chuyển hướng về trang danh sách sản phẩm của admin
            header('Location: admin.php?act=ds_sanpham&success=added');
            exit;
        }
    }

    // Chức năng: Hiển thị form sửa sản phẩm
    public function sua_sp() {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $sp_model = new sanpham($this->pdo);
            $san_pham = $sp_model->getone_sanoham($id); // Lấy thông tin sản phẩm cần sửa

            $dm_model = new danhmuc($this->pdo);
            $danh_sach_danh_muc = $dm_model->getAllCategories(); // Lấy danh sách danh mục

            if ($san_pham) {
                include __DIR__ . '/../GiaoDien/QuanTri/san_pham/sua.php';
            } else {
                header('Location: admin.php?act=ds_sanpham&error=notfound');
            }
        } else {
            header('Location: admin.php?act=ds_sanpham');
        }
    }

    // Chức năng: Xử lý cập nhật sản phẩm
    public function xl_suasp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';
            $stock_quantity = $_POST['stock_quantity'] ?? 0;
            $category_id = $_POST['category_id'] ?? 0;
            $existing_image = $_POST['existing_image'] ?? '';

            $image_url = $existing_image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../TaiLen/san_pham/";
                $image_url = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image_url;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }

            $sp_model = new sanpham($this->pdo);
            $sp_model->capnhatsp($id, $name, $description, $price, $image_url, $stock_quantity, $category_id);
            header('Location: admin.php?act=ds_sanpham&success=updated');
            exit;
        }
    }

    // Chức năng: Xử lý xóa sản phẩm
    public function xoa_sp() {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $sanpham_model = new sanpham($this->pdo);
            $sanpham_model->xoasp($id);
        }
        header('Location: admin.php?act=ds_sanpham&success=deleted');
        exit;
    }
}
?>