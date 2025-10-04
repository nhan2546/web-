<?php
// Tệp: DieuKhien/DieuKhienQuanTri.php

// 1. INCLUDE CÁC TỆP MODEL CẦN THIẾT
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
}
?>