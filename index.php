<?php ini_set('display_errors', 1);
error_reporting(E_ALL); ?>
<?php session_start(); ?>

<?php include __DIR__.'/GiaoDien/trang/bo_cuc/dau_trang.php' ; ?>
<?php
// Load the CSDL class definition (creates a PDO connection)
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

switch ($act) {
    // Page/Product Actions
    case 'trangchu':
        $c->trangchu();
        break;
    case 'hienthi_sp':
        $c->hienthi_sp();
        break;
    case 'xl_themsp':
        $c->xl_themsp();
        break;
    case 'deletesp':
        $c->xoa_sp();
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
<?php include __DIR__.'/GiaoDien/trang/bo_cuc/chan_trang.php'; ?>
  
