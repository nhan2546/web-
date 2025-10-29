<div class="container">
    <h2 class="mb-4 search-results-title">Kết Quả Tìm Kiếm cho: "<?= htmlspecialchars($keyword ?? '') ?>"</h2>

    <div class="cp-grid product-list-main-grid">
        <?php if (!empty($danh_sach_san_pham)): ?>
            <?php foreach ($danh_sach_san_pham as $san_pham): ?>
                <article class="cp-card">
                    <a href="index.php?act=chi_tiet_san_pham&id=<?= htmlspecialchars($san_pham['id']) ?>" class="product-link">
                        <div class="cp-card__image-container">
                            <img src="TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="<?= htmlspecialchars($san_pham['name']) ?>">
                        </div>
                        <div class="cp-card__content">
                            <h4><?= htmlspecialchars($san_pham['name']) ?></h4>
                            <div class="cp-price">
                                <?php
                                // Giả định rằng sản phẩm tìm kiếm cũng có thể có giá khuyến mãi
                                $display_price = $san_pham['price'];
                                $has_sale_price = isset($san_pham['sale_price']) && (float)$san_pham['sale_price'] > 0 && $san_pham['sale_price'] < $san_pham['price'];
                                if ($has_sale_price) {
                                    $display_price = $san_pham['sale_price'];
                                }
                                ?>
                                <span class="now"><?= number_format($display_price, 0, ',', '.') ?>₫</span>
                                <?php if ($has_sale_price): ?>
                                    <del><?= number_format($san_pham['price'], 0, ',', '.') ?>₫</del>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    <form action="index.php?act=them_vao_gio" method="post" class="add-to-cart-form">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($san_pham['id']) ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($san_pham['name']) ?>">
                        <input type="hidden" name="price" value="<?= htmlspecialchars($san_pham['price']) ?>">
                        <input type="hidden" name="image_url" value="<?= htmlspecialchars($san_pham['image_url']) ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="cp-btn cp-btn-primary add-to-cart-btn">Thêm Vào Giỏ</button>
                    </form>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="col-12 text-center no-products-message">Không tìm thấy sản phẩm nào phù hợp.</p>
        <?php endif; ?>
    </div>
</div>
