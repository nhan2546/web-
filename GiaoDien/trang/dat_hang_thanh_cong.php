<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Dữ liệu $chi_tiet_don_hang được truyền từ controller
$order_info = $chi_tiet_don_hang['order_info'];
$order_items = $chi_tiet_don_hang['order_items'];

// Gửi email xác nhận nếu có địa chỉ email
// Lưu ý: Chức năng này hiện tại chỉ hoạt động cho khách hàng đã đăng ký tài khoản
// vì khách vãng lai chưa được yêu cầu nhập email khi thanh toán.
if (isset($order_info['email']) && !empty($order_info['email'])) {
    
    // Load PHPMailer
    require 'vendor/autoload.php';
    $mail = new PHPMailer(true);

    try {
        // Cấu hình server
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nho265007@mail.com'; // Email của bạn
        $mail->Password   = 'xykehewjkfgelynp';   // Mật khẩu ứng dụng của bạn
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        // Người nhận
        $mail->setFrom('nho265007@mail.com', 'Web-cua-ban'); // Thay 'Web-cua-ban' bằng tên cửa hàng của bạn
        $mail->addAddress($order_info['email'], $order_info['fullname']);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Xác nhận đơn hàng thành công #' . htmlspecialchars($order_info['id']);
        
        // Tạo thân email
        $email_body = '<div style="font-family: Arial, sans-serif; line-height: 1.6;">';
        $email_body .= "<h2>Cảm ơn bạn đã đặt hàng tại Web-cua-ban!</h2>";
        $email_body .= "<p>Xin chào <strong>" . htmlspecialchars($order_info['fullname']) . "</strong>,</p>";
        $email_body .= "<p>Chúng tôi đã nhận được đơn hàng của bạn. Dưới đây là chi tiết đơn hàng:</p>";
        $email_body .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
        $email_body .= "<tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Mã đơn hàng:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>#" . htmlspecialchars($order_info['id']) . "</td></tr>";
        $email_body .= "<tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Ngày đặt:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>" . date('d/m/Y H:i', strtotime($order_info['order_date'])) . "</td></tr>";
        $email_body .= "<tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Tổng tiền:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'><strong>" . number_format($order_info['total_amount'], 0, ',', '.') . "₫</strong></td></tr>";
        $email_body .= "<tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Địa chỉ giao hàng:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($order_info['shipping_address']) . "</td></tr>";
        $email_body .= '</table>';
        
        $email_body .= "<h3>Chi tiết sản phẩm:</h3>";
        $email_body .= '<table style="width: 100%; border-collapse: collapse;">
                            <thead style="background-color: #f2f2f2;">
                                <tr>
                                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Tên sản phẩm</th>
                                    <th style="padding: 8px; border: 1px solid #ddd; text-align: center;">Số lượng</th>
                                    <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Giá</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach ($order_items as $item) {
            $email_body .= "<tr>
                                <td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($item['product_name']) . "</td>
                                <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>" . htmlspecialchars($item['quantity']) . "</td>
                                <td style='padding: 8px; border: 1px solid #ddd; text-align: right;'>" . number_format($item['price'], 0, ',', '.') . "₫</td>
                            </tr>";
        }
        $email_body .= '</tbody></table>';
        $email_body .= '<p style="margin-top: 20px;">Chúng tôi sẽ xử lý và giao đơn hàng của bạn trong thời gian sớm nhất.</p>';
        $email_body .= '<p>Cảm ơn bạn đã tin tưởng và mua sắm!</p></div>';

        $mail->Body = $email_body;
        $mail->AltBody = strip_tags($email_body); // Fallback for non-HTML clients

        $mail->send();
    } catch (Exception $e) {
        // Không gửi được email. Có thể ghi log lỗi để debug, nhưng không làm gián đoạn người dùng.
        // error_log("Mailer Error: {$mail->ErrorInfo}");
    }
}
?>

<div class="order-success-page-wrapper">
    <div class="order-success-card">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
        </div>
        <h1 class="success-title">Đặt hàng thành công!</h1>
        <p class="success-message">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận.</p>

        <div class="order-summary-details">
            <div class="summary-header">
                <p>Mã đơn hàng của bạn là: <strong>#<?= htmlspecialchars($order_info['id']) ?></strong></p>
            </div>
            <div class="summary-body">
                <div class="summary-row">
                    <span>Ngày đặt hàng:</span>
                    <span><?= date('d/m/Y H:i', strtotime($order_info['order_date'])) ?></span>
                </div>
                <div class="summary-row">
                    <span>Giao đến:</span>
                    <span class="text-end">
                        <?= htmlspecialchars($order_info['fullname']) ?><br>
                     <?= htmlspecialchars($order_info['shipping_address'] ?? '') ?>
                    </span>
                </div>
                <div class="summary-row">
                    <span>Tổng tiền:</span>
                    <span class="total-price"><?= number_format($order_info['total_amount'], 0, ',', '.') ?>₫</span>
                </div>
            </div>
        </div>

        <div class="next-steps">
            <p>Chúng tôi sẽ gửi một email xác nhận với chi tiết đơn hàng của bạn trong ít phút.</p>
            <p>Bạn có thể theo dõi trạng thái đơn hàng trong trang tài khoản.</p>
        </div>

        <div class="action-buttons">
            <a href="index.php?act=chi_tiet_don_hang&id=<?= $order_info['id'] ?>" class="cp-btn">Xem chi tiết đơn hàng</a>
            <a href="index.php?act=hienthi_sp" class="cp-btn cp-btn-secondary">Tiếp tục mua sắm</a>
        </div>
    </div>
</div>