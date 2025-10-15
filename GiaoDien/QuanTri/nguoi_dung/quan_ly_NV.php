<div class="page-header d-flex justify-content-between align-items-center">
    <h1>Quản lý Nhân Viên</h1>
    <a href="admin.php?act=them_nv" class="admin-btn admin-btn-primary">Thêm Nhân Viên Mới</a>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <!-- Form tìm kiếm -->
        <div class="mb-4">
            <form action="admin.php" method="GET" class="d-flex">
                <input type="hidden" name="act" value="ds_nhanvien">
                <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tên hoặc email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="admin-btn admin-btn-primary" style="white-space: nowrap;">Tìm kiếm</button>
                <?php if (!empty($_GET['search'])): ?>
                    <a href="admin.php?act=ds_nhanvien" class="admin-btn admin-btn-secondary ms-2" style="white-space: nowrap;">Xóa tìm kiếm</a>
                <?php endif; ?>
            </form>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach_nhan_vien)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Không có nhân viên nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($danh_sach_nhan_vien as $nhan_vien): ?>
                    <tr>
                        <td><?php echo $nhan_vien['id']; ?></td>
                        <td><?php echo htmlspecialchars($nhan_vien['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($nhan_vien['email']); ?></td>
                        <td><span class="badge badge-secondary"><?php echo htmlspecialchars($nhan_vien['role']); ?></span></td>
                        <td>
                            <?php if ($nhan_vien['status'] == 'locked'): ?>
                                <span class="badge badge-cancelled">Đã khóa</span>
                            <?php else: ?>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($nhan_vien['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="admin.php?act=sua_nhanvien&id=<?php echo $nhan_vien['id']; ?>" class="admin-btn admin-btn-secondary">Sửa</a>
                            <?php if ($nhan_vien['id'] != $_SESSION['user_id']): // Không cho phép thao tác với chính mình ?>
                                <a href="admin.php?act=toggle_trangthai_khachhang&id=<?php echo $nhan_vien['id']; ?>&status=<?php echo ($nhan_vien['status'] == 'locked' ? 'active' : 'locked'); ?>" class="admin-btn admin-btn-warning">
                                    <?php if ($nhan_vien['status'] == 'locked'): ?>
                                        Mở khóa
                                    <?php else: ?>
                                        Khóa
                                    <?php endif; ?>
                                </a>
                                <a href="admin.php?act=xoa_nhanvien&id=<?php echo $nhan_vien['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');" class="admin-btn admin-btn-danger">Xóa</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Phân trang -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4 d-flex justify-content-center">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="admin.php?act=ds_nhanvien&page=<?= $page - 1 ?>&search=<?= urlencode($search_term) ?>">Trước</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="admin.php?act=ds_nhanvien&page=<?= $i ?>&search=<?= urlencode($search_term) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="admin.php?act=ds_nhanvien&page=<?= $page + 1 ?>&search=<?= urlencode($search_term) ?>">Sau</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>