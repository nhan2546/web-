<div class="page-header">
    <h1>Quản lý Bảo hành</h1>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <span>Danh sách Yêu cầu Bảo hành</span>
    </div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Sản phẩm</th>
                    <th>Ngày yêu cầu</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($danh_sach_bao_hanh)): ?>
                    <?php foreach ($danh_sach_bao_hanh as $bao_hanh): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($bao_hanh['id']); ?></td>
                            <td><?php echo htmlspecialchars($bao_hanh['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($bao_hanh['product_name']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($bao_hanh['claim_date'])); ?></td>
                            <td>
                                <?php 
                                    $status_class = 'badge-secondary';
                                    if ($bao_hanh['status'] === 'pending') $status_class = 'badge-pending';
                                    if ($bao_hanh['status'] === 'approved') $status_class = 'badge-processing';
                                    if ($bao_hanh['status'] === 'completed') $status_class = 'badge-success';
                                    if ($bao_hanh['status'] === 'rejected') $status_class = 'badge-cancelled';
                                ?>
                                <span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($bao_hanh['status']); ?></span>
                            </td>
                            <td class="action-buttons">
                                <a href="admin.php?act=ct_baohanh&id=<?php echo $bao_hanh['id']; ?>" class="admin-btn">Xem chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Chưa có yêu cầu bảo hành nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>