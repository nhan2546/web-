<?php
// --- HELPER FUNCTION & DATA PREPARATION ---

// Hàm tạo URL với các tham số filter hiện tại
function generate_filter_url($new_params) {
    $current_params = $_GET;
    unset($current_params['page']); // Bỏ qua trang khi thay đổi filter

    // Gộp tham số mới vào tham số hiện tại
    foreach ($new_params as $key => $value) {
        if ($key === 'brands') {
            // Xử lý đặc biệt cho brand (checkbox)
            $current_brands = $current_params['brands'] ?? [];
            if (in_array($value, $current_brands)) {
                // Nếu đã chọn, bỏ chọn
                $current_brands = array_diff($current_brands, [$value]);
            } else {
                // Nếu chưa chọn, thêm vào
                $current_brands[] = $value;
            }
            $current_params['brands'] = $current_brands;
        } else {
            // Ghi đè các tham số khác (price, sort)
            $current_params[$key] = $value;
        }
    }

    // Xóa các tham số rỗng
    $current_params = array_filter($current_params);

    return 'index.php?' . http_build_query($current_params);
}

// Các khoảng giá để lọc
$price_ranges = [
    '0-2000000' => 'Dưới 2 triệu',
    '2000000-5000000' => 'Từ 2 - 5 triệu',
    '5000000-10000000' => 'Từ 5 - 10 triệu',
    '10000000-15000000' => 'Từ 10 - 15 triệu',
    '15000000-0' => 'Trên 15 triệu',
];

$current_brands = $_GET['brands'] ?? [];
$current_price = $_GET['price'] ?? '';
?>

<!-- Header trang (đã có từ lần chỉnh sửa trước) -->
<div class="product-list-header">
    <h1 class="product-list-title">
        <?= htmlspecialchars($category_info['name'] ?? 'Tất cả sản phẩm') ?>
    </h1>
</div>

<!-- Bố cục chính của trang -->
<div class="product-list-layout">
    <!-- Cột trái: Sidebar bộ lọc -->
    <aside class="product-list-sidebar">
        <div class="filter-block">
            <h4 class="filter-title">Hãng sản xuất</h4>
            <div class="filter-options">
                <?php if (!empty($available_brands)): ?>
                    <?php foreach ($available_brands as $brand): ?>
                        <div class="filter-checkbox">
                            <input type="checkbox" 
                                   id="brand-<?= $brand['id'] ?>" 
                                   onchange="window.location.href='<?= generate_filter_url(['brands' => $brand['name']]) ?>'"
                                   <?= in_array($brand['name'], $current_brands) ? 'checked' : '' ?>>
                            <label for="brand-<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="filter-block">
            <h4 class="filter-title">Mức giá</h4>
            <div class="filter-options">
                <?php foreach ($price_ranges as $range => $label): ?>
                    <div class="filter-checkbox">
                        <input type="radio" 
                               name="price_range" 
                               id="price-<?= $range ?>"
                               onchange="window.location.href='<?= generate_filter_url(['price' => $range]) ?>'"
                               <?= ($current_price === $range) ? 'checked' : '' ?>>
                        <label for="price-<?= $range ?>"><?= $label ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>

    <!-- Cột phải: Nội dung chính -->
    <div class="product-list-main">
        <!-- Thanh công cụ sắp xếp (đã có từ lần chỉnh sửa trước) -->
        <div class="product-list-toolbar">
            <div class="product-count">
                <strong><?= $total_products ?? 0 ?></strong> sản phẩm
            </div>

            <div class="sorting-options">
                <label for="sort-select">Sắp xếp theo:</label>
                <div class="sort-select-wrapper">
                    <select id="sort-select" class="sort-select" onchange="window.location.href=this.value;">
                        <?php
                            $sort_options = [
                                'newest' => 'Mới nhất',
                                'price_asc' => 'Giá tăng dần',
                                'price_desc' => 'Giá giảm dần',
                                'name_asc' => 'Tên A-Z',
                            ];
                            $current_sort = $_GET['sort'] ?? 'newest';

                            foreach ($sort_options as $key => $label):
                                $url = generate_filter_url(['sort' => $key]);
                                $selected = ($current_sort === $key) ? 'selected' : '';
                        ?>
                            <option value="<?= $url ?>" <?= $selected ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lưới sản phẩm -->
        <div class="cp-grid product-list-main-grid">
            <?php if (!empty($danh_sach_san_pham)): ?>
                <?php foreach ($danh_sach_san_pham as $sp): ?>
                    <article class="cp-card">
                        <a href="index.php?act=chi_tiet_san_pham&id=<?= (int)$sp['id'] ?>">
                            <div class="cp-card__image-container">
                                <img src="TaiLen/san_pham/<?= htmlspecialchars($sp['image_url']) ?>" alt="<?= htmlspecialchars($sp['name']) ?>">
                            </div>
                            <div class="cp-card__content">
                                <h4><?= htmlspecialchars($sp['name']) ?></h4>
                                <div class="cp-price">
                                    <?php if (isset($sp['sale_price']) && (float)$sp['sale_price'] > 0 && $sp['sale_price'] < $sp['price']): ?>
                                        <span class="now"><?= number_format($sp['sale_price'], 0, ',', '.') ?>₫</span>
                                        <del><?= number_format($sp['price'], 0, ',', '.') ?>₫</del>
                                    <?php else: ?>
                                        <span class="now"><?= number_format($sp['price'], 0, ',', '.') ?>₫</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products-message">Không tìm thấy sản phẩm nào phù hợp với tiêu chí của bạn.</p>
            <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <?php include __DIR__ . '/../../DieuKhien/phan_trang.php'; ?>
    </div>
</div>