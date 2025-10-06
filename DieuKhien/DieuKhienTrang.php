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
        // Sửa lỗi: Hiển thị view dành cho khách hàng
        include __DIR__ . '/../GiaoDien/trang/danh_sach_san_pham.php';
    }
}
?>