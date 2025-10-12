<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <!-- Link đến Bootstrap CSS (ví dụ) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link đến file CSS tùy chỉnh cho trang admin -->
    <link rel="stylesheet" href="../GiaoDien/QuanTri/css/admin_style.css">
</head>
<body>

<div class="admin-layout">
    <!-- Sidebar (Menu bên trái) -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <a href="admin.php">Shop Táo Ngon</a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="admin.php?act=dashboard">Bảng điều khiển</a></li>
                <li><a href="admin.php?act=ds_sanpham">Quản lý Sản phẩm</a></li>
                <li><a href="admin.php?act=ds_danhmuc">Quản lý Danh mục</a></li>
                <li><a href="admin.php?act=ds_donhang">Quản lý Đơn hàng</a></li>
                <li><a href="admin.php?act=ds_nguoidung">Quản lý Người dùng</a></li>
                <li><a href="index.php?act=trangchu" target="_blank">Xem trang web</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Nội dung chính -->
    <div class="admin-main">
        <header class="admin-header">
            <div class="header-left">
                <!-- Có thể thêm nút toggle sidebar ở đây -->
            </div>
            <div class="header-right">
                <span>Xin chào, <?= htmlspecialchars($_SESSION['user_fullname'] ?? 'Admin') ?></span>
                <a href="index.php?act=dang_xuat">Đăng xuất</a>
            </div>
        </header>

        <main class="admin-content">
            <div class="container-fluid">