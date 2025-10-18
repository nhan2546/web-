<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span>Danh sách Sản phẩm</span>
        <a href="admin.php?act=them_sp" class="admin-btn admin-btn-primary">Thêm sản phẩm mới</a>
    </div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
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
                            <td><?= htmlspecialchars($san_pham['name']) ?></td>
                            <td><?= number_format($san_pham['price']) ?> VNĐ</td>
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
                        <td colspan="6" class="text-center">Không có sản phẩm nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>