<?php
// Hiển thị thông báo nếu có
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Xóa thông báo sau khi hiển thị
}
?>
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span>Danh sách Sản phẩm</span>
        <a href="admin.php?act=them_sp" class="admin-btn admin-btn-primary">Thêm sản phẩm mới</a>
    </div>
    <div class="admin-card-body">
        
        <!-- Vùng lọc theo danh mục -->
        <div class="mb-3">
            <form action="admin.php" method="GET" class="d-flex align-items-center">
                <input type="hidden" name="act" value="ds_sanpham">
                <div class="flex-grow-1 me-2">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Lọc theo danh mục --</option>
                        <?php 
                        // Lấy category_id hiện tại từ URL để so sánh
                        $current_category = $_GET['category_id'] ?? null;
                        foreach ($danh_sach_danh_muc as $danh_muc): ?>
                            <option value="<?= $danh_muc['id'] ?>" <?= ($current_category == $danh_muc['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($danh_muc['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (isset($current_category) && $current_category): ?>
                    <a href="admin.php?act=ds_sanpham" class="admin-btn" style="background-color: #6c757d; color: white; text-decoration: none;">Xóa lọc</a>
                <?php endif; ?>
            </form>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Danh mục</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($danh_sach_san_pham)): ?>
                    <?php foreach ($danh_sach_san_pham as $san_pham): ?>
                        <tr>
                            <td><?= htmlspecialchars($san_pham['id']) ?></td>
                            <td>
                                <img src="../TaiLen/san_pham/<?= htmlspecialchars($san_pham['image_url']) ?>" alt="<?= htmlspecialchars($san_pham['name']) ?>" width="60">
                            </td>
                            <td><?= htmlspecialchars($san_pham['name']) ?></td>
                            <td><?= number_format($san_pham['price']) ?> VND</td>
                            <td><?= htmlspecialchars($san_pham['stock_quantity']) ?></td>
                            <td><?= htmlspecialchars($san_pham['category_name'] ?? 'N/A') ?></td>
                            <td class="action-buttons">
                                <a href="admin.php?act=sua_sp&id=<?= $san_pham['id'] ?>" class="admin-btn">Sửa</a>
                                <a href="admin.php?act=xoa_sp&id=<?= $san_pham['id'] ?>" 
                                   class="admin-btn admin-btn-danger" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Không có sản phẩm nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>