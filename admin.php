<?php
session_start();

// 1. KIỂM TRA BẢO MẬT
// Kiểm tra xem người dùng đã đăng nhập và có phải là admin không.
// Nếu không, chuyển hướng họ về trang đăng nhập.
$allowed_roles = ['admin', 'staff'];
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    header('Location: index.php?act=dang_nhap&error=unauthorized');
    exit;
}

// 2. KHỞI TẠO
// Tải các tệp cần thiết và khởi tạo đối tượng.
require_once __DIR__ . '/MoHinh/CSDL.php';
require_once __DIR__ . '/DieuKhien/DieuKhienQuanTri.php';

$db = new CSDL();
$pdo = $db->conn;
$adminController = new DieuKhienQuanTri($pdo);

// 3. ĐIỀU HƯỚNG (ROUTING)
// Xác định hành động (action) từ URL, nếu không có thì mặc định là 'dashboard'.
$act = $_GET['act'] ?? 'dashboard';

// 4. XỬ LÝ CÁC HÀNH ĐỘNG LOGIC (POST, DELETE, UPDATE...)
// Các case này sẽ xử lý dữ liệu và thường kết thúc bằng header() và exit().
// Chúng được đặt trước khi bất kỳ HTML nào được in ra.
switch ($act) {
    // Xử lý logic cho sản phẩm (POST từ form thêm/sửa)
    case 'them_sp':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->them_sp(); // Phương thức này đã xử lý POST và exit()
        }
        break;
    case 'xl_suasp':
        $adminController->xl_suasp();
        break;
    case 'xoa_sp':
        $adminController->xoa_sp();
        break;

    // Xử lý logic cho đơn hàng
    case 'capnhat_trangthai_donhang':
        $adminController->capnhat_trangthai_donhang();
        break;

    // Xử lý logic cho danh mục
    case 'xl_them_danhmuc':
        $adminController->xl_them_danhmuc();
        break;
    case 'xl_sua_danhmuc':
        $adminController->xl_sua_danhmuc();
        break;
    case 'xoa_danhmuc':
        $adminController->xoa_danhmuc();
        break;

    // Xử lý logic cho nhân viên
    case 'xl_them_nv':
        $adminController->xl_them_nv();
        break;
    case 'xl_sua_nhanvien':
        $adminController->xl_sua_nhanvien();
        break;
    case 'xoa_nhanvien':
        $adminController->xoa_nhanvien();
        break;

    // Xử lý logic cho khách hàng
    case 'toggle_trangthai_khachhang':
        $adminController->toggle_trangthai_khachhang();
        break;

    // Xử lý logic cho voucher
    case 'xl_them_voucher':
        $adminController->xl_them_voucher();
        break;
    case 'xl_sua_voucher':
        $adminController->xl_sua_voucher();
        break;
    case 'xoa_voucher':
        $adminController->xoa_voucher();
        break;

    // Xử lý logic cho voucher
    case 'xl_them_voucher':
        $adminController->xl_them_voucher();
        break;
    case 'xl_sua_voucher':
        $adminController->xl_sua_voucher();
        break;
    case 'xoa_voucher':
        $adminController->xoa_voucher();
        break;

    // Xử lý AJAX
    case 'ajax_get_chart_data':
        $adminController->ajax_get_chart_data();
        break;
}

// BẮT ĐẦU HIỂN THỊ GIAO DIỆN (chỉ chạy cho các action hiển thị)
include __DIR__ . '/GiaoDien/QuanTri/nguoi_dung/dau_trang_admin.php';

// 5. HIỂN THỊ GIAO DIỆN (GET requests)
// Các case này chỉ gọi các phương thức để lấy dữ liệu và include view.
switch ($act) {
    // Quản lý sản phẩm
    case 'ds_sanpham': $adminController->ds_sanpham(); break;
    case 'them_sp': $adminController->them_sp(); break;
    case 'sua_sp': $adminController->sua_sp(); break;

    // Quản lý đơn hàng
    case 'ds_donhang': $adminController->ds_donhang(); break;
    case 'ct_donhang': $adminController->ct_donhang(); break;

    // Quản lý Danh mục
    case 'ds_danhmuc': $adminController->ds_danhmuc(); break;
    case 'them_danhmuc': $adminController->them_danhmuc(); break;
    case 'sua_danhmuc': $adminController->sua_danhmuc(); break;

    // Quản lý Nhan vien    
    case 'ds_nhanvien': $adminController->ds_nhanvien(); break;
    case 'them_nv': $adminController->them_nv(); break;
    case 'sua_nhanvien': $adminController->sua_nhanvien(); break;

    // Quản lý Khách hàng
    case 'ds_khachhang': $adminController->ds_khachhang(); break;

    // Quản lý Voucher
    case 'ds_voucher': $adminController->ds_voucher(); break;
    case 'them_voucher': $adminController->them_voucher(); break;
    case 'sua_voucher': $adminController->sua_voucher(); break;

    case 'bao_cao_doanh_thu': $adminController->bao_cao_doanh_thu(); break;

    // Quản lý Bảo hành
    case 'ds_baohanh': $adminController->ds_baohanh(); break;

    // Mặc định: hiển thị Bảng điều khiển
    default: $adminController->dashboard(); break;
}

// Bao gồm footer của trang admin
include __DIR__ . '/GiaoDien/QuanTri/nguoi_dung/chan_trang_admin.php';
?>
