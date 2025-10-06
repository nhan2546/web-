
<h2 class="text-center mt-m"> Chào Mừng Đến Shop!</h2>
<p class="text-center mt-4">Danh sách sản phẩm mới nhất sẽ hiển thị ở đây.</p>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <div class="container-fluid">
        <a class="navbar-brand" href="index.php?act=trangchu">Trang Chủ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?act=hienthi_sp">Sản Phẩm</a>
                </li>
            </ul>
            <ul class="navbar-nav">
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=dang_nhap">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=dang_ky">Đăng ký</a>
                    </li>
                    <div class="search-section">
  <form onsubmit="gotoSearch(event)">
    <input id="q" placeholder="Bạn cần tìm gì...">
    <button>Tìm</button>
  </form>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
