<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="TaiNguyen/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Quản lý Đơn hàng</h1>

        <table>
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Tên Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($danh_sach_don_hang)): ?>
                    <?php foreach ($danh_sach_don_hang as $don_hang): ?>
                        <tr>
                            <td>#<?= $don_hang['id'] ?></td>
                            <td><?= htmlspecialchars($don_hang['customer_name']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($don_hang['order_date'])) ?></td>
                            <td><?= number_format($don_hang['total_amount'], 0, ',', '.') ?> VNĐ</td>
                            <td>
                                <?php
                                    $status = htmlspecialchars($don_hang['status']);
                                    $badge_class = 'badge-' . strtolower($status);
                                    // Thẻ span bây giờ chỉ cần in ra class và nội dung
                                    echo "<span class='badge {$badge_class}'>{$status}</span>";
                                ?>
                            </td>
                            <td>
                                <a href="dieukhienquantri.php?act=ct_donhang&id=<?= $don_hang['id'] ?>" class="btn btn-info">Xem</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>