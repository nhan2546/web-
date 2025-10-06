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
        <div class="container">
            <div class="header-content">
                <h1 class="shop-title">Shop Táo Ngon</h1>
                <div class="header-nav">
                    <ul class="nav-list main-nav">
                        <li><a href="index.php?act=trangchu">Trang Chủ</a></li>
                        <li><a href="index.php?act=hienthi_sp">Sản Phẩm</a></li>
                        <?php if (isset($_SESSION['user_fullname'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Xin chào, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="index.php?act=lich_su_mua_hang">Lịch sử mua hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="index.php?act=dang_xuat">Đăng xuất</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li><a href="index.php?act=dang_nhap">Đăng nhập</a></li>
                            <li><a href="index.php?act=dang_ky">Đăng ký</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <main class="main py-4">
