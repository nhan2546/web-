<?php
if (!$san_pham) {
    echo "<p>Sản phẩm không tồn tại.</p>";
    return;
}
?>

<div class="product-detail-wrapper">
    <div class="product-detail-grid">
        <!-- Cột hình ảnh -->
        <div class="product-detail-images">
            <img src="TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="<?= htmlspecialchars($san_pham['name']) ?>">
        </div>

        <!-- Cột thông tin và mua hàng -->
        <div class="product-detail-info">
            <h1><?= htmlspecialchars($san_pham['name']) ?></h1>

            <!-- Hiển thị đánh giá trung bình -->
            <div class="product-rating-summary">
                <span class="rating-value"><?= $rating_info['average_rating'] ?></span>
                <div class="stars-outer">
                    <div class="stars-inner" style="width: <?= ($rating_info['average_rating'] / 5) * 100 ?>%;"></div>
                </div>
                <a href="#reviews" class="review-count">(<?= $rating_info['review_count'] ?> đánh giá)</a>
            </div>

            <div class="product-price">
                <?php if (isset($san_pham['sale_price']) && $san_pham['sale_price'] > 0 && $san_pham['sale_price'] < $san_pham['price']): ?>
                    <span class="now"><?= number_format($san_pham['sale_price'], 0, ',', '.') ?>₫</span>
                    <del><?= number_format($san_pham['price'], 0, ',', '.') ?>₫</del>
                <?php else: ?>
                    <span class="now"><?= number_format($san_pham['price'], 0, ',', '.') ?>₫</span>
                <?php endif; ?>
            </div>

            <div class="product-description">
                <p><?= nl2br(htmlspecialchars($san_pham['description'])) ?></p>
            </div>

            <form action="index.php?act=them_vao_gio" method="POST" class="add-to-cart-form">
                <input type="hidden" name="id" value="<?= (int)$san_pham['id'] ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($san_pham['name']) ?>">
                <input type="hidden" name="image_url" value="<?= htmlspecialchars($san_pham['image_url']) ?>">
                <input type="hidden" name="price" value="<?= (isset($san_pham['sale_price']) && $san_pham['sale_price'] > 0) ? $san_pham['sale_price'] : $san_pham['price'] ?>">
                
                <div class="quantity-selector">
                    <label for="quantity">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= (int)$san_pham['stock_quantity'] ?>">
                </div>

                <button type="submit" class="cp-btn" <?= ($san_pham['stock_quantity'] <= 0) ? 'disabled' : '' ?>>
                    <?= ($san_pham['stock_quantity'] > 0) ? 'Thêm vào giỏ hàng' : 'Hết hàng' ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Khu vực bình luận và đánh giá -->
    <div id="reviews" class="reviews-section">
        <h2>Đánh giá & Bình luận</h2>

        <!-- Form để lại bình luận (chỉ hiển thị khi đã đăng nhập) -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="review-form-container">
                <h4>Để lại đánh giá của bạn</h4>
                <form action="index.php?act=them_binh_luan" method="POST">
                    <input type="hidden" name="product_id" value="<?= (int)$san_pham['id'] ?>">
                    <div class="form-group">
                        <label>Đánh giá của bạn:</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 sao"></label>
                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 sao"></label>
                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 sao"></label>
                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 sao"></label>
                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 sao"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Bình luận của bạn:</label>
                        <textarea id="comment" name="comment" rows="4" placeholder="Sản phẩm này tuyệt vời..."></textarea>
                    </div>
                    <button type="submit" class="cp-btn">Gửi đánh giá</button>
                </form>
            </div>
        <?php else: ?>
            <p>Vui lòng <a href="index.php?act=dang_nhap&redirect=chi_tiet_san_pham&id=<?= (int)$san_pham['id'] ?>">đăng nhập</a> để để lại đánh giá.</p>
        <?php endif; ?>

        <!-- Danh sách các bình luận đã có -->
        <div class="reviews-list">
            <?php if (empty($reviews)): ?>
                <p>Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-author">
                            <div class="author-avatar">
                                <?php if (!empty($review['avatar_url'])): ?>
                                    <img src="TaiLen/avatars/<?= htmlspecialchars($review['avatar_url']) ?>" alt="Avatar">
                                <?php else: ?>
                                    <span><?= htmlspecialchars(strtoupper(mb_substr($review['fullname'], 0, 1, 'UTF-8'))) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="author-info">
                                <strong><?= htmlspecialchars($review['fullname']) ?></strong>
                                <span class="review-date"><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                            </div>
                        </div>
                        <div class="review-content">
                            <div class="stars-outer">
                                <div class="stars-inner" style="width: <?= ($review['rating'] / 5) * 100 ?>%;"></div>
                            </div>
                            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>