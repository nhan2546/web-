<?php
// Load the controller implementation. The repository stores controllers under DieuKhien/
include_once __DIR__ . '/DieuKhien/DieuKhienTrang.php';

$c = new controller();
// Lấy hành động từ URL, nếu không có thì mặc định là trang chủ
$act = $_GET['act'] ?? 'trangchu';

switch ($act) {
    case 'trangchu':
        $c->trangchu();
        break;

    // --- Actions cho Sản phẩm ---
    case 'hienthi_sp':
        $c->hienthi_sp();
        break;
    
    case 'xl_themsp':
        $c->xl_themsp();
        break;

    case 'deletesp':
        $c->xoa_sp();
        break;

    // --- Actions cho Danh mục (bạn tự thêm tương tự) ---
    // case 'hienthi_dm':
    //     $c->hienthi_dm();
    //     break;
    
    default:
        $c->trangchu();
        break;
}
?>