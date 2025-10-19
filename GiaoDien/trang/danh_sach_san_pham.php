<div class="product-list-page-wrapper">
    <div class="page-header">
        <h1>
            <?php 
            // Hiển thị tên danh mục nếu có, ngược lại hiển thị tiêu đề chung
            if (isset($category_info) && $category_info) {
                echo htmlspecialchars($category_info['name']);
            } else {
                echo "Tất cả sản phẩm";
            }
            ?>
        </h1>
        <p>Khám phá các sản phẩm công nghệ hàng đầu với mức giá tốt nhất.</p>
    </div>

    <!-- Phần bộ lọc (tùy chọn, có thể mở rộng sau) -->
    <div class="filter-bar">
        <div class="filter-group">
            <label for="sort-by">Sắp xếp theo:</label>
            <select id="sort-by" name="sort-by" class="form-select">
                <option value="default">Mặc định</option>
                <option value="price-asc">Giá: Thấp đến Cao</option>
                <option value="price-desc">Giá: Cao đến Thấp</option>
                <option value="newest">Mới nhất</option>
            </select>
        </div>
    </div>

    <!-- Lưới sản phẩm -->
    <div class="cp-grid product-list-grid">
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
                                <?php if (isset($sp['sale_price']) && (float)$sp['sale_price'] > 0 && $sp['sale_price'] < $sp['price']): ?>
                                    <span class="now"><?= number_format($sp['sale_price'], 0, ',', '.') ?>₫</span>
                                    <del><?= number_format($sp['price'], 0, ',', '.') ?>₫</del>
                                <?php else: ?>
                                    <span class="now"><?= number_format($sp['price'], 0, ',', '.') ?>₫</span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <form action="index.php?act=them_vao_gio" method="POST" class="mt-auto">
                            <input type="hidden" name="id" value="<?= (int)$sp['id'] ?>"><input type="hidden" name="name" value="<?= htmlspecialchars($sp['name']) ?>"><input type="hidden" name="image_url" value="<?= htmlspecialchars($sp['image_url']) ?>"><input type="hidden" name="price" value="<?= (isset($sp['sale_price']) && $sp['sale_price'] > 0) ? $sp['sale_price'] : $sp['price'] ?>"><button type="submit" class="cp-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products-message">Không tìm thấy sản phẩm nào phù hợp.</p>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <?php include __DIR__ . '/../../DieuKhien/phan_trang.php'; ?>
</div>