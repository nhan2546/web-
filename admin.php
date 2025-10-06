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
include 'GiaoDien/QuanTri/bo_cuc/dau_trang.php';

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
    case 'ds_sanpham':
        $adminController->ds_sanpham();
        break;
    case 'xl_themsp': // Route mới để xử lý thêm sản phẩm
        $adminController->xl_themsp();
        break;
    case 'xoa_sp': // Route mới để xử lý xóa sản phẩm
        $adminController->xoa_sp();
        break;

    // Các route quản trị khác...

    // Mặc định: hiển thị Bảng điều khiển
    default:
        $adminController->dashboard();
        break;
}

// Bao gồm footer của trang admin
include 'GiaoDien/QuanTri/bo_cuc/chan_trang.php';
?>