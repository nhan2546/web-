<div class="text-center my-4">
    <h2 class="mt-4">Chào Mừng Đến Shop!</h2>
    <p class="lead">Nơi bạn có thể tìm thấy những sản phẩm tuyệt vời nhất.</p>
</div>

<hr>

<h3 class="mt-5 mb-4 text-center">Sản phẩm mới nhất</h3>
<div class="danh_sach_san_pham">
    <?php if (!empty($danh_sach_san_pham)): ?>
        <?php foreach ($danh_sach_san_pham as $san_pham): ?>
            <div class="product-grid">
                <a href="index.php?act=chi_tiet_san_pham&id=<?= htmlspecialchars($san_pham['id']) ?>">
                    <img src="TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="<?= htmlspecialchars($san_pham['name']) ?>">
                    <h3><?= htmlspecialchars($san_pham['name']) ?></h3>
                    <p class="price"><?= number_format($san_pham['price'], 0, ',', '.') ?> VND</p>
                    <button>Thêm Vào Giỏ</button>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center">Chưa có sản phẩm nào để hiển thị.</p>
    <?php endif; ?>
</div>
