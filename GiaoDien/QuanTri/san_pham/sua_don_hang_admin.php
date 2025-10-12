<div class="admin-card">
    <div class="admin-card-header">
        Cập nhật sản phẩm
    </div>
    <div class="admin-card-body">
        <form action="admin.php?act=xl_suasp" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($san_pham['id']) ?>">
            
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($san_pham['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($san_pham['description']) ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($san_pham['price']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Số lượng kho</label>
                    <input type="number" name="stock_quantity" class="form-control" value="<?= htmlspecialchars($san_pham['stock_quantity']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($danh_sach_danh_muc as $danh_muc): ?>
                        <option value="<?= $danh_muc['id'] ?>" <?= ($danh_muc['id'] == $san_pham['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($danh_muc['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh sản phẩm</label>
                <input type="file" name="image" class="form-control">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($san_pham['image_url']) ?>">
                <?php if (!empty($san_pham['image_url'])): ?>
                    <div class="mt-2">
                        <img src="../TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="Ảnh hiện tại" width="100">
                        <p class="form-text">Ảnh hiện tại. Chọn file mới để thay thế.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="admin.php?act=ds_sanpham" class="admin-btn" style="background-color: #6c757d; color: white; margin-right: 10px;">Hủy</a>
                <button type="submit" class="admin-btn admin-btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>