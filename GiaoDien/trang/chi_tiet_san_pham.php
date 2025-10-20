<?php
if (!$san_pham) {
    echo "<div class='alert alert-danger text-center'>Sản phẩm không tồn tại hoặc đã bị xóa.</div>";
    return;
}

// --- Chuẩn bị dữ liệu ---
$is_on_sale = isset($san_pham['sale_price']) && $san_pham['sale_price'] > 0 && $san_pham['sale_price'] < $san_pham['price'];
$display_price = $is_on_sale ? $san_pham['sale_price'] : $san_pham['price'];
$original_price = $is_on_sale ? $san_pham['price'] : null;
$saving_percentage = $is_on_sale ? round((($san_pham['price'] - $san_pham['sale_price']) / $san_pham['price']) * 100) : 0;
$is_in_stock = ($san_pham['quantity'] ?? 0) > 0;
?>

<div class="cp-container product-detail-page">
    <!-- Breadcrumb -->
    <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
        <nav aria-label="breadcrumb" class="cp-breadcrumb">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <?php if ($index < count($breadcrumbs) - 1): ?>
                        <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= htmlspecialchars($crumb['title']) ?></a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($crumb['title']) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
    <?php endif; ?>
    <!-- {{product_name}} & {{rating_summary}} -->
    <h1 class="product-title-main"><?= htmlspecialchars($san_pham['name']) ?></h1>
    <div class="product-rating-summary mb-3">
        <span class="rating-value fw-bold text-warning"><?= $rating_info['average_rating'] ?></span>
        <div class="stars-outer d-inline-block mx-1">
            <div class="stars-inner" style="width: <?= ($rating_info['average_rating'] / 5) * 100 ?>%;"></div>
        </div>
        <a href="#reviews" class="review-count text-decoration-none">(<?= $rating_info['review_count'] ?> đánh giá)</a>
        <span class="mx-2">|</span>
        <span class="stock-status">
            Tình trạng:
            <?php if ($is_in_stock): ?>
                <span class="text-success fw-bold">Còn hàng</span>
            <?php else: ?>
                <span class="text-danger fw-bold">Hết hàng</span>
            <?php endif; ?>
        </span>
    </div>

    <hr>

    <!-- Bố cục 2 cột chính của trang -->
    <div class="product-detail-layout">
        <!-- Cột trái: Hình ảnh, Mô tả -->
        <div class="product-main-content">
            <!-- BỘ SƯU TẬP ẢNH -->
            <div class="product-gallery-container mb-4">
                <div class="main-image-wrapper">
                    <img src="TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="Ảnh sản phẩm <?= htmlspecialchars($san_pham['name']) ?>" class="product-main-image" id="main-product-image">
                </div>
                <div class="thumbnail-wrapper">
                    <?php
                        $gallery_images = !empty($san_pham['gallery_images_json']) ? json_decode($san_pham['gallery_images_json'], true) : [];
                        // Gộp ảnh đại diện vào đầu danh sách thumbnail
                        array_unshift($gallery_images, $san_pham['image_url']);
                        $gallery_images = array_unique($gallery_images); // Loại bỏ ảnh trùng lặp
                    ?>
                    <?php foreach ($gallery_images as $index => $img): ?>
                        <div class="thumbnail-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="TaiLen/san_pham/<?= htmlspecialchars($img) ?>" alt="Thumbnail <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- {{product_description}} -->
            <div class="product-specs-box mb-4">
                <h4 class="mb-3">Thông số sản phẩm</h4>
                <table class="specs-table">
                    <tbody>
                        <?php
                        // Tự động phân tích chuỗi thông số thành bảng
                        $specs_text = trim($san_pham['description']);
                        $specs_lines = !empty($specs_text) ? explode("\n", $specs_text) : [];
                        foreach ($specs_lines as $line) {
                            $parts = explode(':', $line, 2); // Tách dòng bởi dấu ':'
                            if (count($parts) === 2) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars(trim($parts[0])) . '</td>';
                                echo '<td>' . htmlspecialchars(trim($parts[1])) . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <div class="specs-toggle-wrapper">
                    <button id="toggle-specs-btn" class="specs-toggle-btn">Xem thêm</button>
                </div>
            </div>

        </div>

        <!-- Cột phải: Giá, Tùy chọn, Mua hàng -->
        <div class="product-purchase-sidebar">
            <!-- {{price_box}} -->
            <div class="price-box mb-3">
                <span class="final-price"><?= number_format($display_price, 0, ',', '.') ?>₫</span>
                <?php if ($original_price): ?>
                    <del class="original-price"><?= number_format($original_price, 0, ',', '.') ?>₫</del>
                    <span class="saving-badge">Giảm <?= $saving_percentage ?>%</span>
                <?php endif; ?>
            </div>

            <!-- {{variant_selection}} -->
            <div class="variant-group mb-3">
                <label class="variant-label">Dung lượng</label>
                <!-- Thêm data-group để JS nhận diện -->
                <div class="variant-options" data-group="storage">
                    <a href="#" class="variant-tag active" data-value="128GB">128GB</a>
                    <a href="#" class="variant-tag" data-value="256GB">256GB</a>
                    <a href="#" class="variant-tag" data-value="512GB">512GB</a>
                </div>
            </div>
            <div class="variant-group mb-4">
                <?php
                    // Mặc định nhãn là "Màu sắc"
                    $color_variant_label = 'Màu sắc';

                    // Lấy tên danh mục từ breadcrumbs (phần tử thứ 2 từ cuối lên) một cách an toàn
                    if (isset($breadcrumbs) && count($breadcrumbs) > 2) {
                        $category_name_from_breadcrumb = $breadcrumbs[count($breadcrumbs) - 2]['title'] ?? '';

                        // Nếu sản phẩm thuộc danh mục "Tai nghe", đổi nhãn
                        if (mb_strtolower($category_name_from_breadcrumb, 'UTF-8') === 'tai nghe') {
                            $color_variant_label = 'Phiên bản màu sắc';
                        }
                    }
                ?>
                <label class="variant-label"><?= htmlspecialchars($color_variant_label) ?></label>
                <div class="variant-options" data-group="color">
                    <?php
                        // Giải mã chuỗi JSON từ CSDL để lấy danh sách các phiên bản màu
                        $variants = !empty($san_pham['variants_json']) ? json_decode($san_pham['variants_json'], true) : [];
                        $is_first_color = true;
                        if (!empty($variants)):
                            foreach ($variants as $variant):
                    ?>
                        <a href="#" 
                           class="variant-tag <?= $is_first_color ? 'active' : '' ?>" 
                           data-value="<?= htmlspecialchars($variant['color']) ?>" 
                           data-image="TaiLen/san_pham/<?= htmlspecialchars($variant['image']) ?>">
                           <?= htmlspecialchars($variant['color']) ?>
                        </a>
                    <?php
                                $is_first_color = false;
                            endforeach;
                        endif;
                    ?>
                </div>
            </div>

            <!-- {{promotion_banner}} -->
            <div class="promotion-box mb-4">
                <div class="promo-header">
                    <i class="fas fa-gift"></i> Khuyến mãi
                </div>
                <ul class="promo-list">
                    <li>Giảm thêm 2% khi mua cùng sản phẩm Apple khác.</li>
                    <li>Thu cũ đổi mới - Trợ giá đến 2 triệu.</li>
                    <li>Trả góp 0% qua thẻ tín dụng.</li>
                </ul>
            </div>

            <!-- {{additional_info}} - Thông tin bổ sung -->
            <div class="additional-info-box mb-4">
                <div class="info-item">
                    <i class="fas fa-truck"></i>
                    <span>Giao hàng dự kiến: <strong>Thứ 4, 25/12</strong></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-sync-alt"></i>
                    <a href="index.php?act=thu_cu_doi_moi">
                        <span>Thu cũ đổi mới - Lên đời siêu tiết kiệm</span>
                    </a>
                </div>
            </div>

            <!-- {{additional_info}} - Thông tin bổ sung -->
            <div class="additional-info-box mb-4">
                <div class="info-item">
                    <i class="fas fa-truck"></i>
                    <span>Giao hàng dự kiến: <strong>Thứ 4, 25/12</strong></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-sync-alt"></i>
                    <a href="index.php?act=thu_cu_doi_moi">
                        <span>Thu cũ đổi mới - Lên đời siêu tiết kiệm</span>
                    </a>
                </div>
            </div>

            <!-- {{purchase_buttons}} --> 
            <form action="index.php?act=them_vao_gio" method="POST" class="purchase-form" id="product-purchase-form">
                <input type="hidden" name="id" value="<?= (int)$san_pham['id'] ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($san_pham['name']) ?>">
                <input type="hidden" name="image_url" value="<?= htmlspecialchars($san_pham['image_url']) ?>">
                <input type="hidden" name="price" value="<?= $display_price ?>">
                <input type="hidden" name="quantity" value="1">
                
                <div class="purchase-buttons-group">
                    <?php if ($is_in_stock): ?>
                        <button type="submit" name="buy_now" class="btn-buy-now">
                            <strong>MUA NGAY</strong>
                            <span>Giao hàng tận nơi hoặc nhận tại cửa hàng</span>
                        </button>
                        <button type="submit" name="add_to_cart" class="btn-add-to-cart">
                        <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                    </button>
                    <?php else: ?>
                        <button type="button" class="btn-add-to-cart" disabled>Hết hàng</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- {{review_box}} -->
    <div id="reviews" class="review-section-wrapper mt-5">
        <!-- Khối Đánh giá & Bình luận -->
        <div class="review-module">
            <div class="review-module-header">
                <h4 class="mb-0">Đánh giá sản phẩm</h4>
            </div>
            <div class="review-module-body">
                <div class="review-summary-and-action">
                    <!-- Phần tổng quan đánh giá -->
                    <div class="review-summary-overview">
                        <div class="summary-left">
                            <div class="average-rating-value"><?= $rating_info['average_rating'] ?>/5</div>
                            <div class="stars-outer d-inline-block">
                                <div class="stars-inner" style="width: <?= ($rating_info['average_rating'] / 5) * 100 ?>%;"></div>
                            </div>
                            <div class="review-count-total">(<?= $rating_info['review_count'] ?> đánh giá)</div>
                        </div>
                        <div class="summary-right">
                            <?php
                            $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                            foreach ($rating_counts as $rating => $count) {
                                if (isset($counts[$rating])) {
                                    $counts[$rating] = $count;
                                }
                            }
                            $total_reviews_with_rating = array_sum($counts);

                            for ($i = 5; $i >= 1; $i--):
                                $count = $counts[$i];
                                $percentage = ($total_reviews_with_rating > 0) ? ($count / $total_reviews_with_rating) * 100 : 0;
                            ?>
                            <div class="rating-bar-row">
                                <div class="rating-bar-label"><?= $i ?> <i class="fas fa-star text-warning"></i></div>
                                <div class="rating-bar-container">
                                    <div class="rating-bar" style="width: <?= $percentage ?>%;"></div>
                                </div>
                                <div class="rating-bar-count"><?= $count ?></div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <!-- Nút viết đánh giá -->
                    <div class="write-review-action">
                        <p>Bạn đã dùng sản phẩm này?</p>
                        <button id="open-review-modal-btn" class="cp-btn">Viết đánh giá</button>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc và danh sách bình luận -->
            <div class="review-module-main">
                 <!-- Bộ lọc đánh giá -->
                 <div class="review-filters">
                    <div class="filter-badges">
                        <?php
                            $base_url = "index.php?act=chi_tiet_san_pham&id=" . (int)$san_pham['id'] . "#reviews";
                            $current_filters = $_GET;
                            unset($current_filters['act'], $current_filters['id']);

                            function is_active_filter($key, $value = null) {
                                global $current_filters;
                                if ($value === null) return isset($current_filters[$key]);
                                return isset($current_filters[$key]) && $current_filters[$key] == $value;
                            }
                        ?>
                        <a href="<?= $base_url ?>" class="filter-badge <?= empty($current_filters) ? 'active' : '' ?>">Tất cả</a>
                        <a href="<?= $base_url ?>&rating=5" class="filter-badge <?= is_active_filter('rating', 5) ? 'active' : '' ?>">5 Sao</a>
                        <a href="<?= $base_url ?>&rating=4" class="filter-badge <?= is_active_filter('rating', 4) ? 'active' : '' ?>">4 Sao</a>
                        <a href="<?= $base_url ?>&rating=3" class="filter-badge <?= is_active_filter('rating', 3) ? 'active' : '' ?>">3 Sao</a>
                        <a href="<?= $base_url ?>&rating=2" class="filter-badge <?= is_active_filter('rating', 2) ? 'active' : '' ?>">2 Sao</a>
                        <a href="<?= $base_url ?>&rating=1" class="filter-badge <?= is_active_filter('rating', 1) ? 'active' : '' ?>">1 Sao</a>
                        <a href="<?= $base_url ?>&has_image=1" class="filter-badge <?= is_active_filter('has_image') ? 'active' : '' ?>">Có hình ảnh</a>
                        <a href="<?= $base_url ?>&verified=1" class="filter-badge <?= is_active_filter('verified') ? 'active' : '' ?>">Đã mua hàng</a>
                    </div>
                </div>

                <!-- Danh sách các bình luận đã có -->
                <div class="reviews-list">
                    <?php if (empty($reviews_tree) && empty($flat_reviews)): ?>
                        <p class="text-center text-muted mt-3">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                    <?php else: ?>
                        <?php
                        function display_reviews($reviews, $is_reply = false) {
                            foreach ($reviews as $review) {
                                echo '<div class="review-item' . ($is_reply ? ' review-reply' : '') . '">';
                                echo '<div class="review-author">';
                                echo '<div class="author-avatar">';
                                if (!empty($review['avatar_url'])) {
                                    echo '<img src="TaiLen/avatars/' . htmlspecialchars($review['avatar_url']) . '" alt="Avatar">';
                                } else {
                                    echo '<span>' . htmlspecialchars(strtoupper(mb_substr($review['fullname'], 0, 1, 'UTF-8'))) . '</span>';
                                }
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="review-content">';
                                echo '<div class="author-info">';
                                echo '<strong>' . htmlspecialchars($review['fullname']) . '</strong>';
                                if ($review['is_verified_purchase']) {
                                    echo '<span class="badge bg-primary-soft text-primary"><i class="fas fa-check-circle me-1"></i> Đã mua hàng</span>';
                                }
                                echo '</div>';
                                if ($review['rating'] > 0) {
                                    echo '<div class="stars-outer mb-2"><div class="stars-inner" style="width: ' . (($review['rating'] / 5) * 100) . '%;"></div></div>';
                                }
                                echo '<p class="mb-1">' . nl2br(htmlspecialchars($review['comment'])) . '</p>';
                                // Hiển thị ảnh review nếu có
                                if (!empty($review['image_url'])) {
                                    echo '<div class="review-image-attachment mt-2"><img src="TaiLen/reviews/' . htmlspecialchars($review['image_url']) . '" alt="Ảnh review" loading="lazy"></div>';
                                }
                                echo '<div class="review-meta">';
                                echo '<span class="review-date">' . date('d/m/Y', strtotime($review['created_at'])) . '</span>';
                                echo '<button class="reply-btn" data-review-id="' . $review['id'] . '" data-author-name="' . htmlspecialchars($review['fullname']) . '">Trả lời</button>';
                                echo '</div>';

                                if (!empty($review['replies'])) {
                                    display_reviews($review['replies'], true);
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        display_reviews($reviews_tree); // Hiển thị các bình luận đã được sắp xếp
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal/Popup để viết đánh giá -->
<div id="review-modal" class="review-modal-overlay">
    <div class="review-modal-content">
        <div class="review-modal-header">
            <h4>Đánh giá sản phẩm</h4>
            <button id="close-review-modal-btn" class="close-btn">&times;</button>
        </div>
        <div class="review-modal-body">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="index.php?act=them_binh_luan" method="POST" enctype="multipart/form-data" id="review-form">
                    <input type="hidden" name="product_id" value="<?= (int)$san_pham['id'] ?>">
                    
                    <div class="form-group mb-3">
                        <div class="rating-criterion-row">
                            <label class="form-label">Chất lượng sản phẩm:</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 sao">★</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 sao">★</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 sao">★</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 sao">★</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 sao">★</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <textarea id="comment" name="comment" rows="4" class="form-control" placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm..." required></textarea>
                    </div> 

                    <div class="form-group mb-4">
                        <label for="review_image" class="form-label-upload"><i class="fas fa-camera"></i> Gửi hình ảnh thực tế</label>
                        <input type="file" name="review_image" id="review_image" class="form-control-upload" accept="image/*">
                    </div>

                    <button type="submit" id="submit-review-btn" class="cp-btn w-100">Gửi đánh giá</button>
                </form>
            <?php else: ?>
                <div class="alert alert-secondary text-center">Vui lòng <a href="index.php?act=dang_nhap&redirect=chi_tiet_san_pham&id=<?= (int)$san_pham['id'] ?>">đăng nhập</a> để để lại đánh giá.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- {{recently_viewed_products}} -->
<?php if (!empty($recently_viewed_products)): ?>
<div class="cp-section recently-viewed-section">
    <div class="cp-section__head">
        <h3>Sản phẩm đã xem</h3>
    </div>
    <!-- Swiper -->
    <div class="swiper recently-viewed-slider">
        <div class="swiper-wrapper">
            <?php foreach ($recently_viewed_products as $product): ?>
                <div class="swiper-slide">
                    <?php
                        // Chuẩn bị dữ liệu giá cho card
                        $is_on_sale_rv = isset($product['sale_price']) && $product['sale_price'] > 0 && $product['sale_price'] < $product['price'];
                        $display_price_rv = $is_on_sale_rv ? $product['sale_price'] : $product['price'];
                        $original_price_rv = $is_on_sale_rv ? $product['price'] : null;
                    ?>
                    <div class="cp-card">
                        <a href="index.php?act=chi_tiet_san_pham&id=<?= $product['id'] ?>">
                            <div class="cp-card__image-container">
                                <img src="TaiLen/san_pham/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="cp-card__content">
                                <h4><?= htmlspecialchars($product['name']) ?></h4>
                                <div class="cp-price">
                                    <span class="now"><?= number_format($display_price_rv, 0, ',', '.') ?>₫</span>
                                    <?php if ($original_price_rv): ?><del><?= number_format($original_price_rv, 0, ',', '.') ?>₫</del><?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- JS cho Modal Đánh giá ---
    const reviewModal = document.getElementById('review-modal');
    const openReviewModalBtn = document.getElementById('open-review-modal-btn');
    const closeReviewModalBtn = document.getElementById('close-review-modal-btn');

    if (reviewModal && openReviewModalBtn && closeReviewModalBtn) {
        openReviewModalBtn.addEventListener('click', () => {
            <?php if (isset($_SESSION['user_id'])): ?>
                reviewModal.classList.add('show');
            <?php else: ?>
                // Nếu chưa đăng nhập, hỏi người dùng có muốn chuyển đến trang đăng nhập không
                if (confirm('Vui lòng đăng nhập để viết đánh giá. Bạn có muốn chuyển đến trang đăng nhập không?')) {
                    window.location.href = 'index.php?act=dang_nhap&redirect=chi_tiet_san_pham&id=<?= (int)$san_pham['id'] ?>';
                }
            <?php endif; ?>
        });

        const closeModal = () => {
            reviewModal.classList.remove('show');
        };

        closeReviewModalBtn.addEventListener('click', closeModal);
        // Đóng modal khi click vào vùng nền mờ bên ngoài
        reviewModal.addEventListener('click', (event) => {
            if (event.target === reviewModal) {
                closeModal();
            }
        });
    }

    // --- JS cho form trả lời bình luận ---
    // Di chuyển form vào modal nếu cần, hoặc giữ nguyên nếu trả lời là inline
    const reviewForm = document.getElementById('review-form'); // Form trong modal
    const parentIdInput = reviewForm ? reviewForm.querySelector('input[name="parent_id"]') : null;
    const commentTextarea = document.getElementById('comment');
    const submitBtn = document.getElementById('submit-review-btn');
    
    // Tạm thời vô hiệu hóa chức năng trả lời vì form đã chuyển vào modal
    // Nếu muốn giữ, cần logic phức tạp hơn để mở modal và điền sẵn thông tin
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            alert('Chức năng trả lời đang được phát triển. Vui lòng viết một đánh giá mới.');
            // Hoặc mở modal và xử lý logic trả lời
            // openReviewModalBtn.click();
            // ... set parent_id, ...
        });
    });

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

    // --- JS cho form thêm vào giỏ hàng ---
    // Đảm bảo form "Mua ngay" và "Thêm vào giỏ" hoạt động với AJAX
    const purchaseForm = document.getElementById('product-purchase-form');
    if (purchaseForm) {
        purchaseForm.addEventListener('submit', function(event) {
            // Ngăn chặn submit mặc định để xử lý bằng AJAX
            event.preventDefault();
            
            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');
            const cartBadge = document.getElementById('cart-badge');

            // Chỉ chạy hiệu ứng khi nhấn nút "Thêm vào giỏ"
            if (event.submitter && event.submitter.name === 'add_to_cart') {
                const productImage = document.querySelector('.product-main-image');
                flyToCart(productImage);
            }

            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && cartBadge) {
                    cartBadge.textContent = data.new_total_quantity;
                    cartBadge.style.transform = 'scale(1.3)';
                    setTimeout(() => { cartBadge.style.transform = 'scale(1)'; }, 200);
                }
                // Nếu là nút "Mua ngay", chuyển hướng đến trang thanh toán
                if (event.submitter && event.submitter.name === 'buy_now') {
                    // Sau khi thêm sản phẩm, chuyển thẳng đến trang giỏ hàng
                    window.location.href = 'index.php?act=gio_hang';
                } else {
                    // Không cần alert vì đã có hiệu ứng fly-to-cart
                }
            })
            .catch(error => console.error('Lỗi khi thêm vào giỏ hàng:', error));
        });
    }

    // --- JS cho các nút chọn phiên bản (màu sắc, dung lượng) ---
    const finalPriceEl = document.querySelector('.price-box .final-price');
    const stickyPriceEl = document.querySelector('.sticky-price');
    const formPriceInput = document.querySelector('#product-purchase-form input[name="price"]')
    const mainImageEl = document.getElementById('main-product-image');
    const stickyImageEl = document.querySelector('.sticky-product-info img');

    // --- DỮ LIỆU GIÁ CHO CÁC PHIÊN BẢN ---
    // Trong thực tế, bạn nên lấy dữ liệu này từ CSDL và in ra bằng PHP
    const basePrice = <?= $display_price ?>;
    <?php
        // Tạo đối tượng giá từ PHP để JS sử dụng
        $js_variant_prices = [
            "128GB" => [], "256GB" => [], "512GB" => []
        ];
        $storages = ["128GB" => 0, "256GB" => 2000000, "512GB" => 5000000];

        if (!empty($variants)) {
            foreach ($storages as $storage => $price_increase) {
                foreach ($variants as $variant) {
                    // Giả sử giá của các màu là như nhau trong cùng một dung lượng
                    // Bạn có thể thêm logic phức tạp hơn ở đây nếu giá mỗi màu khác nhau
                    $js_variant_prices[$storage][$variant['color']] = $display_price + $price_increase;
                }
            }
        }
    ?>
    const variantPrices = {
        "128GB": {
            ...<?= json_encode($js_variant_prices['128GB']) ?>
        },
        "256GB": {
            ...<?= json_encode($js_variant_prices['256GB']) ?>
        },
        "512GB": {
            ...<?= json_encode($js_variant_prices['512GB']) ?>
        }
    };

    // Hàm định dạng số tiền
    const formatCurrency = (price) => {
        return new Intl.NumberFormat('vi-VN').format(price) + '₫';
    };

    // Hàm cập nhật giá trên các nút dung lượng
    function updateStoragePriceTags() {
        const storageGroup = document.querySelector('.variant-options[data-group="storage"]');
        if (!storageGroup) return;

        storageGroup.querySelectorAll('.variant-tag').forEach(tag => {
            const storageValue = tag.dataset.value;
            // Tìm một phiên bản bất kỳ có dung lượng này để lấy giá đại diện
            const representativeVariant = allVariants.find(v => v.storage === storageValue && v.price > 0);
            const price = representativeVariant ? representativeVariant.price : basePrice;
            
            const priceEl = tag.querySelector('.option-price');
            if (priceEl) priceEl.textContent = formatCurrency(price);
        });
    }

    // Hàm cập nhật giá dựa trên các lựa chọn hiện tại
    function updatePrice() {
        const selectedStorage = document.querySelector('.variant-options[data-group="storage"] .variant-tag.active')?.dataset.value;
        const selectedColor = document.querySelector('.variant-options[data-group="color"] .variant-tag.active')?.dataset.value;

        if (selectedStorage && selectedColor) {
            const price = variantPrices[selectedStorage]?.[selectedColor] || basePrice;

            // 1. Cập nhật giá chính
            if (finalPriceEl) finalPriceEl.textContent = formatCurrency(price);
            // 2. Cập nhật giá trong thanh sticky
            if (stickyPriceEl) stickyPriceEl.textContent = formatCurrency(price);
            // 3. Cập nhật giá trong form ẩn để thêm vào giỏ hàng
            if (formPriceInput) formPriceInput.value = price;
        }
    }

    // Hàm cập nhật hình ảnh
    function updateImage(imageUrl) {
        if (!imageUrl || !mainImageEl) return;
        mainImageEl.src = imageUrl;
        if (stickyImageEl) stickyImageEl.src = imageUrl;
    }

    // Gắn sự kiện click cho tất cả các nhóm phiên bản
    document.querySelectorAll('.variant-options').forEach(group => {
        group.addEventListener('click', function(event) {
            const clickedTag = event.target.closest('.variant-tag');
            if (!clickedTag) return;

            event.preventDefault();

            // Bỏ active các nút khác trong cùng nhóm
            group.querySelectorAll('.variant-tag').forEach(tag => tag.classList.remove('active'));
            
            // Active nút vừa click
            clickedTag.classList.add('active');

            // Gọi hàm cập nhật giá
            updatePrice();

            // Nếu là nhóm màu sắc, gọi hàm cập nhật ảnh
            if (group.dataset.group === 'color') {
                const newImageUrl = clickedTag.dataset.image;
                updateImage(newImageUrl);
            }
        });
    });

    // --- JS cho Gallery ảnh ---
    const mainProductImage = document.getElementById('main-product-image');
    const thumbnailItems = document.querySelectorAll('.thumbnail-item');

    thumbnailItems.forEach(item => {
        item.addEventListener('click', function() {
            // Bỏ active tất cả thumbnail
            thumbnailItems.forEach(thumb => thumb.classList.remove('active'));
            // Active thumbnail được click
            this.classList.add('active');
            // Lấy src của ảnh trong thumbnail và cập nhật ảnh chính
            const newImageSrc = this.querySelector('img').src;
            if (mainProductImage) {
                mainProductImage.src = newImageSrc;
            }
        });
    });

    // --- JS cho nút xem thêm/thu gọn thông số ---
    const specsTable = document.querySelector('.specs-table');
    const toggleSpecsBtn = document.getElementById('toggle-specs-btn');

    if (specsTable && toggleSpecsBtn) {
        const allRows = specsTable.querySelectorAll('tbody tr');
        const initialVisibleRows = 5; // Số dòng hiển thị ban đầu

        if (allRows.length > initialVisibleRows) {
            specsTable.classList.add('collapsed'); // Thêm class để ẩn bớt dòng

            toggleSpecsBtn.addEventListener('click', function() {
                specsTable.classList.toggle('collapsed');
                if (specsTable.classList.contains('collapsed')) {
                    this.textContent = 'Xem thêm';
                } else {
                    this.textContent = 'Thu gọn';
                }
            });
        } else {
            toggleSpecsBtn.style.display = 'none'; // Ẩn nút nếu không đủ dòng
        }
    }

    // --- JS cho slider sản phẩm đã xem ---
    var recentlyViewedSwiper = new Swiper(".recently-viewed-slider", {
        slidesPerView: 2,
        spaceBetween: 15,
        breakpoints: {
            640: { slidesPerView: 3, spaceBetween: 20 },
            768: { slidesPerView: 4, spaceBetween: 20 },
            1024: { slidesPerView: 5, spaceBetween: 20 },
        },
        // Không cần navigation và pagination cho slider này để gọn gàng hơn
        // navigation: { ... },
        // pagination: { ... },
    });
});
</script>