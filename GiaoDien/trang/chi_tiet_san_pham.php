<div class="container">
    <?php if (isset($san_pham) && !empty($san_pham)): ?>
        <div class="row">
            <div class="col-md-6">
                <img src="TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($san_pham['name']) ?>">
            </div>
            <div class="col-md-6">
                <h2><?= htmlspecialchars($san_pham['name']) ?></h2>
                <p class="price fs-4"><?= number_format($san_pham['price'], 0, ',', '.') ?> VND</p>
                <hr>
                <h4>Mô tả sản phẩm</h4>
                <p><?= !empty($san_pham['description']) ? nl2br(htmlspecialchars($san_pham['description'])) : 'Chưa có mô tả cho sản phẩm này.' ?></p>
                <hr>
                <form action="index.php?act=them_vao_gio" method="post">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($san_pham['id']) ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($san_pham['name']) ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($san_pham['price']) ?>">
                    <input type="hidden" name="image_url" value="<?= htmlspecialchars($san_pham['image_url']) ?>">
                    <button type="submit" class="btn btn-primary btn-lg">Thêm Vào Giỏ</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Không tìm thấy sản phẩm bạn yêu cầu. <a href="index.php?act=hienthi_sp" class="alert-link">Quay lại danh sách sản phẩm</a>.
        </div>
    <?php endif; ?>
</div>
