<div class="container">
    <h2 class="mb-4"><?= htmlspecialchars($keyword ?? '') ?></h2>

    <div class="danh_sach_san_pham">
        <?php if (!empty($danh_sach_san_pham)): ?>
            <?php foreach ($danh_sach_san_pham as $san_pham): ?>
                <div class="product-grid">
                    <a href="index.php?act=chi_tiet_san_pham&id=<?= htmlspecialchars($san_pham['id']) ?>" class="product-link">
                        <img src="TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="<?= htmlspecialchars($san_pham['name']) ?>">
                        <h3><?= htmlspecialchars($san_pham['name']) ?></h3>
                        <p class="price"><?= number_format($san_pham['price'], 0, ',', '.') ?> VND</p>
                    </a>
                    <form action="index.php?act=them_vao_gio" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($san_pham['id']) ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($san_pham['name']) ?>">
                        <input type="hidden" name="price" value="<?= htmlspecialchars($san_pham['price']) ?>">
                        <input type="hidden" name="image_url" value="<?= htmlspecialchars($san_pham['image_url']) ?>">
                        <button type="submit">Thêm Vào Giỏ</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="col-12 text-center">Không tìm thấy sản phẩm nào phù hợp.</p>
        <?php endif; ?>
    </div>
</div>
