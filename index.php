<?php
// File: index.php

// Start session
session_start();

// 1. KHỞI TẠO (Merged includes)
require_once __DIR__ . '/MoHinh/CSDL.php';
require_once __DIR__ . '/DieuKhien/DieuKhienTrang.php';
require_once __DIR__ . '/DieuKhien/dieukhienxacthuc.php';
require_once __DIR__ . '/GiaoDien/trang/bo_cuc/breadcrumb_helper.php';
// Initialize Database
$db = new CSDL();
$pdo = $db->conn;

// Instantiate Controllers
$c = new controller($pdo);
$authController = new DieuKhienXacThuc($pdo);

// Get action
$act = $_GET['act'] ?? 'trangchu';

// Handle logic actions first
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
    case 'ajax_tim_kiem':           $c->ajax_tim_kiem(); break;
}

// If we are here, it's a display action.

// Prepare breadcrumb data
$breadcrumb_data = [];
$breadcrumbs = generate_breadcrumbs($pdo, $act, $breadcrumb_data); // Di chuyển việc tạo breadcrumbs lên trước

// 5. HIỂN THỊ HEADER
include __DIR__ . '/GiaoDien/trang/bo_cuc/dau_trang.php';
// 6. HIỂN THỊ NỘI DUNG TRANG
switch ($act) {
    case 'trangchu':
        $c->trangchu();
        break;
    case 'hienthi_sp':
        $c->hienthi_sp();
        break;
    case 'danhmuc':
        $breadcrumbs = generate_breadcrumbs($pdo, $act, ['category_info' => $c->hienthi_sp_theo_danhmuc(true)]);
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
    case 'gio_hang':
        $c->hien_thi_gio_hang();
        break;
    case 'thanh_toan':
        $c->hien_thi_thanh_toan();
        break;
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
    case 'chi_tiet_don_hang':
        $c->chi_tiet_don_hang();
        break;
    case 'dat_hang_thanh_cong':
        $c->dat_hang_thanh_cong();
        break;
    case 'thu_cu_doi_moi':
        $c->thu_cu_doi_moi();
        break;
    default:
        $c->trangchu();
        break;
}

// 7. HIỂN THỊ FOOTER
include __DIR__ . '/GiaoDien/trang/bo_cuc/chan_trang.php';

?>
