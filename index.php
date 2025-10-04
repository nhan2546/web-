<?php
session_start();

// Load the CSDL class definition
require_once __DIR__ . '/MoHinh/CSDL.php';

// Create a single database connection object
$db = new CSDL();
$pdo = $db->conn; // Get the connection for dependency injection

// Load controller implementations
include_once __DIR__ . '/DieuKhien/DieuKhienTrang.php';
include_once __DIR__ . '/DieuKhien/DieuKhienXacThuc.php';

// Instantiate controllers, passing the PDO connection object
$c = new controller($pdo);
$authController = new DieuKhienXacThuc($pdo);

// Get the action from the URL, default to 'trangchu'
$act = $_GET['act'] ?? 'trangchu';

// Route the request to the appropriate controller method
switch ($act) {
    // --- Page/Product Actions ---
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

    // --- Authentication Actions ---
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