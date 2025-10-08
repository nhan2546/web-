<?php
// Tệp: DieuKhien/DieuKhienTrang.php

require_once __DIR__ . '/../MoHinh/SanPham.php';

class controller {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function trangchu() {
        // Lấy một vài sản phẩm để hiển thị trên trang chủ (ví dụ)
        $sp_model = new sanpham($this->pdo);
        $danh_sach_san_pham = $sp_model->getallsanpham(8); // Lấy 8 sản phẩm
        include __DIR__.'/../GiaoDien/trang/trang_chu.php';
    }

    public function hienthi_sp() {
        $sp_model = new sanpham($this->pdo);

        // Logic phân trang
        $products_per_page = 8; // Số sản phẩm trên mỗi trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $products_per_page;

        $total_products = $sp_model->countAllSanPham();
        $total_pages = ceil($total_products / $products_per_page);

        $danh_sach_san_pham = $sp_model->getallsanpham($products_per_page, $offset);

        include __DIR__.'/../GiaoDien/trang/danh_sach_san_pham.php';
    }

    public function chi_tiet_san_pham() {
        $id = $_GET['id'] ?? 0;
        $sp_model = new sanpham($this->pdo);
        $san_pham = $sp_model->getsanphambyid($id);
        include __DIR__.'/../GiaoDien/trang/chi_tiet_san_pham.php';
    }

    public function tim_kiem_san_pham() {
        $keyword = $_GET['keyword'] ?? '';
        $sp_model = new sanpham($this->pdo);
        $danh_sach_san_pham = $sp_model->timKiemSanPham($keyword);
        // Sử dụng một view riêng để hiển thị kết quả
        include __DIR__.'/../GiaoDien/trang/ket_qua_tim_kiem.php';
    }

    public function ajax_tim_kiem() {
        header('Content-Type: application/json'); // Báo cho trình duyệt biết đây là dữ liệu JSON
        $keyword = $_GET['keyword'] ?? '';
        $sp_model = new sanpham($this->pdo);

        // Giới hạn số lượng kết quả trả về để không làm chậm trang
        $danh_sach_san_pham = $sp_model->timKiemSanPham($keyword, 5); 

        echo json_encode($danh_sach_san_pham);
        exit(); // Dừng thực thi sau khi trả về JSON
    }

    // --- CÁC HÀM XỬ LÝ GIỎ HÀNG ---

    public function them_vao_gio() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $image_url = $_POST['image_url'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'] ?? 1;

            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            if (isset($_SESSION['cart'][$id])) {
                // Nếu có, tăng số lượng
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                // Nếu chưa, thêm mới
                $_SESSION['cart'][$id] = [
                    'id' => $id,
                    'name' => $name,
                    'image_url' => $image_url,
                    'price' => $price,
                    'quantity' => $quantity
                ];
            }
        }
        // Chuyển hướng người dùng về trang giỏ hàng
        header('Location: index.php?act=gio_hang');
        exit();
    }

    public function hien_thi_gio_hang() {
        $cart = $_SESSION['cart'] ?? [];
        include __DIR__.'/../GiaoDien/trang/gio_hang.php';
    }

    public function xoa_san_pham_gio_hang() {
        $id = $_GET['id'] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: index.php?act=gio_hang');
        exit();
    }

    public function cap_nhat_gio_hang() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['quantities'])) {
                foreach ($_POST['quantities'] as $id => $quantity) {
                    $quantity = (int)$quantity;
                    if ($quantity > 0 && isset($_SESSION['cart'][$id])) {
                        $_SESSION['cart'][$id]['quantity'] = $quantity;
                    } else if ($quantity <= 0 && isset($_SESSION['cart'][$id])) {
                        // Nếu số lượng <= 0 thì xóa sản phẩm
                        unset($_SESSION['cart'][$id]);
                    }
                }
            }
        }
        header('Location: index.php?act=gio_hang');
        exit();
    }
}
?>