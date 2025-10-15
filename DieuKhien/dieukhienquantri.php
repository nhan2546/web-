<?php
require_once __DIR__ . '/../MoHinh/CSDL.php';
require_once __DIR__ . '/../MoHinh/SanPham.php';
require_once __DIR__ . '/../MoHinh/DanhMuc.php';
require_once __DIR__ . '/../MoHinh/DonHang.php';
require_once __DIR__ . '/../MoHinh/NguoiDung.php';

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
        // Lấy các tham số lọc và tìm kiếm từ URL
        $status_filter = $_GET['status'] ?? '';
        $search_term = $_GET['search'] ?? '';

        $dh_model = new donhang($this->pdo);
        $danh_sach_don_hang = $dh_model->getOrders($status_filter, $search_term);
        include __DIR__ . '/../GiaoDien/QuanTri/san_pham/quan_ly_don_hang_admin.php';
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
        // Khởi tạo các model cần thiết
        $dh_model = new donhang($this->pdo);
        $sp_model = new sanpham($this->pdo);
        $user_model = new NguoiDung($this->pdo);

        // Lấy các số liệu thống kê
        $stats['total_revenue'] = $dh_model->getTotalRevenue() ?? 0;
        $stats['new_orders_count'] = $dh_model->countNewOrders() ?? 0;
        $stats['customer_count'] = $user_model->countCustomers() ?? 0;
        $stats['product_count'] = $sp_model->countAllSanPham() ?? 0;

        include __DIR__ . '/../GiaoDien/QuanTri/bang_dieu_khien.php';
    }

    // Chức năng: Hiển thị form thêm sản phẩm
    public function them_sp() {
        // Lấy danh sách danh mục để hiển thị trong form
        $dm_model = new danhmuc($this->pdo);
        $danh_sach_danh_muc = $dm_model->getDS_Danhmuc(); // Sửa lỗi gọi hàm không tồn tại
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
            $sale_price = $_POST['sale_price'] ?? 0; // Lấy giá sale

            // 2. Xử lý upload hình ảnh
            $image_url = ''; // Mặc định không có ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../TaiLen/san_pham/"; // Sửa lại đường dẫn
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
            // Gọi hàm themsp với đúng thứ tự tham số
            $sanpham_model->themsp($name, $description, $price, $image_url, $stock_quantity, $category_id, $sale_price);

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
            $danh_sach_danh_muc = $dm_model->getDS_Danhmuc(); // Sửa lỗi gọi hàm không tồn tại

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
            $sale_price = $_POST['sale_price'] ?? 0; // Lấy giá sale
            $existing_image = $_POST['existing_image'] ?? '';

            $image_url = $existing_image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../TaiLen/san_pham/";
                $image_url = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image_url;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }

            $sp_model = new sanpham($this->pdo);
            $sp_model->capnhatsp($id, $name, $description, $price, $image_url, $stock_quantity, $category_id, $sale_price);
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

    // --- QUẢN LÝ DANH MỤC ---
    public function ds_danhmuc() {
        $dm_model = new danhmuc($this->pdo);
        $danh_sach_danh_muc = $dm_model->getDS_Danhmuc();
        include __DIR__ . '/../GiaoDien/QuanTri/nguoi_dung/quan_ly_NV.php';
    }

    public function them_danhmuc() {
        include __DIR__ . '/../GiaoDien/QuanTri/danh_muc/them.php';
    }

    public function xl_them_danhmuc() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            if (!empty($name)) {
                $dm_model = new danhmuc($this->pdo);
                $dm_model->addCategory($name);
            }
            header('Location: admin.php?act=ds_danhmuc&success=added');
            exit;
        }
    }

    public function sua_danhmuc() {
        $id = $_GET['id'] ?? 0;
        $dm_model = new danhmuc($this->pdo);
        $danh_muc = $dm_model->getDanhMucById($id);
        if ($danh_muc) {
            include __DIR__ . '/../GiaoDien/QuanTri/danh_muc/sua.php';
        } else {
            header('Location: admin.php?act=ds_danhmuc');
        }
    }

    public function xl_sua_danhmuc() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            if ($id > 0 && !empty($name)) {
                $dm_model = new danhmuc($this->pdo);
                $dm_model->updateCategory($id, $name);
            }
            header('Location: admin.php?act=ds_danhmuc&success=updated');
            exit;
        }
    }

    public function xoa_danhmuc() {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $dm_model = new danhmuc($this->pdo);
            $dm_model->deleteCategory($id);
        }
        header('Location: admin.php?act=ds_danhmuc&success=deleted');
        exit;
    }
    // --- QUẢN LÝ KHÁCH HÀNG ---
    public function ds_khachhang() {
        // Hàm trợ giúp để xác định thứ hạng khách hàng
        if (!function_exists('getCustomerRank')) {
            function getCustomerRank($spending) {
                if ($spending >= 30000000) {
                    return 'Kim Cương';
                } elseif ($spending >= 15000000) {
                    return 'Vàng';
                } elseif ($spending >= 5000000) {
                    return 'Bạc';
                } else {
                    return 'Đồng';
                }
            }
        }

        $user_model = new NguoiDung($this->pdo);
        // Lấy danh sách khách hàng cùng với tổng chi tiêu của họ
        $danh_sach_khach_hang = $user_model->getDS_KhachHang();
        
        // Thay vì include file cũ, chúng ta sẽ dùng file mới đã được cập nhật
        // để hiển thị danh sách khách hàng.
        include __DIR__ . '/../GiaoDien/QuanTri/nguoi_dung/quan_ly_KH.php';
    }

    public function toggle_trangthai_khachhang() {
        $id = $_GET['id'] ?? 0;
        $status = $_GET['status'] ?? '';
        if ($id > 0 && in_array($status, ['active', 'locked'])) {
            $user_model = new NguoiDung($this->pdo);
            $user_model->toggleUserStatus($id, $status);
        }
        header('Location: admin.php?act=ds_khachhang&success=toggled');
        exit;
    }

    // --- QUẢN LÝ NGƯỜI DÙNG ---
    public function ds_nhanvien() {
        $search_term = $_GET['search'] ?? ''; // Lấy từ khóa tìm kiếm

        // Logic phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; // Số nhân viên mỗi trang
        $offset = ($page - 1) * $limit;

        $user_model = new NguoiDung($this->pdo);

        // Lấy tổng số nhân viên để tính tổng số trang
        $total_nhanvien = $user_model->countNhanVien(['admin', 'staff'], $search_term);
        $total_pages = ceil($total_nhanvien / $limit);

        // Truyền cả vai trò và từ khóa tìm kiếm vào model
        $danh_sach_nhan_vien = $user_model->getDS_NguoiDung(['admin', 'staff'], $search_term, $limit, $offset); 
        include __DIR__ . '/../GiaoDien/QuanTri/nguoi_dung/quan_ly_NV.php';
    }

    public function them_nv() {
        // Các vai trò có thể tạo
        $roles = ['staff', 'admin', 'manager'];
        include __DIR__ . '/../GiaoDien/QuanTri/nguoi_dung/them_nv.php';
    }

    public function xl_them_nv() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? 'staff';

            // --- VALIDATION ---
            if (empty($fullname) || empty($email) || empty($password)) {
                // Có thể thêm thông báo lỗi chi tiết hơn
                header('Location: admin.php?act=them_nv&error=empty');
                exit;
            }

            if ($password !== $confirm_password) {
                header('Location: admin.php?act=them_nv&error=password_mismatch');
                exit;
            }

            $user_model = new NguoiDung($this->pdo);
            if ($user_model->findUserByEmail($email)) {
                header('Location: admin.php?act=them_nv&error=email_exists');
                exit;
            }

            $user_model->createUserByAdmin($fullname, $email, $password, $role);
            header('Location: admin.php?act=ds_nhanvien&success=user_added');
            exit;
        }
    }

    public function xoa_nhanvien() {
        $id = $_GET['id'] ?? 0;
        // Ngăn admin tự xóa tài khoản của chính mình
        if ($id > 0 && $id != $_SESSION['user_id']) {
            $user_model = new NguoiDung($this->pdo);
            $user_model->deleteUser($id);
        }
        header('Location: admin.php?act=ds_nhanvien&success=deleted');
        exit;
    }

    public function sua_nhanvien() {
        $id = $_GET['id'] ?? 0;
        if ($id <= 0) {
            header('Location: admin.php?act=ds_nhanvien');
            exit;
        }

        $user_model = new NguoiDung($this->pdo);
        $nhan_vien = $user_model->findUserById($id);

        if ($nhan_vien) {
            // Các vai trò có thể có trong hệ thống
            $roles = ['customer', 'staff', 'admin'];
            include __DIR__ . '/../GiaoDien/QuanTri/nguoi_dung/sua_nhan_NV.php';
        } else {
            header('Location: admin.php?act=ds_nhanvien&error=notfound');
            exit;
        }
    }

    public function xl_sua_nhanvien() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'customer';

            $user_model = new NguoiDung($this->pdo);
            $user_model->updateUserByAdmin($id, $fullname, $email, $role);

            header('Location: admin.php?act=ds_nhanvien&success=updated');
            exit;
        }
    }

    /**
     * Cung cấp dữ liệu doanh thu hàng tháng cho biểu đồ (AJAX).
     */
    public function ajax_get_chart_data() {
        header('Content-Type: application/json');
        $dh_model = new donhang($this->pdo);
        $monthly_revenue = $dh_model->getMonthlyRevenue();

        // Chuẩn bị dữ liệu cho Chart.js
        $labels = [];
        $data = [];
        foreach ($monthly_revenue as $row) {
            $labels[] = $row['month'];
            $data[] = (float)$row['revenue'];
        }

        echo json_encode(['labels' => $labels, 'data' => $data]);
        exit; // Dừng thực thi để chỉ trả về JSON
    }

    /**
     * Hiển thị trang báo cáo doanh thu theo tháng/năm.
     */
    public function bao_cao_doanh_thu() {
        $dh_model = new donhang($this->pdo);

        // Lấy năm hiện tại và năm được chọn từ filter
        $current_year = date('Y');
        $selected_year = $_GET['year'] ?? $current_year;

        // Lấy dữ liệu doanh thu cho năm được chọn
        $revenue_data = $dh_model->getMonthlyRevenue($selected_year);

        // Tạo một mảng 12 tháng với doanh thu bằng 0
        $monthly_revenue = array_fill(1, 12, 0);
        foreach ($revenue_data as $row) {
            $monthly_revenue[(int)$row['month']] = (float)$row['revenue'];
        }

        include __DIR__ . '/../GiaoDien/QuanTri/bao_cao/doanh_thu.php';
    }
}
?>