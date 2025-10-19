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
                    <input type="number" name="quantity" class="form-control" min="0" placeholder="Ví dụ: 100" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Ảnh đại diện chính</label>
                    <input type="file" name="image_url" class="form-control" accept="image/*">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Thông số sản phẩm</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Tính năng nổi bật</label>
                    <textarea name="highlights" class="form-control" rows="4" placeholder="Nhập mỗi tính năng trên một dòng..."></textarea>
                </div>

                <!-- Khu vực quản lý phiên bản màu sắc -->
                <div class="form-group-grid">
                    <label class="form-label">Các phiên bản màu sắc</label>
                    <div>
                        <div id="variants-container" class="variants-list">
                            <!-- Các phiên bản sẽ được thêm vào đây bằng JS -->
                        </div>
                        <button type="button" id="add-variant-btn" class="admin-btn admin-btn-secondary mt-2">Thêm phiên bản</button>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="admin.php?act=ds_sanpham" class="admin-btn admin-btn-secondary">Hủy</a>
                    <button type="submit" class="admin-btn admin-btn-primary">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantsContainer = document.getElementById('variants-container');
    const addVariantBtn = document.getElementById('add-variant-btn');

    let variantIdCounter = 0;

    function createVariantRow(data = {}) {
        const variantId = variantIdCounter++;
        const variantRow = document.createElement('div');
        variantRow.classList.add('variant-item');
        variantRow.innerHTML = `
            <input type="text" name="variant_color[]" placeholder="Tên màu (VD: Xanh)" class="form-control variant-color" value="">
            <input type="file" name="variant_image[]" class="form-control variant-image" accept="image/*">
            <button type="button" class="admin-btn admin-btn-danger remove-variant-btn">Xóa</button>
        `;
        variantsContainer.appendChild(variantRow);
    }

    addVariantBtn.addEventListener('click', function() {
        createVariantRow();
    });

    variantsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant-btn')) {
            e.target.closest('.variant-item').remove();
        }
    });
});
</script>