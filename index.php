<?php
session_start(); // Bắt đầu session ở đầu tệp để các biến session hoạt động
// Load the CSDL class definition
require_once __DIR__ . '/MoHinh/CSDL.php';
// Create database connection and get PDO
$db = new CSDL();
$pdo = $db->conn;

// Load controller implementations
include_once __DIR__ . '/DieuKhien/DieuKhienTrang.php';
include_once __DIR__ . '/DieuKhien/dieukhienxacthuc.php';

// Instantiate controllers, passing the PDO connection object
$c = new controller($pdo);
$authController = new DieuKhienXacThuc($pdo);

// Determine action
$act = $_GET['act'] ?? 'trangchu';

// Include header
include __DIR__.'/GiaoDien/trang/bo_cuc/dau_trang.php';

switch ($act) {
    // Page/Product Actions
    case 'trangchu':
        $c->trangchu();
        break;
    case 'hienthi_sp':
        $c->hienthi_sp();
        break;
    case 'chi_tiet_san_pham':
        $c->chi_tiet_san_pham();
        break;
    case 'gio_hang':
        // Chỉ cần include file view giỏ hàng
        include __DIR__.'/GiaoDien/trang/gio_hang.php';
        break;
    case 'thanh_toan':
        // Chỉ cần include file view thanh toán
        include __DIR__.'/GiaoDien/trang/thanh_toan.php';
        break;
    case 'xu_ly_dat_hang':
        // TODO: Thêm logic xử lý đặt hàng ở đây
        echo "Cảm ơn bạn đã đặt hàng!";
        break;

    // Authentication Actions
    case 'dang_nhap':
        $authController->hien_thi_dang_nhap();
        break;
    case 'xu_ly_dang_nhap':
        $authController->xu_ly_dang_nhap();
        break;
    case 'dang_ky':
        $authController->hien_thi_dang_ky();
        break;
    case 'xu_ly_dang_ky':
        $authController->xu_ly_dang_ky();
        break;
    case 'dang_xuat':
        $authController->dang_xuat();
        break;

    default:
        $c->trangchu();
        break;
}
?>

<?php
include __DIR__.'/GiaoDien/trang/bo_cuc/chan_trang.php';
?>