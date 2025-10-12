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
<<<<<<< HEAD
    case 'them_sp': // Route để hiển thị form thêm
        $adminController->them_sp();
        break;
    case 'xl_themsp': // Route để xử lý logic thêm sản phẩm
=======
    case 'them_sp': // Route mới để hiển thị form
        $adminController->hienthi_themsp();
        break;
    case 'xl_themsp': // Route mới để xử lý thêm sản phẩm
>>>>>>> afca77cd971748c14b4406f6a402e47fed186b97
        $adminController->xl_themsp();
        break;
    case 'xoa_sp': // Route để xử lý xóa sản phẩm
        $adminController->xoa_sp();
        break;
    case 'sua_sp': // Route để hiển thị form sửa
        $adminController->sua_sp();
        break;
    case 'xl_suasp': // Route để xử lý logic sửa
        $adminController->xl_suasp();
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