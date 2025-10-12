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

// TÁCH BIỆT LOGIC XỬ LÝ VÀ HIỂN THỊ
// Các case xử lý logic (đăng nhập, thêm giỏ hàng,...) sẽ không include view trực tiếp
switch ($act) {
    case 'them_vao_gio':
        $c->them_vao_gio();
        break;
    case 'xoa_san_pham_gio_hang':
        $c->xoa_san_pham_gio_hang();
        break;
    case 'cap_nhat_gio_hang':
        $c->cap_nhat_gio_hang();
        break;
    case 'xu_ly_dang_nhap':
        $authController->xu_ly_dang_nhap();
        break;
    case 'xu_ly_dang_ky':
        $authController->xu_ly_dang_ky();
        break;
    case 'dang_xuat':
        $authController->dang_xuat();
        break;
    case 'cap_nhat_tai_khoan':
        $c->cap_nhat_tai_khoan();
        break;
    case 'doi_mat_khau':
        $c->doi_mat_khau();
        break;
    case 'cap_nhat_avatar':
        $c->cap_nhat_avatar();
        break;
    // Các case xử lý logic khác...


}

// BẮT ĐẦU HIỂN THỊ GIAO DIỆN
// Include header (biến $danh_muc_menu sẽ có sẵn trong file này)
include __DIR__.'/GiaoDien/trang/bo_cuc/dau_trang.php';

// Khối switch này chỉ xử lý các action có hiển thị giao diện
switch ($act) {
    case 'trangchu':
        $c->trangchu();
        break;
    case 'hienthi_sp':
        $c->hienthi_sp();
        break;
    case 'danhmuc':
        $c->hienthi_sp_theo_danhmuc();
        break;
    case 'chi_tiet_san_pham':
        $c->chi_tiet_san_pham();
        break;
    case 'gio_hang':
        $c->hien_thi_gio_hang();
        break;
    case 'tim_kiem':
        $c->tim_kiem_san_pham();
        break;
    case 'ajax_tim_kiem': // AJAX không cần header/footer nhưng để đây cho gọn
        $c->ajax_tim_kiem();
        break;
    case 'thanh_toan':
        include __DIR__.'/GiaoDien/trang/thanh_toan.php';
        break;
    case 'dang_nhap':
        $authController->hien_thi_dang_nhap();
        break;
    case 'dang_ky':
        $authController->hien_thi_dang_ky();
        break;
    case 'quen_mat_khau':
        $authController->hien_thi_quen_mat_khau();
        break;
    case 'lich_su_mua_hang':
        $c->lich_su_mua_hang();
        break;
    case 'thong_tin_tai_khoan':
        $c->thong_tin_tai_khoan();
        break;
}

// Include footer
include __DIR__.'/GiaoDien/trang/bo_cuc/chan_trang.php';
?>
