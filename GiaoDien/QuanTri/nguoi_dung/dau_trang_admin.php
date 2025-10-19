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
            <!-- Thay đổi: Thay thế văn bản bằng logo hình ảnh -->
            <a href="index.php?act=trangchu" class="sidebar-brand" target="_blank" title="Xem trang chủ">
                <img src="TaiNguyen/hinh_anh/ChatGPT_Image_Oct_15__2025__05_00_01_PM-removebg-preview.png" alt="Shop Táo Ngon">
            </a>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item <?php echo ($current_act == 'dashboard') ? 'active' : ''; ?>">
                    <a href="admin.php?act=dashboard"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-grid-1x2-fill" viewBox="0 0 16 16"><path d="M0 1a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V1zm9 0a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1V1zm0 9a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1v-5z"/></svg>Tổng Doanh Thu</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_sanpham', 'them_sp', 'sua_sp'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_sanpham"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-seam-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.723.023a.75.75 0 0 1 .554 0l7.25 2.95zM-1.25 8.567l-1.5-3-1.5 3L-3 10l1.5 3 1.5-3L-1.25 8.567zM8 4.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 1 0v-3a.5.5 0 0 0-.5-.5z"/></svg>Quản lý Sản phẩm</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_donhang', 'ct_donhang'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_donhang"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16"><path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 1.988v12.024l.146.147.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.646.647.646-.647.-646.647-.646-.647-.646.647-.646-.647-.646.647-.646-.647-.646.647-.646-.647-.646.647-.646-.647-.646.647-.646-.647-.646.647-.646-.647-.646.647-.646-.647-.646.647-.646-.647-.646.6-4.695-4.695a.5.5 0 0 1 0-.708z"/></svg>Quản lý Đơn hàng</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_nhanvien', 'sua_nhanvien'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_nhanvien"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>Quản lý Nhân viên</a>
                </li>
                <li class="sidebar-nav-item <?php echo ($current_act == 'ds_khachhang') ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_khachhang"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16"><path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm-2 3a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4z"/></svg>Quản lý Khách Hàng</a>
                </li>
                <li class="sidebar-nav-item <?php echo (in_array($current_act, ['ds_voucher', 'them_voucher', 'sua_voucher'])) ? 'active' : ''; ?>">
                    <a href="admin.php?act=ds_voucher"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-ticket-detailed-fill" viewBox="0 0 16 16"><path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zM4 5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5m0 2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5m0 2a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5"/></svg>Quản lý Voucher</a>
                </li>
                <!-- Thêm mục Xem trang người dùng -->
                <li class="sidebar-nav-item" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                    <a href="index.php" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/><path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/></svg>
                        Trang Chủ
                    </a>
                </li>
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
            <main class="admin-page-content">
                <!-- Nội dung của từng trang sẽ được include vào đây -->