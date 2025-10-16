<?php
ob_start();
// File: logic_handler.php

// This file handles all logic-based actions that do not render a full HTML page.
// It should be included at the very top of index.php.

session_start();

// 1. KHỞI TẠO
require_once __DIR__ . '/MoHinh/CSDL.php';
require_once __DIR__ . '/DieuKhien/DieuKhienTrang.php';
require_once __DIR__ . '/DieuKhien/DieuKhienXacThuc.php';

$db = new CSDL();
$pdo = $db->conn;

$c = new controller($pdo); // Controller cho các trang nội dung
$authController = new DieuKhienXacThuc($pdo); // Controller cho xác thực

$act = $_GET['act'] ?? 'trangchu';

// Danh sách các action chỉ xử lý logic và chuyển hướng
$logic_actions = [
    'them_vao_gio', 'xoa_san_pham_gio_hang', 'cap_nhat_gio_hang', 
    'ap_dung_voucher', 'xoa_voucher', 'xu_ly_dang_nhap', 'xu_ly_dang_ky', 
    'dang_xuat', 'cap_nhat_tai_khoan', 'doi_mat_khau', 'cap_nhat_avatar', 
    'xu_ly_dat_hang', 'them_binh_luan', 'ajax_tim_kiem'
];

if (in_array($act, $logic_actions)) {
    // Các action này sẽ tự gọi header() và exit(), kết thúc script tại đây.
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
    // Mặc dù các hàm trên đều gọi exit(), thêm một exit ở đây để chắc chắn.
    exit();
}

// Nếu $act không phải là một logic action, script sẽ tiếp tục chạy đến index.php

?>