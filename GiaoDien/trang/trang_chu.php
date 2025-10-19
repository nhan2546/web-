<!-- Hero/banner -->
<section class="cp-hero">
  <div class="cp-container">
    <div class="hero-layout">
      <!-- Cột trái: Slider chính -->
      <div class="swiper hero-slider">
        <div class="swiper-wrapper">
          <!-- Slides -->
          <div class="swiper-slide">
            <a href="index.php?act=hienthi_sp"><img src="TaiNguyen/hinh_anh/690x300_iPhone_17_Pro_Opensale_v3.webp" alt="Banner 1"></a>
          </div>
          <div class="swiper-slide">
            <a href="index.php?act=hienthi_sp"><img src="TaiNguyen/hinh_anh/16-pro-max-cate-20-10.webp" alt="Banner 2"></a>
          </div>
          <div class="swiper-slide">
            <a href="index.php?act=hienthi_sp"><img src="TaiNguyen/hinh_anh/xiaomi-15t-pro-5g-home.webp" alt="Banner 3"></a>
          </div>
          <div class="swiper-slide">
            <a href="index.php?act=hienthi_sp"><img src="TaiNguyen/hinh_anh/Z7-Sliding-1025.webp" alt="Banner 4"></a>
          </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>

      <!-- Cột phải: 2 banner phụ -->
      <div class="hero-side-banners">
        <a href="#" class="side-banner">
          <img src="TaiNguyen/hinh_anh/Z7-Sliding-1025.webp" alt="Side Banner 1">
        </a>
        <a href="#" class="side-banner">
          <img src="TaiNguyen/hinh_anh/tecno-spark-40-pro-plus-home-2010.webp" alt="Side Banner 2">
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Flash sale / Deal -->
<section class="cp-section">
  <div class="cp-container cp-deal">
    <div class="deal-showcase">
      <div class="deal-showcase__header">
        <div class="deal-title">
          <h3><i class="fas fa-fire-alt"></i> Hot Sale Cuối Tuần</h3>
        </div>
        <div class="deal-countdown-wrapper">
          <span>Kết thúc sau</span>
          <?php
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $end_of_day = date('Y-m-d') . 'T23:59:59';
          ?>
          <div class="cp-countdown" data-end="<?= $end_of_day ?>">
            <span class="time-box" id="countdown-hours">00</span>
            <span class="time-box" id="countdown-minutes">00</span>
            <span class="time-box" id="countdown-seconds">00</span>
          </div>
        </div>
      </div>
      <div class="deal-showcase__body">
        <ul class="deal-tabs">
          <li class="deal-tab-item active" data-tab="1">Điện thoại</li>
          <li class="deal-tab-item" data-tab="2">Laptop</li>
          <li class="deal-tab-item" data-tab="3">Phụ kiện</li>
        </ul>
        <div class="deal-tab-content active" id="deal-tab-1">
          <div class="swiper deal-slider">
            <div class="swiper-wrapper">
              <?php if (!empty($danh_sach_hot_sale)): ?>
                <?php foreach ($danh_sach_hot_sale as $sp_sale): ?>
                  <div class="swiper-slide">
                    <article class="cp-card">
                      <a href="index.php?act=chi_tiet_san_pham&id=<?= (int)$sp_sale['id'] ?>">
                        <div class="cp-card__image-container">
                          <img src="TaiLen/san_pham/<?= htmlspecialchars($sp_sale['image_url']) ?>" alt="<?= htmlspecialchars($sp_sale['name']) ?>">
                          <span class="cp-discount-badge">-<?= round($sp_sale['discount_percentage']) ?>%</span>
                        </div>
                        <div class="cp-card__content">
                          <h4><?= htmlspecialchars($sp_sale['name']) ?></h4>
                          <div class="cp-price">
                            <span class="now"><?= number_format($sp_sale['sale_price'], 0, ',', '.') ?>₫</span>
                            <del><?= number_format($sp_sale['price'], 0, ',', '.') ?>₫</del>
                          </div>
                        </div>
                      </a>
                    </article>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="swiper-button-next deal-button-next"></div>
          <div class="swiper-button-prev deal-button-prev"></div>
        </div>
        <!-- Các tab content khác (2, 3) có thể được thêm vào đây với logic tương tự -->
      </div>
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
    // --- HÀM TẠO HIỆU ỨNG "FLY TO CART" ---
    function flyToCart(sourceElement) {
        const cartIcon = document.querySelector('.cp-cart-link');
        if (!sourceElement || !cartIcon) return;

        // 1. Tạo một bản sao của ảnh để "bay"
        const flyingElement = sourceElement.cloneNode(true);
        flyingElement.classList.add('fly-to-cart-element');
        document.body.appendChild(flyingElement);

        // 2. Lấy vị trí bắt đầu (từ ảnh sản phẩm)
        const startRect = sourceElement.getBoundingClientRect();
        const cartRect = cartIcon.getBoundingClientRect();

        // 3. Thiết lập style ban đầu cho phần tử bay
        flyingElement.style.left = `${startRect.left}px`;
        flyingElement.style.top = `${startRect.top}px`;
        flyingElement.style.width = `${startRect.width}px`;
        flyingElement.style.height = `${startRect.height}px`;

        // 4. Bắt đầu animation sau một khoảng trễ nhỏ để trình duyệt kịp render
        requestAnimationFrame(() => {
            // Vị trí kết thúc (tại giỏ hàng)
            const endLeft = cartRect.left + (cartRect.width / 2);
            const endTop = cartRect.top + (cartRect.height / 2);

            flyingElement.style.left = `${endLeft}px`;
            flyingElement.style.top = `${endTop}px`;
            flyingElement.style.width = '30px'; // Thu nhỏ lại
            flyingElement.style.height = '30px';
            flyingElement.style.opacity = '0.5';
            flyingElement.style.transform = 'scale(0.2)';
        });

        // 5. Xóa phần tử bay sau khi animation kết thúc (1 giây)
        setTimeout(() => flyingElement.remove(), 1000);
    }
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
            
            // Tìm ảnh sản phẩm tương ứng với form được nhấn
            const productCard = this.closest('.cp-card');
            const productImage = productCard ? productCard.querySelector('.cp-card__image-container img') : null;
            
            // Chạy hiệu ứng
            if (productImage) {
                flyToCart(productImage);
            }

            fetch(actionUrl, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success && cartBadge) {
                    setTimeout(() => {
                        cartBadge.textContent = data.new_total_quantity;
                    }, 800);
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

    // --- JAVASCRIPT CHO DEAL SLIDER ---
    const dealSlider = document.querySelector(".deal-slider");
    if (dealSlider) {
        new Swiper(dealSlider, {
            slidesPerView: 2,
            spaceBetween: 15,
            navigation: {
                nextEl: ".deal-button-next",
                prevEl: ".deal-button-prev",
            },
            breakpoints: {
                640: { slidesPerView: 3 },
                992: { slidesPerView: 4 },
                1200: { slidesPerView: 5 },
            },
        });
    }

    // --- JAVASCRIPT CHO COUNTDOWN ---
    const countdownEl = document.querySelector('.cp-countdown');
    if (countdownEl) {
        const endTime = new Date(countdownEl.dataset.end).getTime();
        const hoursEl = document.getElementById('countdown-hours');
        const minutesEl = document.getElementById('countdown-minutes');
        const secondsEl = document.getElementById('countdown-seconds');

        const updateCountdown = () => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                countdownEl.innerHTML = "Đã kết thúc";
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if(hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
            if(minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
            if(secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
        };

        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown(); // Gọi lần đầu để không bị trễ 1 giây
    }

    // --- JAVASCRIPT CHO HOT SALE TABS & SLIDER ---
    const dealTabs = document.querySelectorAll('.deal-tab-item');
    const dealTabContents = document.querySelectorAll('.deal-tab-content');
    let dealSwipers = {}; // Lưu trữ các instance của Swiper

    function initializeSwiper(tabId) {
        const sliderEl = document.querySelector(`#deal-tab-${tabId} .deal-slider`);
        if (sliderEl && !dealSwipers[tabId]) { // Chỉ khởi tạo nếu chưa có
            dealSwipers[tabId] = new Swiper(sliderEl, {
                slidesPerView: 2,
                spaceBetween: 15,
                navigation: {
                    nextEl: `#deal-tab-${tabId} .deal-button-next`,
                    prevEl: `#deal-tab-${tabId} .deal-button-prev`,
                },
                breakpoints: {
                    640: { slidesPerView: 3 },
                    992: { slidesPerView: 4 },
                    1200: { slidesPerView: 5 },
                },
            });
        }
    }

    dealTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            dealTabs.forEach(t => t.classList.remove('active'));
            dealTabContents.forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(`deal-tab-${tab.dataset.tab}`).classList.add('active');
            initializeSwiper(tab.dataset.tab); // Khởi tạo swiper cho tab được chọn
        });
    });

    // Khởi tạo Swiper cho tab active mặc định khi tải trang
    initializeSwiper(1);
});
</script>
