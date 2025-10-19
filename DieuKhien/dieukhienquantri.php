<?php
require_once __DIR__ . '/../MoHinh/CSDL.php';
require_once __DIR__ . '/../MoHinh/SanPham.php';
require_once __DIR__ . '/../MoHinh/DanhMuc.php';
require_once __DIR__ . '/../MoHinh/DonHang.php';
require_once __DIR__ . '/../MoHinh/NguoiDung.php';
require_once __DIR__ . '/../MoHinh/Voucher.php';

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
            include __DIR__ . '/../GiaoDien/QuanTri/san_pham/chi_tiet_don_hang_admin.php';
        } else {
            header('Location: admin.php?act=ds_donhang'); // Giả sử tệp admin là admin.php
        }
    }

    // Chức năng: Cập nhật trạng thái đơn hàng
    public function capnhat_trangthai_donhang() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $new_status = isset($_POST['status']) ? $_POST['status'] : '';

            // Thêm kiểm tra hợp lệ cho trạng thái mới
            $allowed_statuses = ['pending', 'confirmed', 'shipping', 'delivered', 'success', 'cancelled'];
            if ($order_id > 0 && !empty($new_status) && in_array($new_status, $allowed_statuses)) {
                $dh_model = new donhang($this->pdo);
                if ($dh_model->updateOrderStatus($order_id, $new_status)) {
                    echo 'OK';
                    exit; // Dừng script sau khi trả về kết quả
                } else {
                    // Trả về lỗi nếu model không cập nhật được
                    header("HTTP/1.1 500 Internal Server Error");
                    echo "Lỗi khi cập nhật trạng thái trong CSDL.";
                    exit;
                }
            }
            // Trả về lỗi nếu dữ liệu không hợp lệ
            header("HTTP/1.1 400 Bad Request");
            echo "Dữ liệu không hợp lệ hoặc thiếu thông tin.";
            exit;
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
        // Hợp nhất logic hiển thị và xử lý form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // --- XỬ LÝ KHI FORM ĐƯỢC GỬI ĐI (POST) ---
            $this->xu_ly_them_sp_post();
        } else {
            // --- HIỂN THỊ FORM KHI TRUY CẬP (GET) ---
            $this->hien_thi_form_them_sp();
        }
    }

    /**
     * Hiển thị giao diện form thêm sản phẩm.
     * Được gọi bởi them_sp() khi request là GET.
     */
    private function hien_thi_form_them_sp() {
        // Lấy danh sách danh mục để hiển thị trong form
        $category_model = new danhmuc($this->pdo);
        $danh_sach_danh_muc = $category_model->getDS_Danhmuc();
        // Kiểm tra xem có lỗi nào được truyền qua URL không (ví dụ: từ xử lý POST)
        $error = $_GET['error'] ?? null;
        include __DIR__ . '/../GiaoDien/QuanTri/san_pham/them.php';
    }

    /**
     * Xử lý dữ liệu từ form thêm sản phẩm.
     * Được gọi bởi them_sp() khi request là POST.
     */
    private function xu_ly_them_sp_post() {
        // 1. Lấy dữ liệu từ form
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $sale_price = $_POST['sale_price'] ?? null; // Lấy giá khuyến mãi
        $description = $_POST['description'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;
        $category_id = $_POST['category_id'] ?? 0;
        // $highlights = $_POST['highlights'] ?? null; // Tạm thời vô hiệu hóa vì chưa có cột trong DB

        // Kiểm tra dữ liệu cơ bản
        if (empty($name) || empty($price) || empty($category_id)) {
            // Nếu thiếu dữ liệu, quay lại form với thông báo lỗi
            header('Location: admin.php?act=them_sp&error=empty_fields');
            exit;
        }

        // 2. Lấy dữ liệu JSON của các phiên bản và xác định ảnh đại diện
        $variant_colors = $_POST['variant_color'] ?? [];
        $variant_images = $_FILES['variant_image'] ?? [];
        $variants_data = [];

        // Xử lý upload ảnh cho từng phiên bản
        $target_dir = __DIR__ . "/../TaiLen/san_pham/";
        foreach ($variant_colors as $index => $color) {
            $image_url = '';
            // Kiểm tra xem có file được tải lên cho phiên bản này không
            if (isset($variant_images['name'][$index]) && $variant_images['error'][$index] == 0) {
                $image_url = time() . '_' . basename($variant_images["name"][$index]);
                $target_file = $target_dir . $image_url;
                move_uploaded_file($variant_images['tmp_name'][$index], $target_file);
            }

            if (!empty($color) && !empty($image_url)) {
                $variants_data[] = [
                    'color' => $color,
                    'image' => $image_url,
                ];
            }
        }

        // Xử lý ảnh đại diện chính
        $image_url = ''; 
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
            $image_url = time() . '_main_' . basename($_FILES["image_url"]["name"]);
            $target_file = $target_dir . $image_url;
            move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file);
        } 
        // Nếu không có ảnh đại diện chính, tự động lấy ảnh của phiên bản đầu tiên
        elseif (!empty($variants_data)) {
            $image_url = $variants_data[0]['image'];
        }
        $variants_json = json_encode($variants_data);

        // 3. Tạo đối tượng model và gọi hàm thêm
        $sanpham_model = new sanpham($this->pdo);
        // Tạm thời bỏ highlights
        $sanpham_model->themsp($name, $description, $price, $image_url, $quantity, $category_id, $sale_price, !empty($variants_data) ? $variants_json : null);

        // 4. Chuyển hướng về trang danh sách sản phẩm của admin
        header('Location: admin.php?act=ds_sanpham&success=added');
        exit;
    }
    // Chức năng: Hiển thị form sửa sản phẩm
    public function sua_sp() {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $sp_model = new sanpham($this->pdo);
            $san_pham = $sp_model->getone_sanpham($id); // Lấy thông tin sản phẩm cần sửa

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
            $quantity = $_POST['quantity'] ?? 0; // Đã đúng
            $category_id = $_POST['category_id'] ?? 0;
            $sale_price = $_POST['sale_price'] ?? null; // Lấy giá khuyến mãi
            $existing_image_url = $_POST['existing_image_url'] ?? '';
            // $highlights = $_POST['highlights'] ?? null;

            // Lấy dữ liệu phiên bản từ form
            $variant_colors = $_POST['variant_color'] ?? [];
            $existing_variant_images = $_POST['existing_variant_image'] ?? [];
            $new_variant_images = $_FILES['variant_image'] ?? [];
            $variants_data = [];

            $target_dir = __DIR__ . "/../TaiLen/san_pham/";
            foreach ($variant_colors as $index => $color) {
                $image_url_for_variant = $existing_variant_images[$index] ?? ''; // Giữ ảnh cũ làm mặc định

                // Nếu có ảnh mới được tải lên cho vị trí này, xử lý nó
                if (isset($new_variant_images['name'][$index]) && $new_variant_images['error'][$index] == 0) {
                    $image_url_for_variant = time() . '_' . basename($new_variant_images["name"][$index]);
                    $target_file = $target_dir . $image_url_for_variant;
                    move_uploaded_file($new_variant_images["tmp_name"][$index], $target_file);
                }

                if (!empty($color) && !empty($image_url_for_variant)) {
                    $variants_data[] = [
                        'color' => $color,
                        'image' => $image_url_for_variant,
                    ];
                }
            }

            // Xử lý ảnh đại diện chính
            $image_url = $existing_image_url; // Mặc định giữ lại ảnh cũ
            if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
                // Nếu có ảnh mới, tải lên và ghi đè
                $image_url = time() . '_main_' . basename($_FILES["image_url"]["name"]);
                $target_file = $target_dir . $image_url;
                move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file);
            } 
            // Nếu không có ảnh đại diện chính và không có ảnh cũ, lấy ảnh của phiên bản đầu tiên
            elseif (empty($image_url) && !empty($variants_data)) {
                $image_url = $variants_data[0]['image'];
            }

            $variants_json = json_encode($variants_data);

            $sp_model = new sanpham($this->pdo);
            // Tạm thời bỏ highlights
            $sp_model->capnhatsp($id, $name, $description, $price, $image_url, $quantity, $category_id, $sale_price, !empty($variants_data) ? $variants_json : null);

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
        include __DIR__ . '/../GiaoDien/QuanTri/danh_muc/danh_sach.php';
    }

    public function them_danhmuc() {
        include __DIR__ . '/../GiaoDien/QuanTri/danh_muc/them.php';
    }

    public function xl_them_danhmuc() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            if (!empty($name)) {}
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

    // --- QUẢN LÝ VOUCHER ---
    public function ds_voucher() {
        $voucher_model = new Voucher($this->pdo);
        $danh_sach_voucher = $voucher_model->getAllVouchers();
        include __DIR__ . '/../GiaoDien/QuanTri/voucher/danh_sach.php';
    }

    public function them_voucher() {
        include __DIR__ . '/../GiaoDien/QuanTri/voucher/them.php';
    }

    public function xl_them_voucher() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = strtoupper(trim($_POST['code']));
            $description = $_POST['description'] ?? '';
            $discount_type = $_POST['discount_type'] ?? 'fixed';
            $discount_value = $_POST['discount_value'] ?? 0;
            $min_order_amount = $_POST['min_order_amount'] ?? 0;
            $usage_limit = $_POST['usage_limit'] ?? null;
            $expires_at = $_POST['expires_at'] ?? null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $voucher_model = new Voucher($this->pdo);

            // KIỂM TRA XEM MÃ VOUCHER ĐÃ TỒN TẠI CHƯA
            if ($voucher_model->findVoucherByCode($code)) {
                // Nếu đã tồn tại, chuyển hướng lại với thông báo lỗi
                header('Location: admin.php?act=them_voucher&error=code_exists');
                exit;
            }

            $voucher_model->addVoucher($code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active);
            
            header('Location: admin.php?act=ds_voucher&success=added');
            exit;
        }
    }

    public function sua_voucher() {
        $id = $_GET['id'] ?? 0;
        $voucher_model = new Voucher($this->pdo);
        $voucher = $voucher_model->getVoucherById($id);
        if ($voucher) {
            include __DIR__ . '/../GiaoDien/QuanTri/voucher/sua.php';
        } else {
            header('Location: admin.php?act=ds_voucher');
        }
    }

    public function xl_sua_voucher() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $code = strtoupper(trim($_POST['code']));
            $description = $_POST['description'] ?? '';
            $discount_type = $_POST['discount_type'] ?? 'fixed';
            $discount_value = $_POST['discount_value'] ?? 0;
            $min_order_amount = $_POST['min_order_amount'] ?? 0;
            $usage_limit = $_POST['usage_limit'] ?? null;
            $expires_at = $_POST['expires_at'] ?? null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $voucher_model = new Voucher($this->pdo);
            $voucher_model->updateVoucher($id, $code, $description, $discount_type, $discount_value, $min_order_amount, $usage_limit, $expires_at, $is_active);

            header('Location: admin.php?act=ds_voucher&success=updated');
            exit;
        }
    }

    public function xoa_voucher() {
        $id = $_GET['id'] ?? 0;
        $voucher_model = new Voucher($this->pdo);
        $voucher_model->deleteVoucher($id);
        header('Location: admin.php?act=ds_voucher&success=deleted');
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
