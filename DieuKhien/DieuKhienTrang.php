<?php
// Tệp: DieuKhien/DieuKhienTrang.php

require_once __DIR__ . '/../MoHinh/NguoiDung.php';
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

    public function hienthi_sp_theo_danhmuc() {
        $category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($category_id <= 0) {
            header('Location: index.php?act=trangchu');
            exit();
        }

        $sp_model = new sanpham($this->pdo);
        $dm_model = new danhmuc($this->pdo);

        // Lấy thông tin danh mục để hiển thị tiêu đề
        $category_info = $dm_model->getDanhMucById($category_id);

        // Logic phân trang
        $products_per_page = 8;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $products_per_page;

        $total_products = $sp_model->countSanPhamByDanhMuc($category_id);
        $total_pages = ceil($total_products / $products_per_page);

        $danh_sach_san_pham = $sp_model->getSanPhamByDanhMuc($category_id, $products_per_page, $offset);
        include __DIR__.'/../GiaoDien/trang/danh_sach_san_pham.php';
    }

    public function chi_tiet_san_pham() {
        $id = $_GET['id'] ?? 0;
        $sp_model = new sanpham($this->pdo);
        $san_pham = $sp_model->getone_sanpham($id);
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

    public function lich_su_mua_hang() {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?act=dang_nhap');
            exit();
        }

        // 2. Lấy đơn hàng từ CSDL
        require_once __DIR__ . '/../MoHinh/DonHang.php';
        $donHangModel = new donhang($this->pdo);
        $danh_sach_don_hang = $donHangModel->getOrdersByUserId($_SESSION['user_id']);

        // 3. Hiển thị view
        include __DIR__.'/../GiaoDien/trang/lich_su_mua_hang.php';
    }

    public function thong_tin_tai_khoan() {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?act=dang_nhap');
            exit();
        }

        // 2. Lấy thông tin người dùng từ CSDL
        $userModel = new NguoiDung($this->pdo);
        $user_info = $userModel->findUserById($_SESSION['user_id']);

        // 3. Hiển thị view
        include __DIR__.'/../GiaoDien/trang/thong_tin_tai_khoan.php';
    }

    public function cap_nhat_tai_khoan() {
        // 1. Kiểm tra người dùng đã đăng nhập và gửi form bằng POST
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=trangchu');
            exit();
        }

        // 2. Lấy dữ liệu từ form
        $user_id = $_SESSION['user_id'];
        $fullname = $_POST['fullname'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $address = $_POST['address'] ?? '';

        // 3. Gọi model để cập nhật
        $userModel = new NguoiDung($this->pdo);
        $userModel->updateUserInfo($user_id, $fullname, $phone_number, $address);

        // 4. Cập nhật lại session để tên mới hiển thị ngay lập tức
        $_SESSION['user_fullname'] = $fullname;

        // 4. Chuyển hướng lại trang thông tin với thông báo thành công
        header('Location: index.php?act=thong_tin_tai_khoan&success=1');
        exit();
    }

    public function doi_mat_khau() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=trangchu');
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';

        // Kiểm tra mật khẩu mới có khớp không
        if ($new_password !== $confirm_new_password) {
            header('Location: index.php?act=thong_tin_tai_khoan&error=password_mismatch');
            exit();
        }

        $userModel = new NguoiDung($this->pdo);
        $user = $userModel->findUserById($user_id);

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!$user || !password_verify($current_password, $user['password'])) {
            header('Location: index.php?act=thong_tin_tai_khoan&error=wrong_password');
            exit();
        }

        // Cập nhật mật khẩu mới
        $userModel->updatePassword($user_id, $new_password);

        // Chuyển hướng với thông báo thành công
        header('Location: index.php?act=thong_tin_tai_khoan&success=password_changed');
        exit();
    }

    public function cap_nhat_avatar() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=trangchu');
            exit();
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $target_dir = __DIR__ . "/../TaiLen/avatars/";
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
            // Tạo tên tệp duy nhất để tránh ghi đè
            $new_filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Di chuyển tệp đã tải lên
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                // Cập nhật CSDL
                $userModel = new NguoiDung($this->pdo);
                $userModel->updateAvatar($_SESSION['user_id'], $new_filename);
                header('Location: index.php?act=thong_tin_tai_khoan&success=avatar_updated');
                exit();
            }
        }

        // Nếu có lỗi, chuyển hướng lại
        header('Location: index.php?act=thong_tin_tai_khoan&error=avatar_upload_failed');
        exit();
    }
}
?>