<?php
session_start();

// 1. KHỞI TẠO
require_once __DIR__ . '/MoHinh/CSDL.php';
require_once __DIR__ . '/DieuKhien/DieuKhienTrang.php';
require_once __DIR__ . '/DieuKhien/DieuKhienXacThuc.php';
require_once __DIR__ . '/GiaoDien/trang/bo_cuc/breadcrumb_helper.php';

$db = new CSDL();
$pdo = $db->conn;

$c = new controller($pdo); // Controller cho các trang nội dung
$authController = new DieuKhienXacThuc($pdo); // Controller cho xác thực

// 3. TÁCH BIỆT LOGIC XỬ LÝ VÀ HIỂN THỊ
$act = $_GET['act'] ?? 'trangchu';

// 4. ĐIỀU HƯỚNG (ROUTING)
// Ưu tiên xử lý các action logic trước khi hiển thị bất kỳ HTML nào.
// Các phương thức này sẽ tự gọi header() và exit().
switch ($act) {
    case 'them_vao_gio':            $c->them_vao_gio(); break;
    case 'xoa_san_pham_gio_hang':   $c->xoa_san_pham_gio_hang(); break;
    case 'cap_nhat_gio_hang':       $c->cap_nhat_gio_hang(); break;
    case 'ap_dung_voucher':         $c->ap_dung_voucher(); break;
    case 'xoa_voucher':             $c->xoa_voucher(); break;
    case 'xu_ly_dang_nhap':         $authController->xu_ly_dang_nhap(); break;
    case 'xu_ly_dang_ky':           $authController->xu_ly_dang_ky(); break;
    case 'dang_xuat':               $authController->dang_xuat(); break;
    case 'cap_nhat_tai_khoan':      $c->cap_nhat_tai_khoan(); break;
    case 'doi_mat_khau':            $c->doi_mat_khau(); break;
    case 'cap_nhat_avatar':         $c->cap_nhat_avatar(); break;
    case 'xu_ly_dat_hang':          $c->xu_ly_dat_hang(); break;
    case 'them_binh_luan':          $c->them_binh_luan(); break;
    case 'ajax_tim_kiem':           $c->ajax_tim_kiem(); break; // AJAX cũng là logic, không cần layout
}

// Tạo dữ liệu breadcrumb trước khi hiển thị header
$breadcrumb_data = [];

// Nếu script vẫn chạy đến đây, nghĩa là action không phải là logic xử lý ở trên.
// Bây giờ chúng ta có thể an toàn hiển thị layout.
include __DIR__ . '/GiaoDien/trang/bo_cuc/dau_trang.php'; // 5. HIỂN THỊ HEADER

// 6. HIỂN THỊ NỘI DUNG TRANG
switch ($act) {
    // Các trang chính
    case 'trangchu':
        $c->trangchu();
        break;
    case 'hienthi_sp':
        $c->hienthi_sp();
        break;
    case 'danhmuc':
        $breadcrumb_data['category_info'] = $c->hienthi_sp_theo_danhmuc(true);
        $c->hienthi_sp_theo_danhmuc();
        break;
    case 'chi_tiet_san_pham':
        $breadcrumb_data['san_pham'] = $c->chi_tiet_san_pham(true);
        $c->chi_tiet_san_pham();
        break;
    case 'tim_kiem':
        $breadcrumb_data['keyword'] = $_GET['keyword'] ?? '';
        $c->tim_kiem_san_pham();
        break;

    // Giỏ hàng
    case 'gio_hang':
        $c->hien_thi_gio_hang();
        break;
    case 'thanh_toan':
        $c->hien_thi_thanh_toan();
        break;

    // Xác thực & Tài khoản người dùng
    case 'dang_nhap':
        $authController->hien_thi_dang_nhap();
        break;
    case 'dang_ky':
        $authController->hien_thi_dang_ky();
        break;
    case 'thong_tin_tai_khoan':
        $c->thong_tin_tai_khoan();
        break;
    case 'lich_su_mua_hang':
        $c->lich_su_mua_hang();
        break;
    case 'chi_tiet_don_hang_user':
        $c->chi_tiet_don_hang_user();
        break;
    case 'thu_cu_doi_moi':
        $c->thu_cu_doi_moi();
        break;

    default:
        $c->trangchu();
        break;
}

// Tạo breadcrumb HTML và JSON-LD
$breadcrumbs = generate_breadcrumbs($pdo, $act, $breadcrumb_data);

// Hiển thị breadcrumb
include __DIR__ . '/GiaoDien/trang/bo_cuc/breadcrumb_view.php';

// 7. HIỂN THỊ FOOTER
include __DIR__ . '/GiaoDien/trang/bo_cuc/chan_trang.php';
?>
