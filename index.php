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
include_once __DIR__ . '/MoHinh/DanhMuc.php'; // Thêm dòng này để load model DanhMuc

// Instantiate controllers, passing the PDO connection object
$c = new controller($pdo);
$authController = new DieuKhienXacThuc($pdo);

// Lấy danh sách danh mục cho menu
$danhMucModel = new DanhMuc($pdo);
$danh_muc_menu = $danhMucModel->getDS_Danhmuc(); // Sửa tên hàm cho đúng với model

// Determine action
$act = $_GET['act'] ?? 'trangchu';

// Include header
// Include header (biến $danh_muc_menu sẽ có sẵn trong file này)
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
        $c->hien_thi_gio_hang();
        break;
    case 'them_vao_gio':
        $c->them_vao_gio();
        break;
    case 'xoa_san_pham_gio_hang':
        $c->xoa_san_pham_gio_hang();
        break;
    case 'cap_nhat_gio_hang':
        $c->cap_nhat_gio_hang();
        break;
    case 'tim_kiem':
        $c->tim_kiem_san_pham();
        break;
    case 'ajax_tim_kiem':
        $c->ajax_tim_kiem();
        break;

    case 'thanh_toan':
        // Chỉ cần include file view thanh toán
        include __DIR__.'/GiaoDien/trang/thanh_toan.php';
        break;
    case 'xu_ly_dat_hang':
        // TODO: Thêm logic xử lý đặt hàng ở đây
        echo " Đặt Hàng Thành Công!";
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
    case 'quen_mat_khau':
        $authController->hien_thi_quen_mat_khau();
        break;

    default:
        $c->trangchu();
        break;
}
?>

<?php
// Include footer
include __DIR__.'/GiaoDien/trang/bo_cuc/chan_trang.php';
?>
