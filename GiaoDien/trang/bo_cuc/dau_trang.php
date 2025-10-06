<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng của tôi</title>
    <link href="TaiNguyen/css/bootstrap.min.css" rel="stylesheet">
    <link href="TaiNguyen/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="main-content-wrapper">
            <div class="header-content">
                <a href="index.php?act=trangchu" class="shop-title-link">
                    <h1 class="shop-title">Shop Táo Ngon</h1>
                </a>
                <!-- Thanh tìm kiếm ở giữa -->
                <div class="header-search-container">
                    <form action="index.php?act=tim_kiem" method="GET" class="header-search-form">
                        <input type="text" name="keyword" placeholder="Bạn tìm gì hôm nay?">
                        <button type="submit" aria-label="Tìm kiếm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <!-- Các icon chức năng bên phải -->
                <div class="header-actions">
                    <a href="index.php?act=gio_hang" title="Giỏ hàng" class="header-action-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                        </svg>
                        <span>Giỏ hàng</span>
                    </a>
                    <?php if (isset($_SESSION['user_fullname'])): ?>
                        <div class="header-action-item nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                </svg>
                                <span><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="index.php?act=lich_su_mua_hang">Lịch sử mua hàng</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?act=dang_xuat">Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="index.php?act=dang_nhap" title="Đăng nhập / Đăng ký" class="header-action-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                            <span>Đăng nhập</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <!-- Thanh menu danh mục -->
    <nav class="main-nav-bar">
        <div class="main-content-wrapper">
            <ul class="nav-list main-nav">
                <li class="nav-item dropdown dropdown-megamenu">
                <a class="nav-link dropdown-toggle" href="#" id="phoneDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Điện thoại</a>
                </li>
                <li><a href="index.php?act=danhmuc&id=2">Laptop</a></li>
                <li><a href="index.php?act=danhmuc&id=3">Đồng hồ</a></li>
                <li><a href="index.php?act=danhmuc&id=4">Máy cũ</a></li>
                <li><a href="index.php?act=danhmuc&id=5">Phụ kiện</a></li>
            </ul>
        </div>
    </nav>
    <main class="main py-4">
        <div class="main-content-wrapper">
