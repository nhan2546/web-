<div class="admin-form-container">
    <div class="admin-card">
        <div class="admin-card-header">
            Thêm sản phẩm mới
        </div>
        <div class="admin-card-body">
            <form action="admin.php?act=them_sp" method="POST" enctype="multipart/form-data">
                <div class="form-group-grid">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="form-group-grid">
                    <label class="form-label">Danh mục</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php 
                        foreach ($danh_sach_danh_muc as $danh_muc): ?>
                            <option value="<?= $danh_muc['id'] ?>"><?= htmlspecialchars($danh_muc['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
    
                <div class="form-group-grid">
                    <label class="form-label">Giá (VNĐ)</label>
                    <input type="number" name="price" class="form-control" min="0" placeholder="Ví dụ: 25000000" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Giá khuyến mãi (VNĐ)</label>
                    <input type="number" name="sale_price" class="form-control" min="0" placeholder="Để trống hoặc nhập 0 nếu không giảm giá">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Số lượng kho</label>
                    <input type="number" name="stock_quantity" class="form-control" min="0" placeholder="Ví dụ: 100" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Hình ảnh sản phẩm</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
    
                <div class="form-group-grid">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="admin.php?act=ds_sanpham" class="admin-btn admin-btn-secondary">Hủy</a>
                    <button type="submit" class="admin-btn admin-btn-primary">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>