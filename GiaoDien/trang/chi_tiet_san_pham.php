<?php
if (!$san_pham) {
    echo "<p>Sản phẩm không tồn tại.</p>";
    return;
}
?>

<!-- Thẻ div container my-5 đã được loại bỏ, vì layout chung đã có cp-container -->
<!-- Main Product Info Card -->
    <div class="card product-detail-card shadow-sm mb-5">
        <div class="card-body">
            <div class="row g-4">
                <!-- Cột hình ảnh -->
                <div class="col-lg-5 text-center">
                    <img src="TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="<?= htmlspecialchars($san_pham['name']) ?>" class="img-fluid rounded">
                </div>

                <!-- Cột thông tin và mua hàng -->
                <div class="col-lg-7 d-flex flex-column">
                    <h1 class="product-title"><?= htmlspecialchars($san_pham['name']) ?></h1>

                    <!-- Đánh giá và tình trạng kho -->
                    <div class="d-flex align-items-center gap-4 mb-3">
                        <div class="product-rating-summary">
                            <span class="rating-value fw-bold text-warning"><?= $rating_info['average_rating'] ?></span>
                            <div class="stars-outer d-inline-block mx-1">
                                <div class="stars-inner" style="width: <?= ($rating_info['average_rating'] / 5) * 100 ?>%;"></div>
                            </div>
                            <a href="#reviews" class="review-count text-decoration-none">(<?= $rating_info['review_count'] ?> đánh giá)</a>
                        </div>
                        <div class="stock-status">
                            <?php if ($san_pham['stock_quantity'] > 0): ?>
                                <span class="badge bg-success-soft text-success">Còn hàng</span>
                            <?php else: ?>
                                <span class="badge bg-danger-soft text-danger">Hết hàng</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="product-price-box bg-light p-3 rounded mb-4">
                        <?php if (isset($san_pham['sale_price']) && $san_pham['sale_price'] > 0 && $san_pham['sale_price'] < $san_pham['price']): ?>
                            <span class="h2 text-danger fw-bold me-3"><?= number_format($san_pham['sale_price'], 0, ',', '.') ?>₫</span>
                            <del class="text-muted"><?= number_format($san_pham['price'], 0, ',', '.') ?>₫</del>
                        <?php else: ?>
                            <span class="h2 text-danger fw-bold"><?= number_format($san_pham['price'], 0, ',', '.') ?>₫</span>
                        <?php endif; ?>
                    </div>

                    <!-- Form thêm vào giỏ hàng -->
                    <form action="index.php?act=them_vao_gio" method="POST" class="mt-auto">
                        <input type="hidden" name="id" value="<?= (int)$san_pham['id'] ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($san_pham['name']) ?>">
                        <input type="hidden" name="image_url" value="<?= htmlspecialchars($san_pham['image_url']) ?>">
                        <input type="hidden" name="price" value="<?= (isset($san_pham['sale_price']) && $san_pham['sale_price'] > 0) ? $san_pham['sale_price'] : $san_pham['price'] ?>">
                        
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <label for="quantity" class="form-label mb-0 fw-bold">Số lượng:</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" style="width: 80px;" value="1" min="1" max="<?= (int)$san_pham['stock_quantity'] ?>">
                        </div>

                        <button type="submit" class="cp-btn cp-btn-lg w-100" <?= ($san_pham['stock_quantity'] <= 0) ? 'disabled' : '' ?>>
                            <i class="fas fa-cart-plus me-2"></i>
                            <?= ($san_pham['stock_quantity'] > 0) ? 'Thêm vào giỏ hàng' : 'Hết hàng' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Description and Reviews Section -->
    <div class="row g-5">
        <!-- Cột mô tả -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">Mô tả sản phẩm</h4>
                </div>
                <div class="card-body product-description">
                    <p><?= nl2br(htmlspecialchars($san_pham['description'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Cột đánh giá -->
        <div class="col-lg-4">
            <div id="reviews" class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">Đánh giá & Bình luận</h4>
                </div>
                <div class="card-body">
                    <!-- Form để lại bình luận -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="review-form-container mb-4">
                            <form action="index.php?act=them_binh_luan" method="POST">
                                <input type="hidden" name="product_id" value="<?= (int)$san_pham['id'] ?>">
                                <input type="hidden" name="parent_id" id="parent_id_input" value="">
                                <div id="replying-to-container" class="mb-2" style="display: none;">
                                    <small class="text-muted">Đang trả lời <strong></strong> <button type="button" id="cancel-reply-btn" class="btn-close btn-sm"></button></small>
                                </div>
                                <div class="form-group mb-2 star-rating-group">
                                    <label class="form-label">Đánh giá của bạn:</label>
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 sao"></label>
                                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 sao"></label>
                                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 sao"></label>
                                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 sao"></label>
                                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 sao"></label>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <textarea id="comment" name="comment" rows="3" class="form-control" placeholder="Sản phẩm này tuyệt vời..." required></textarea>
                                </div> 
                                <button type="submit" id="submit-review-btn" class="cp-btn w-100">Gửi đánh giá</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary text-center">Vui lòng <a href="index.php?act=dang_nhap&redirect=chi_tiet_san_pham&id=<?= (int)$san_pham['id'] ?>">đăng nhập</a> để để lại đánh giá.</div>
                    <?php endif; ?>

                    <!-- Danh sách các bình luận đã có -->
                    <div class="reviews-list">
                        <?php if (empty($reviews_tree)): ?>
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
                                    echo '<div class="author-info">';
                                    echo '<strong>' . htmlspecialchars($review['fullname']) . '</strong>';
                                    echo '<span class="review-date">' . date('d/m/Y', strtotime($review['created_at'])) . '</span>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="review-content">';
                                    if ($review['rating'] > 0) {
                                        echo '<div class="stars-outer mb-2"><div class="stars-inner" style="width: ' . (($review['rating'] / 5) * 100) . '%;"></div></div>';
                                    }
                                    echo '<p class="mb-1">' . nl2br(htmlspecialchars($review['comment'])) . '</p>';
                                    echo '<button class="reply-btn" data-review-id="' . $review['id'] . '" data-author-name="' . htmlspecialchars($review['fullname']) . '">Trả lời</button>';
                                    echo '</div>';

                                    if (!empty($review['replies'])) {
                                        display_reviews($review['replies'], true);
                                    }

                                    echo '</div>';
                                }
                            }
                            display_reviews($reviews_tree);
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ thực thi script nếu có form bình luận
    const reviewFormContainer = document.querySelector('.review-form-container');
    if (!reviewFormContainer) return;

    const parentIdInput = document.getElementById('parent_id_input');
    const commentTextarea = document.getElementById('comment');
    const submitBtn = document.getElementById('submit-review-btn');
    const replyingToContainer = document.getElementById('replying-to-container');
    const replyingToName = replyingToContainer.querySelector('strong');
    const cancelReplyBtn = document.getElementById('cancel-reply-btn');
    const starRatingGroup = document.querySelector('.star-rating-group');

    function resetReplyState() {
        parentIdInput.value = '';
        replyingToContainer.style.display = 'none';
        submitBtn.textContent = 'Gửi đánh giá';
        commentTextarea.placeholder = 'Sản phẩm này tuyệt vời...';
        starRatingGroup.style.display = 'block'; // Hiện lại phần đánh giá sao
    }

    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-review-id');
            const authorName = this.getAttribute('data-author-name');

            // Cập nhật UI cho trạng thái trả lời
            parentIdInput.value = reviewId;
            replyingToName.textContent = authorName;
            replyingToContainer.style.display = 'block';
            submitBtn.textContent = 'Gửi trả lời';
            commentTextarea.placeholder = `Trả lời ${authorName}...`;
            starRatingGroup.style.display = 'none'; // Ẩn đánh giá sao khi trả lời

            // Focus và cuộn đến form
            commentTextarea.focus();
            reviewFormContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // Xử lý nút hủy trả lời
    cancelReplyBtn.addEventListener('click', resetReplyState);
});
</script>