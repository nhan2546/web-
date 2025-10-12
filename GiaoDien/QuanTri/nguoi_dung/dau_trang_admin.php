<?php
// Giả sử bạn đã có hàm để lấy tên trang hiện tại dựa vào 'act'
function get_current_page_title($act) {
    switch ($act) {
        case 'ds_sanpham': return 'Quản lý Sản phẩm';
        case 'them_sp': return 'Thêm Sản phẩm';
        case 'sua_sp': return 'Sửa Sản phẩm';
        case 'ds_donhang': return 'Quản lý Đơn hàng';
        case 'ct_donhang': return 'Chi tiết Đơn hàng';
        case 'ds_danhmuc': return 'Quản lý Danh mục';
        case 'them_danhmuc': return 'Thêm Danh mục';
        case 'sua_danhmuc': return 'Sửa Danh mục';
        case 'ds_nguoidung': return 'Quản lý Người dùng';
        case 'sua_nguoidung': return 'Sửa Người dùng';
        default: return 'Bảng điều khiển';
    }
}
$current_act = $_GET['act'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_current_page_title($current_act); ?> - Trang Quản Trị</title>
    <link href="TaiNguyen/css/bootstrap.min.css" rel="stylesheet">
    <link href="TaiNguyen/css/style.admin.css" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- THANH ĐIỀU HƯỚNG BÊN (SIDEBAR) -->
        <aside class="admin-sidebar">
            <a href="admin.php" class="sidebar-brand">Shop Táo Ngon</a>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item <?php echo ($current_act == 'dashboard') ? 'active' : ''; ?>">
                    <a href="admin.php?act=dashboard">Bảng điều khiển</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_sanpham', 'them_sp', 'sua_sp'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_sanpham">Quản lý Sản phẩm</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_donhang', 'ct_donhang'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_donhang">Quản lý Đơn hàng</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_danhmuc', 'them_danhmuc', 'sua_danhmuc'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_danhmuc">Quản lý Danh mục</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_nguoidung', 'sua_nguoidung'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_nguoidung">Quản lý Người dùng</a>
                </li>
                <!-- Thêm các mục quản lý khác ở đây -->
            </ul>
        </aside>

        <!-- VÙNG NỘI DUNG CHÍNH -->
        <div class="admin-main-content">
            <header class="admin-header">
                <div class="user-profile">
                    <span>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user_fullname'] ?? 'Admin'); ?></strong></span>
                    <a href="index.php?act=dang_xuat" class="admin-logout-link" title="Đăng xuất">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                    </a>
                </div>
            </header>
            <div class="admin-page-content">
                <!-- Nội dung của từng trang sẽ được include vào đây -->