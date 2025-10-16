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

// Tách biệt logic xử lý và hiển thị
// Các case xử lý logic (thêm, sửa, xóa, cập nhật) sẽ không include view trực tiếp
switch ($act) {
    case 'xl_themsp': // Xử lý logic thêm sản phẩm
        $adminController->xl_themsp();
        break;
    case 'xl_suasp': // Xử lý logic sửa sản phẩm
        $adminController->xl_suasp();
        break;
    case 'xoa_sp': // Xử lý xóa sản phẩm
        $adminController->xoa_sp();
        break;
    case 'capnhat_trangthai_donhang': // Xử lý cập nhật trạng thái đơn hàng
        $adminController->capnhat_trangthai_donhang();
        break;
    case 'xl_them_danhmuc':
        $adminController->xl_them_danhmuc();
        break;
    case 'xl_sua_danhmuc':
        $adminController->xl_sua_danhmuc();
        break;
    case 'xoa_danhmuc':
        $adminController->xoa_danhmuc();
        break;
    case 'xoa_nhanvien':
        $adminController->xoa_nhanvien();
        break;
    case 'xl_sua_nhanvien':
        $adminController->xl_sua_nhanvien();
        break;
    case 'xl_them_nv':
        $adminController->xl_them_nv();
        break;
    case 'toggle_trangthai_khachhang':
        $adminController->toggle_trangthai_khachhang();
        break;
    case 'xl_them_voucher':
        $adminController->xl_them_voucher();
        break;
    case 'xl_sua_voucher':
        $adminController->xl_sua_voucher();
        break;
    case 'xoa_voucher':
        $adminController->xoa_voucher();
        break;
    case 'ajax_get_chart_data':
        $adminController->ajax_get_chart_data();
        break;
}

// BẮT ĐẦU HIỂN THỊ GIAO DIỆN
include __DIR__ . '/GiaoDien/QuanTri/nguoi_dung/dau_trang_admin.php';

// Các case chỉ để hiển thị giao diện
switch ($act) {
    // Quản lý sản phẩm
    case 'ds_sanpham':
        $adminController->ds_sanpham();
        break;
    case 'them_sp':
        $adminController->them_sp();
        break;
    case 'sua_sp':
        $adminController->sua_sp();
        break;

    // Quản lý đơn hàng
    case 'ds_donhang':
        $adminController->ds_donhang();
        break;
    case 'ct_donhang':
        $adminController->ct_donhang();
        break;

    // Quản lý Danh mục
    case 'ds_danhmuc':
        $adminController->ds_danhmuc();
        break;
    case 'them_danhmuc':
        $adminController->them_danhmuc();
        break;
    case 'sua_danhmuc':
        $adminController->sua_danhmuc();
        break;

    // Quản lý Nhan vien    
    case 'ds_nhanvien':
        $adminController->ds_nhanvien(); // Đã tồn tại
        break;
    case 'sua_nhanvien':
        $adminController->sua_nhanvien(); // Sửa từ sua_nguoidung
        break;
    case 'them_nv':
        $adminController->them_nv();
        break;

    // Quản lý Khách hàng
    case 'ds_khachhang':
        $adminController->ds_khachhang();
        break;

    // Quản lý Voucher
    case 'ds_voucher':
        $adminController->ds_voucher();
        break;
    case 'them_voucher':
        $adminController->them_voucher();
        break;
    case 'sua_voucher':
        $adminController->sua_voucher();
        break;

    case 'bao_cao_doanh_thu':
        $adminController->bao_cao_doanh_thu();
        break;
    case 'bao_cao_san_pham':
        $adminController->bao_cao_san_pham();
        break;

    // Mặc định: hiển thị Bảng điều khiển
    default:
        $adminController->dashboard();
        break;
}
// Bao gồm footer của trang admin
include __DIR__ . '/GiaoDien/QuanTri/nguoi_dung/chan_trang_admin.php';
?>