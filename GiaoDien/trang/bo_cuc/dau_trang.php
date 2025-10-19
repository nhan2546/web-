<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shop Táo Ngon</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="TaiNguyen/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>

<!-- Top bar (ship nhanh, hotline, store locator...) -->
<div class="cp-topbar">
  <div class="cp-topbar__marquee">
    <ul class="cp-topbar__items"> <!-- Khối nội dung gốc -->
        <li>🚚 Miễn phí giao nhanh</li>
        <li>🎧 Hotline: 1800 0000</li>
        <li>📍 Hệ thống cửa hàng</li>
        <li>🎁 Khuyến mãi</li>
    </ul>
    <ul class="cp-topbar__items" aria-hidden="true"> <!-- Khối nội dung nhân bản để tạo hiệu ứng lặp lại liền mạch -->
        <li>🚚 Miễn phí giao nhanh</li>
        <li>🎧 Hotline: 1800 0000</li>
        <li>📍 Hệ thống cửa hàng</li>
        <li>🎁 Khuyến mãi</li>
    </ul>
  </div>
</div>

<!-- Header -->
<header class="cp-header">
  <div class="cp-container cp-header__row">
    <a class="cp-logo" href="index.php?act=trangchu">
      <img src="TaiNguyen/hinh_anh/ChatGPT_Image_Oct_15__2025__05_00_01_PM-removebg-preview.png" alt="Shop Táo Ngon">
    </a>

    <form class="cp-search" action="index.php" method="get">
      <input type="hidden" name="act" value="tim_kiem">
      <input name="keyword" id="search-input" placeholder="Bạn tìm gì hôm nay?" autocomplete="off" />
      <button aria-label="Tìm kiếm">🔍</button>
      <!-- Khung chứa kết quả tìm kiếm sẽ được JS chèn vào đây -->
      <div id="search-results-container"></div>
    </form>

    <nav class="cp-quick">
        <a href="index.php?act=gio_hang" class="cp-cart-link">
            🛒 Giỏ hàng
            <span class="cart-badge" id="cart-badge">
                <?php
                    // Tính tổng số lượng sản phẩm trong giỏ hàng
                    echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                ?>
            </span>
        </a>
        <?php if (isset($_SESSION['user_id'])): 
            // --- LOGIC TÍNH HẠNG THÀNH VIÊN (được chuyển lên header) ---
            if (!function_exists('getHeaderCustomerRank')) {
                function getHeaderCustomerRank($spending) {
                    $ranks = [
                        'Đồng' => ['threshold' => 0, 'class' => 'rank-copper', 'next_rank' => 'Bạc'],
                        'Bạc' => ['threshold' => 5000000, 'class' => 'rank-silver', 'next_rank' => 'Vàng'],
                        'Vàng' => ['threshold' => 15000000, 'class' => 'rank-gold', 'next_rank' => 'Kim Cương'],
                        'Kim Cương' => ['threshold' => 30000000, 'class' => 'rank-diamond', 'next_rank' => null],
                    ];
                    $current_rank_name = 'Đồng';
                    foreach (array_reverse($ranks, true) as $rank_name => $details) {
                        if ($spending >= $details['threshold']) {
                            $current_rank_name = $rank_name;
                            break;
                        }
                    }
                    $current_rank_details = $ranks[$current_rank_name];
                    $next_rank_name = $current_rank_details['next_rank'];
                    $needed_for_next = 0;
                    $progress_percentage = 100;
                    if ($next_rank_name) {
                        $next_rank_threshold = $ranks[$next_rank_name]['threshold'];
                        $needed_for_next = $next_rank_threshold - $spending;
                        $progress_percentage = ($spending / $next_rank_threshold) * 100;
                    }
                    return [
                        'rank' => $current_rank_name, 'class' => $current_rank_details['class'],
                        'next_rank' => $next_rank_name, 'needed_for_next' => $needed_for_next,
                        'progress_percentage' => min($progress_percentage, 100)
                    ];
                }
            }
            $userModelForHeader = new NguoiDung($pdo);
            $totalSpendingForHeader = $userModelForHeader->getTotalSpendingByUserId($_SESSION['user_id']);
            $rankInfoForHeader = getHeaderCustomerRank($totalSpendingForHeader);
        ?>
            <div class="cp-user-menu">
                <a href="index.php?act=thong_tin_tai_khoan" class="cp-user-menu__trigger">👤 <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></a>
                <div class="cp-user-menu__dropdown">
                    <div class="user-rank-summary">
                        <div class="rank-header">
                            <span class="customer-rank-badge <?= $rankInfoForHeader['class'] ?>"><?= $rankInfoForHeader['rank'] ?></span>
                        </div>
                        <div class="rank-progress-bar">
                            <div class="rank-progress-bar-fill" style="width: <?= $rankInfoForHeader['progress_percentage'] ?>%;"></div>
                        </div>
                        <div class="rank-progress-info">
                            <?php if ($rankInfoForHeader['next_rank']): ?>
                                <p>Còn <strong><?= number_format($rankInfoForHeader['needed_for_next'], 0, ',', '.') ?>₫</strong> để lên hạng <strong><?= $rankInfoForHeader['next_rank'] ?></strong></p>
                            <?php else: ?>
                                <p>🎉 Đã đạt hạng cao nhất</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="index.php?act=thong_tin_tai_khoan" class="dropdown-item">Tài khoản của tôi</a>
                    <a href="index.php?act=lich_su_mua_hang" class="dropdown-item">Lịch sử mua hàng</a>
                    <?php
                        // Chỉ hiển thị link này nếu người dùng là admin hoặc nhân viên
                        if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'staff'])): ?>
                        <a href="admin.php" class="dropdown-item dropdown-item--admin">Trang quản trị</a>
                    <?php endif; ?>
                    <a href="index.php?act=dang_xuat" class="dropdown-item dropdown-item--logout">Đăng xuất</a>
                </div>
            </div>
        <?php else: ?>
            <a href="index.php?act=dang_nhap">👤 Đăng nhập</a>
        <?php endif; ?>
    </nav>

    <button class="cp-burger" aria-label="Menu">☰</button>
  </div>

  <!-- Nav chính -->
  <div class="cp-nav">
    <div class="cp-container">
      <ul class="cp-nav__list">
        <li class="has-mega">
          <a href="index.php?act=danhmuc&id=1">Điện thoại</a>
          <div class="cp-mega">
            <div>
              <h4>iPhone</h4>
              <a href="index.php?act=tim_kiem&keyword=iPhone 16">iPhone 16 Series</a>
              <a href="index.php?act=tim_kiem&keyword=iPhone 15">iPhone 15 Series</a>
              <a href="index.php?act=tim_kiem&keyword=iPhone 14">iPhone 14 Series</a>
            </div>
            <div>
              <h4>Hãng khác</h4>
              <a href="index.php?act=tim_kiem&keyword=Samsung">Samsung</a>
              <a href="index.php?act=tim_kiem&keyword=Xiaomi">Xiaomi</a>
              <a href="index.php?act=tim_kiem&keyword=OPPO">OPPO</a>
            </div>
            <div>
              <h4>Khoảng giá</h4>
              <a href="index.php?act=tim_kiem&price_range=0-10000000">Dưới 10 triệu</a>
              <a href="index.php?act=tim_kiem&price_range=10000000-20000000">10–20 triệu</a>
              <a href="index.php?act=tim_kiem&price_range=20000001-999999999">Trên 20 triệu</a>
            </div>
          </div>
        </li>
        <li class="has-mega">
          <a href="index.php?act=danhmuc&id=2">Laptop</a>
          <div class="cp-mega">
            <div>
              <h4>Thương hiệu</h4>
              <a href="index.php?act=tim_kiem&keyword=MacBook">MacBook</a>
              <a href="index.php?act=tim_kiem&keyword=Dell">Dell</a>
              <a href="index.php?act=tim_kiem&keyword=HP">HP</a>
              <a href="index.php?act=tim_kiem&keyword=Asus">Asus</a>
            </div>
            <div>
              <h4>Nhu cầu</h4>
              <a href="#">Văn phòng</a>
              <a href="#">Đồ họa - Kỹ thuật</a>
              <a href="#">Gaming</a>
            </div>
            <div>
              <h4>Khoảng giá</h4>
              <a href="#">Dưới 20 triệu</a>
              <a href="#">20 - 30 triệu</a>
              <a href="#">Trên 30 triệu</a>
            </div>
          </div>
        </li>
        <li class="has-mega">
          <a href="index.php?act=danhmuc&id=3">Tablet</a>
           <div class="cp-mega">
            <div>
              <h4>Thương hiệu</h4>
              <a href="index.php?act=tim_kiem&keyword=iPad">iPad</a>
              <a href="index.php?act=tim_kiem&keyword=Samsung Tab">Samsung Galaxy Tab</a>
              <a href="index.php?act=tim_kiem&keyword=Xiaomi Pad">Xiaomi Pad</a>
            </div>
            <div>
              <h4>Tính năng</h4>
              <a href="#">Hỗ trợ bút</a>
              <a href="#">Có 5G</a>
              <a href="#">Màn hình lớn</a>
            </div>
          </div>
        </li>
        <li class="has-mega">
          <a href="index.php?act=danhmuc&id=4">Âm thanh</a>
          <div class="cp-mega">
            <div>
              <h4>Tai nghe</h4>
              <a href="#">AirPods</a>
              <a href="#">Sony</a>
              <a href="#">JBL</a>
            </div>
            <div>
              <h4>Loa</h4>
              <a href="#">Marshall</a>
              <a href="#">Harman Kardon</a>
              <a href="#">Bose</a>
            </div>
          </div>
        </li>
        <li class="has-mega">
          <a href="index.php?act=danhmuc&id=5">Phụ kiện</a>
          <div class="cp-mega">
            <div>
              <h4>Phụ kiện di động</h4>
              <a href="#">Sạc, cáp</a>
              <a href="#">Sạc dự phòng</a>
              <a href="#">Ốp lưng</a>
              <a href="#">Dán màn hình</a>
            </div>
            <div>
              <h4>Phụ kiện Laptop</h4>
              <a href="#">Chuột, bàn phím</a>
              <a href="#">Túi chống sốc</a>
              <a href="#">Giá đỡ</a>
            </div>
          </div>
        </li>
        <li><a href="index.php?act=thu_cu_doi_moi">Thu cũ đổi mới</a></li>
      </ul>
    </div>
  </div>
</header>

<!-- Bắt đầu vùng nội dung chính của trang -->
<main class="cp-container cp-section">
