<?php
// File: index.php

// Đầu tiên, gọi bộ xử lý logic. 
// Nếu action là logic, nó sẽ xử lý và thoát script.
require_once __DIR__ . '/logic_handler.php';

// Nếu script tiếp tục ở đây, có nghĩa là chúng ta cần hiển thị một trang.

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
        $c->hienthi_sp_theo_danhmuc();
        break;
    case 'chi_tiet_san_pham':
        $c->chi_tiet_san_pham();
        break;
    case 'tim_kiem':
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