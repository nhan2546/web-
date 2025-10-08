<div class="container">
    <h2 class="mb-4">Lịch Sử Mua Hàng</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Ngày Đặt</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_don_hang)): ?>
                <tr>
                    <td colspan="5" class="text-center">Bạn chưa có đơn hàng nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danh_sach_don_hang as $don_hang): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($don_hang['id']) ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($don_hang['order_date']))) ?></td>
                        <td><?= number_format($don_hang['total_amount'], 0, ',', '.') ?> VND</td>
                        <td>
                            <?php
                                $status_class = 'badge-secondary'; // Mặc định
                                if ($don_hang['status'] == 'processing') $status_class = 'badge-processing';
                                if ($don_hang['status'] == 'shipped') $status_class = 'badge-shipped';
                                if ($don_hang['status'] == 'delivered') $status_class = 'badge-delivered';
                                if ($don_hang['status'] == 'cancelled') $status_class = 'badge-cancelled';
                            ?>
                            <span class="badge <?= $status_class ?>"><?= htmlspecialchars($don_hang['status']) ?></span>
                        </td>
                        <td>
                            <a href="index.php?act=chi_tiet_don_hang&id=<?= $don_hang['id'] ?>" class="btn btn-sm btn-info">Xem Chi Tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>