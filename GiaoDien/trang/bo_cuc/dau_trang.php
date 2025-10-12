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
                <a href="index.php?act=trangchu" class="shop-title-link">
                    <img src="TaiNguyen/hinh_anh/ChatGPT Image Oct 12, 2025, 01_19_04 PM.png" alt="Shop Táo Ngon Logo" class="shop-logo">
                    <h1 class="shop-title">Shop Táo Ngon</h1>
                </a>
                <!-- Thanh tìm kiếm ở giữa -->
                <div class="header-search-container">
                    <form action="index.php" method="GET" class="header-search-form" autocomplete="off">
                        <input type="hidden" name="act" value="tim_kiem">
                        <input type="text" name="keyword" id="search-input" placeholder="Bạn tìm gì hôm nay?">
                        <button type="submit" aria-label="Tìm kiếm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </form>
                    <!-- Khung chứa kết quả tìm kiếm sẽ được JS chèn vào đây -->
                    <div id="search-results-container"></div>
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
                        <!-- Tên tài khoản -->
                        <a href="index.php?act=thong_tin_tai_khoan" title="Tài khoản của bạn" class="header-action-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                            <span><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></span>
                        </a>
                        <!-- Lịch sử mua hàng -->
                        <a href="index.php?act=lich_su_mua_hang" title="Lịch sử mua hàng" class="header-action-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                            </svg>
                            <span>Lịch sử mua hàng </span>
                        </a>
                        <!-- Đăng xuất -->
                        <a href="index.php?act=dang_xuat" title="Đăng xuất" class="header-action-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                            <span>Đăng xuất</span>
                        </a>
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
        <div class="container">
            <ul class="nav-list main-nav">
                <!-- Ví dụ về Mega Menu cho mục Điện thoại -->
                <li class="nav-item has-megamenu">
                    <a class="nav-link" href="index.php?act=danhmuc&id=1">Điện thoại</a>
                    <div class="dropdown-menu megamenu" role="menu">
                        <div class="row">
                            <div class="col-auto">
                                <h6 class="title">iPhone</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">iPhone 15 Series</a></li>
                                    <li><a href="#">iPhone 14 Series</a></li>
                                    <li><a href="#">iPhone 13 Series</a></li>
                                    <li><a href="#">iPhone 12 Series</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Samsung</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Galaxy S Series</a></li>
                                    <li><a href="#">Galaxy ZPhone</a></li>
                                    <li><a href="#">Galaxy A Series</a></li>
                                    <li><a href="#">Galaxy M Series</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Chọn theo giá</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Dưới 2 triệu</a></li>
                                    <li><a href="#">Từ 2 - 4 triệu</a></li>
                                    <li><a href="#">Từ 4 - 7 triệu</a></li>
                                    <li><a href="#">Từ 7 - 13 triệu</a></li>
                                    <li><a href="#">Trên 13 triệu</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Nhu cầu sử dụng</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Chơi game / Cấu hình cao</a></li>
                                    <li><a href="#">Chụp ảnh đẹp</a></li>
                                    <li><a href="#">Pin trâu</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item has-megamenu">
                    <a class="nav-link" href="index.php?act=danhmuc&id=2">Laptop</a>
                    <div class="dropdown-menu megamenu" role="menu">
                        <div class="row">
                            <div class="col-auto">
                                <h6 class="title">MacBook</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">MacBook Air</a></li>
                                    <li><a href="#">MacBook Pro</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Thương hiệu khác</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Dell</a></li>
                                    <li><a href="#">Lenovo</a></li>
                                    <li><a href="#">HP</a></li>
                                    <li><a href="#">Asus</a></li>
                                    <li><a href="#">MSI</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Chọn theo giá</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Dưới 20 triệu</a></li>
                                    <li><a href="#">Từ 20 - 30 triệu</a></li>
                                    <li><a href="#">Trên 30 triệu</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Nhu cầu sử dụng</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Văn phòng</a></li>
                                    <li><a href="#">Đồ họa - Kỹ thuật</a></li>
                                    <li><a href="#">Mỏng nhẹ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item has-megamenu">
                    <a class="nav-link" href="index.php?act=danhmuc&id=3">Đồng hồ Thông Minh</a>
                    <div class="dropdown-menu megamenu" role="menu">
                        <div class="row">
                            <div class="col-auto">
                                <h6 class="title">Apple Watch</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Apple Watch SE</a></li>
                                    <li><a href="#">Apple Watch Series 9</a></li>
                                    <li><a href="#">Apple Watch Ultra 2</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Thương hiệu khác</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Samsung</a></li>
                                    <li><a href="#">Garmin</a></li>
                                    <li><a href="#">Xiaomi</a></li>
                                    <li><a href="#">Amazfit</a></li>
                                    <li><a href="#">Huawei</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Tính năng nổi bật</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Chống nước</a></li>
                                    <li><a href="#">Nghe gọi trên đồng hồ</a></li>
                                    <li><a href="#">Đo nồng độ Oxy (SpO2)</a></li>
                                    <li><a href="#">GPS</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Chọn theo giá</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Dưới 1 triệu</a></li>
                                    <li><a href="#">Từ 1 - 3 triệu</a></li>
                                    <li><a href="#">Trên 3 triệu</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item has-megamenu">
                    <a class="nav-link" href="index.php?act=danhmuc&id=4">Phụ kiện</a>
                    <div class="dropdown-menu megamenu" role="menu">
                        <div class="row">
                            <div class="col-auto">
                                <h6 class="title">Phụ kiện Apple</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Sạc, Cáp</a></li>
                                    <li><a href="#">AirPods</a></li>
                                    <li><a href="#">Ốp lưng</a></li>
                                    <li><a href="#">Ốp lưng iPhone</a></li>
                                    <li><a href="#">Dán màn hình</a></li>
                                    <li><a href="#">AirTag</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Loa, Tai nghe</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Tai nghe Bluetooth</a></li>
                                    <li><a href="#">Tai nghe có dây</a></li>
                                    <li><a href="#">Loa Bluetooth</a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <h6 class="title">Phụ kiện Laptop</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Chuột, Bàn phím</a></li>
                                    <li><a href="#">Balo, Túi chống sốc</a></li>
                                    <li><a href="#">Phần mềm</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li><a class="nav-link" href="index.php?act=danhmuc&id=5">Thu Máy cũ</a></li>
            </ul>
        </div>
    </nav>
    <main class="main py-4">
        <div class="container">
