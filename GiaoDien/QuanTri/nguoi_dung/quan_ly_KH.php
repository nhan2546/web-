<div class="page-header">
    <h1>Quản lý Khách Hàng</h1>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và Tên</th>
                    <th>Email</th>
                    <th>Tổng chi tiêu</th>
                    <th>Xếp hạng</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danh_sach_khach_hang)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Không có khách hàng nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($danh_sach_khach_hang as $khach_hang): ?>
                    <tr>
                        <td><?php echo $khach_hang['id']; ?></td>
                        <td><?php echo htmlspecialchars($khach_hang['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($khach_hang['email']); ?></td>
                        <td><?php echo number_format($khach_hang['total_spending'], 0, ',', '.'); ?> VNĐ</td>
                        <td>
                            <?php 
                                $rank = getCustomerRank($khach_hang['total_spending']);
                                $rank_class = '';
                                switch ($rank) {
                                    case 'Kim Cương':
                                        $rank_class = 'badge-diamond';
                                        break;
                                    case 'Vàng':
                                        $rank_class = 'badge-gold';
                                        break;
                                    case 'Bạc':
                                        $rank_class = 'badge-silver';
                                        break;
                                    default:
                                        $rank_class = 'badge-copper';
                                        break;
                                }
                                echo "<span class='badge {$rank_class}'>{$rank}</span>";
                            ?>
                        </td>
                        <td>
                            <?php if ($khach_hang['status'] == 'locked'): ?>
                                <span class="badge badge-cancelled">Đã khóa</span>
                            <?php else: ?>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <a href="admin.php?act=toggle_trangthai_khachhang&id=<?= $khach_hang['id'] ?>&status=<?= ($khach_hang['status'] == 'locked' ? 'active' : 'locked') ?>" class="admin-btn admin-btn-warning">
                                <?php if ($khach_hang['status'] == 'locked'): ?>
                                    Mở khóa
                                <?php else: ?>
                                    Khóa
                                <?php endif; ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>