<div class="page-header">
    <h1>Quản lý Danh mục</h1>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <a href="admin.php?act=them_danhmuc" class="admin-btn admin-btn-primary">Thêm Danh mục mới</a>
    </div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Danh mục</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($danh_sach_danh_muc as $danh_muc): ?>
                <tr>
                    <td><?php echo $danh_muc['id']; ?></td>
                    <td><?php echo htmlspecialchars($danh_muc['name']); ?></td>
                    <td class="action-buttons">
                        <a href="admin.php?act=sua_danhmuc&id=<?php echo $danh_muc['id']; ?>" class="admin-btn">Sửa</a>
                        <a href="admin.php?act=xoa_danhmuc&id=<?php echo $danh_muc['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" class="admin-btn admin-btn-danger">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>