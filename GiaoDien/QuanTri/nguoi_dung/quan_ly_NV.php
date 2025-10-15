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

        <!-- Giao diện lưới thẻ mới -->
        <div class="admin-grid">
            <?php if (empty($danh_sach_nhan_vien)): ?>
                <p class="text-center w-100">Không có nhân viên nào khớp với tìm kiếm của bạn.</p>
            <?php else: ?>
                <?php foreach ($danh_sach_nhan_vien as $nhan_vien): ?>
                <div class="admin-user-card">
                    <div class="user-card-info">
                        <h5 class="user-card-name"><?= htmlspecialchars($nhan_vien['fullname']) ?></h5>
                        <p class="user-card-email"><?= htmlspecialchars($nhan_vien['email']) ?></p>
                        <div class="user-card-badges">
                            <span class="badge badge-role-<?= htmlspecialchars($nhan_vien['role']) ?>"><?= ucfirst(htmlspecialchars($nhan_vien['role'])) ?></span>
                            <?php if ($nhan_vien['status'] == 'locked'): ?>
                                <span class="badge badge-cancelled">Đã khóa</span>
                            <?php else: ?>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="user-card-actions">
                        <a href="admin.php?act=sua_nhanvien&id=<?= $nhan_vien['id'] ?>" class="admin-btn admin-btn-secondary">Sửa</a>
                        <?php if ($nhan_vien['id'] != $_SESSION['user_id']): // Không cho phép thao tác với chính mình ?>
                            <a href="admin.php?act=toggle_trangthai_khachhang&id=<?= $nhan_vien['id'] ?>&status=<?= ($nhan_vien['status'] == 'locked' ? 'active' : 'locked') ?>" class="admin-btn admin-btn-warning">
                                <?= ($nhan_vien['status'] == 'locked') ? 'Mở khóa' : 'Khóa' ?>
                            </a>
                            <a href="admin.php?act=xoa_nhanvien&id=<?= $nhan_vien['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');" class="admin-btn admin-btn-danger">Xóa</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

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