<?php
// Kiểm tra xem biến $san_pham và $danh_sach_danh_muc có tồn tại không
if (!isset($san_pham) || !isset($danh_sach_danh_muc)) {
    echo "Dữ liệu sản phẩm không hợp lệ.";
    // Có thể chuyển hướng hoặc hiển thị thông báo lỗi chi tiết hơn
    exit;
}
?>
<div class="admin-form-container">
    <div class="admin-card">
        <div class="admin-card-header">
            Chỉnh sửa sản phẩm
        </div>
        <div class="admin-card-body">
            <form action="admin.php?act=xl_suasp" method="POST" enctype="multipart/form-data">
                <!-- Trường ẩn để lưu ID sản phẩm -->
                <input type="hidden" name="id" value="<?= htmlspecialchars($san_pham['id']) ?>">
                <!-- Trường ẩn để lưu tên ảnh hiện tại -->
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($san_pham['image_url']) ?>">

                <div class="form-group-grid">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($san_pham['name']) ?>" required>
                </div>
                
                <div class="form-group-grid">
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
    
                <div class="form-group-grid">
                    <label class="form-label">Giá (VNĐ)</label>
                    <input type="number" name="price" class="form-control" min="0" value="<?= htmlspecialchars($san_pham['price']) ?>" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Giá khuyến mãi (VNĐ)</label>
                    <input type="number" name="sale_price" class="form-control" min="0" value="<?= htmlspecialchars($san_pham['sale_price'] ?? '') ?>" placeholder="Để trống hoặc nhập 0 nếu không giảm giá">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Số lượng kho</label>
                    <input type="number" name="quantity" class="form-control" min="0" value="<?= htmlspecialchars($san_pham['quantity'] ?? 0) ?>" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Thông số sản phẩm</label>
                    <?php
                        // Dữ liệu mẫu cho thông số kỹ thuật
                        $sample_specs = "Màn hình: 6.1 inch, Super Retina XDR, 120Hz\n"
                                      . "Hệ điều hành: iOS 17\n"
                                      . "Camera sau: Chính 48 MP & Phụ 12 MP, 12 MP\n"
                                      . "Camera trước: 12 MP\n"
                                      . "Chip: Apple A17 Pro\n"
                                      . "RAM: 8 GB\n"
                                      . "Dung lượng lưu trữ: 128 GB\n"
                                      . "SIM: 1 Nano SIM & 1 eSIM\n"
                                      . "Pin, Sạc: 20 W";
                    ?>
                    <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars(empty(trim($san_pham['description'])) ? $sample_specs : $san_pham['description']) ?></textarea>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Tính năng nổi bật</label>
                    <textarea name="highlights" class="form-control" rows="4" placeholder="Nhập mỗi tính năng trên một dòng..."><?= htmlspecialchars($san_pham['highlights'] ?? '') ?></textarea>
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
                    <button type="submit" class="admin-btn admin-btn-primary">Cập nhật sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantsContainer = document.getElementById('variants-container');
    const addVariantBtn = document.getElementById('add-variant-btn');

    // Lấy dữ liệu JSON từ PHP và parse nó
    const existingVariants = <?= !empty($san_pham['variants_json']) ? $san_pham['variants_json'] : '[]' ?>;

    let variantIdCounter = 0;

    function createVariantRow(data = {}) {
        const variantId = variantIdCounter++;
        const variantRow = document.createElement('div');
        variantRow.classList.add('variant-item');
        variantRow.innerHTML = `
            <input type="text" name="variant_color[]" placeholder="Tên màu (VD: Xanh)" class="form-control variant-color" value="${data.color || ''}">
            <div class="variant-image-group">
                <input type="file" name="variant_image[]" class="form-control variant-image" accept="image/*">
                ${data.image ? `<small class="form-text">Ảnh hiện tại: ${data.image}</small>` : ''}
                <input type="hidden" name="existing_variant_image[]" value="${data.image || ''}">
            </div>
            <button type="button" class="admin-btn admin-btn-danger remove-variant-btn">Xóa</button>
        `;
        variantsContainer.appendChild(variantRow);
    }

    // Hiển thị các phiên bản đã có
    if (Array.isArray(existingVariants)) {
        existingVariants.forEach(variant => createVariantRow(variant));
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
