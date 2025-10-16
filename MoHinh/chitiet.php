<?php
if (empty($chi_tiet_don_hang) || empty($chi_tiet_don_hang['order_info'])) {
    echo "<p>Không tìm thấy thông tin đơn hàng.</p>";
    exit;
}

$order_info = $chi_tiet_don_hang['order_info'];
$order_items = $chi_tiet_don_hang['order_items'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Đơn hàng #<?= $order_info['id'] ?></title>
    <link rel="stylesheet" href="TaiNguyen/css/style.css">
    <link rel="stylesheet" href="TaiNguyen/css/style.css">
</head>
<body>
    <div class="container">
        <a href="dieukhienquantri.php?act=ds_donhang" class="back-link">&larr; Quay lại danh sách đơn hàng</a>
        
        <h1>Chi tiết Đơn hàng #<?= htmlspecialchars($order_info['id']) ?></h1>

        <div class="order-details-grid">
            <div class="detail-box">
                <h2>Thông tin Đơn hàng</h2>
                <p><strong>Mã đơn hàng:</strong> #<?= htmlspecialchars($order_info['id']) ?></p>
                <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order_info['order_date'])) ?></p>
                <p><strong>Tổng tiền:</strong> <strong style="color: red;"><?= number_format($order_info['total_amount'], 0, ',', '.') ?> VNĐ</strong></p>
                <p><strong>Địa chỉ giao:</strong> <?= htmlspecialchars($order_info['shipping_address']) ?></p>
            </div>
            <div class="detail-box">
                <h2>Thông tin Khách hàng</h2>
                <p><strong>Họ và tên:</strong> <?= htmlspecialchars($order_info['fullname']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order_info['email']) ?></p>
                <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order_info['phone_number']) ?></p>
            </div>
        </div>

        <div class="detail-box">
            <h2>Cập nhật trạng thái</h2>
            <form action="dieukhienquantri.php?act=capnhat_trangthai_donhang" method="POST" class="update-form">
                <input type="hidden" name="order_id" value="<?= $order_info['id'] ?>">
                <select name="status">
                    <?php 
                        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                        foreach ($statuses as $status) {
                            $selected = ($order_info['status'] == $status) ? 'selected' : '';
                            echo "<option value='{$status}' {$selected}>" . ucfirst($status) . "</option>";
                        }
                    ?>
                </select>
                <button type="submit">Cập nhật</button>
            </form>
        </div>

        <h2>Các sản phẩm trong đơn hàng</h2>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td style="width: 80px;">
                            <img src="../../TaiLen/san_pham/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="product-image">
                        </td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= number_format($item['quantity'] * $item['price'], 0, ',', '.') ?> VNĐ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>