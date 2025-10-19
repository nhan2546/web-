<!-- Hero/banner -->
<section class="cp-hero">
  <div class="cp-container">
    <!-- Slider main container -->
    <div class="swiper hero-slider">
      <!-- Additional required wrapper -->
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
          <a href="index.php?act=hienthi_sp"><img src="TaiNguyen/hinh_anh/banner/banner1.png" alt="Banner 1"></a>
        </div>
        <div class="swiper-slide">
          <a href="index.php?act=hienthi_sp"><img src="TaiNguyen/hinh_anh/banner/banner2.png" alt="Banner 2"></a>
        </div>
        <div class="swiper-slide">
          <a href="index.php?act=hienthi_sp"><img src="TaiNguyen/hinh_anh/banner/banner3.png" alt="Banner 3"></a>
        </div>
      </div>
      <!-- If we need pagination -->
      <div class="swiper-pagination"></div> 
 
      <!-- Navigation buttons should be inside the main slider container -->
      <div class="swiper-button-prev"></div> 
      <div class="swiper-button-next"></div> 
    </div>
  </div>
</section>

<!-- Flash sale / Deal -->
<section class="cp-deal">
  <div class="cp-container cp-section">
    <div class="cp-section__head">
      <h3>Hot Sale Cuối Tuần</h3>
      <?php
        // Đặt múi giờ Việt Nam để đảm bảo thời gian chính xác
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        // Tự động đặt thời gian kết thúc là cuối ngày hôm nay
        $end_of_day = date('Y-m-d') . 'T23:59:59';
      ?>
      <div class="cp-countdown" data-end="<?= $end_of_day ?>">00:00:00</div>
    </div>
    <div class="cp-grid">
      <?php if (!empty($danh_sach_hot_sale)): ?>
        <?php foreach ($danh_sach_hot_sale as $sp_sale): ?>
          <article class="cp-card">
            <div class="cp-card__image-container">
                <a href="index.php?act=chi_tiet_san_pham&id=<?= (int)$sp_sale['id'] ?>">
                    <img src="TaiLen/san_pham/<?= htmlspecialchars($sp_sale['image_url']) ?>" alt="<?= htmlspecialchars($sp_sale['name']) ?>">
                </a>
                <span class="cp-badge">Giảm <?= round($sp_sale['discount_percentage']) ?>%</span>
            </div>
            <div class="cp-card__content">
                <a href="index.php?act=chi_tiet_san_pham&id=<?= (int)$sp_sale['id'] ?>">
                    <h4><?= htmlspecialchars($sp_sale['name']) ?></h4>
                    <div class="cp-price">
                        <?php if (isset($sp_sale['sale_price']) && (float)$sp_sale['sale_price'] > 0): ?>
                            <span class="now"><?= number_format($sp_sale['sale_price'], 0, ',', '.') ?>₫</span>
                            <del><?= number_format($sp_sale['price'], 0, ',', '.') ?>₫</del>
                        <?php else: ?>
                            <span class="now"><?= number_format($sp_sale['price'], 0, ',', '.') ?>₫</span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Hiện chưa có sản phẩm nào đang giảm giá.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Sản phẩm mới (loop PHP của bạn vào đây) -->
<div class="cp-section__head">
    <h3>Sản phẩm mới nhất</h3>
    <a class="cp-link" href="index.php?act=hienthi_sp">Xem tất cả →</a>
</div>

<div class="cp-grid">
    <?php if (!empty($danh_sach_san_pham)): ?>
      <?php foreach ($danh_sach_san_pham as $sp): ?>
        <article class="cp-card">
            <div class="cp-card__image-container">
                <a href="index.php?act=chi_tiet_san_pham&id=<?= (int)$sp['id'] ?>">
                    <img src="TaiLen/san_pham/<?= htmlspecialchars($sp['image_url']) ?>" alt="<?= htmlspecialchars($sp['name']) ?>">
                </a>
            </div>
            <div class="cp-card__content">
                <a href="index.php?act=chi_tiet_san_pham&id=<?= (int)$sp['id'] ?>">
                    <h4><?= htmlspecialchars($sp['name']) ?></h4>
                    <div class="cp-price">
                        <span class="now"><?= number_format($sp['price'],0,',','.') ?>₫</span>
                    </div>
                </a>
                <?php if (($sp['quantity'] ?? 0) > 0): ?>
                    <!-- Form được tách ra khỏi thẻ <a> để đảm bảo HTML hợp lệ -->
                    <form action="index.php?act=them_vao_gio" method="POST" class="mt-auto">
                        <input type="hidden" name="id" value="<?= (int)$sp['id'] ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($sp['name']) ?>">
                        <input type="hidden" name="image_url" value="<?= htmlspecialchars($sp['image_url']) ?>">
                        <input type="hidden" name="price" value="<?= htmlspecialchars($sp['price']) ?>">
                        <?php if (isset($sp['variant_color'])): ?>
                            <input type="hidden" name="variant_color" value="<?= $sp['variant_color'] ?>">
                        <?php endif; ?>
                        <button type="submit" class="cp-btn">Thêm vào giỏ</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="cp-btn" disabled>Hết hàng</button>
                <?php endif; ?>
            </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Chưa có sản phẩm nào.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- JAVASCRIPT CHO GIỎ HÀNG AJAX ---
    const cartBadge = document.getElementById('cart-badge');

    const updateBadgeVisibility = () => {
        if (cartBadge) {
            const count = parseInt(cartBadge.textContent, 10);
            cartBadge.style.transform = (count > 0) ? 'scale(1)' : 'scale(0)';
        }
    };

    document.querySelectorAll('form[action="index.php?act=them_vao_gio"]').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');

            fetch(actionUrl, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success && cartBadge) {
                    cartBadge.textContent = data.new_total_quantity;
                    updateBadgeVisibility();
                    cartBadge.style.transform = 'scale(1.3)';
                    setTimeout(() => { cartBadge.style.transform = 'scale(1)'; }, 200);
                }
            })
            .catch(error => console.error('Lỗi khi thêm vào giỏ hàng:', error));
        });
    });

    updateBadgeVisibility();

    // --- JAVASCRIPT CHO SLIDER ---
    const heroSlider = document.querySelector(".hero-slider");
    if (heroSlider) {
        new Swiper(heroSlider, {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".hero-slider .swiper-button-next",
                prevEl: ".hero-slider .swiper-button-prev",
            },
        });
    }

    // --- JAVASCRIPT CHO COUNTDOWN ---
    const countdownEl = document.querySelector('.cp-countdown');
    // ... (Thêm logic countdown nếu cần)

});
</script>
