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

// Bao gồm header của trang admin
include __DIR__ . '/GiaoDien/QuanTri/bo_cuc/dau_trang.php';

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

switch ($act) {
    // Quản lý sản phẩm
    case 'ds_sanpham': // Danh sách sản phẩm
        $adminController->ds_sanpham();
        break;
<<<<<<< HEAD
    case 'them_sp': // Route để hiển thị form thêm
        $adminController->them_sp();
        break;
    case 'xl_themsp': // Route để xử lý logic thêm sản phẩm
=======
    case 'them_sp': // Hiển thị form thêm sản phẩm
        $adminController->them_sp();
        break;
    case 'xl_themsp': // Xử lý logic thêm sản phẩm
>>>>>>> 7d4bf51bfce35c5ecf8d11cfcd0ce29d60fc942b
        $adminController->xl_themsp();
        break;
    case 'sua_sp': // Hiển thị form sửa sản phẩm
        $adminController->sua_sp();
        break;
    case 'xl_suasp': // Xử lý logic sửa sản phẩm
        $adminController->xl_suasp();
        break;
    case 'xoa_sp': // Xử lý xóa sản phẩm
        $adminController->xoa_sp();
        break;

    // Quản lý đơn hàng
    case 'ds_donhang': // Danh sách đơn hàng
        $adminController->ds_donhang();
        break;
    case 'ct_donhang': // Chi tiết đơn hàng
        $adminController->ct_donhang();
        break;
    case 'capnhat_trangthai_donhang': // Xử lý cập nhật trạng thái đơn hàng
        $adminController->capnhat_trangthai_donhang();
        break;

    // Quản lý Danh mục
    case 'ds_danhmuc':
        $adminController->ds_danhmuc();
        break;
    case 'them_danhmuc':
        $adminController->them_danhmuc();
        break;
    case 'xl_them_danhmuc':
        $adminController->xl_them_danhmuc();
        break;
    case 'sua_danhmuc':
        $adminController->sua_danhmuc();
        break;
    case 'xl_sua_danhmuc':
        $adminController->xl_sua_danhmuc();
        break;
    case 'xoa_danhmuc':
        $adminController->xoa_danhmuc();
        break;

    // Quản lý Người dùng
    case 'ds_nguoidung':
        $adminController->ds_nguoidung();
        break;
    case 'xoa_nguoidung':
        $adminController->xoa_nguoidung();
        break;

    // Các route khác...

    // Mặc định: hiển thị Bảng điều khiển
    default:
        $adminController->dashboard();
        break;
}

// Bao gồm footer của trang admin
include __DIR__ . '/GiaoDien/QuanTri/bo_cuc/chan_trang.php';
?>