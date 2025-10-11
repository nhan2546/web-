<?php
// Lấy token từ URL để điền vào form
$token = $_GET['token'] ?? '';

// Kiểm tra xem token có tồn tại không, nếu không thì không cho phép đặt lại
if (empty($token)) {
    // Bạn có thể chuyển hướng về trang chủ hoặc hiển thị thông báo lỗi
    echo '<div class="container mt-5"><div class="alert alert-danger">Token không hợp lệ hoặc đã hết hạn.</div></div>';
    // Dừng kịch bản ở đây
    return;
}
?>

<div class="auth-page-wrapper">
    <div class="auth-form-container">
        <h3 class="auth-form-title">Tạo Mật Khẩu Mới</h3>
        <p class="text-center text-muted mb-4">Vui lòng nhập mật khẩu mới của bạn.</p>
        
        <?php
            // Hiển thị thông báo lỗi nếu có
            if (isset($_GET['error'])) {
                $error_msg = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
                if ($_GET['error'] == 'invalid_data') $error_msg = 'Mật khẩu không được để trống và phải khớp nhau.';
                if ($_GET['error'] == 'invalid_token') $error_msg = 'Yêu cầu đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.';
                echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
            }
        ?>

        <form action="index.php?act=xu_ly_dat_lai_mat_khau" method="POST" id="reset-password-form" novalidate>
            <!-- Trường ẩn để gửi token -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="mb-3" style="position: relative;">
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu mới">
                <div class="invalid-feedback">Vui lòng nhập mật khẩu mới.</div>
            </div>
            <div class="mb-3" style="position: relative;">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu mới">
                <div class="invalid-feedback">Mật khẩu xác nhận không khớp.</div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Đặt Lại Mật Khẩu</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('reset-password-form');
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');

    form.addEventListener('submit', function (event) {
        // Ngăn form gửi đi ngay lập tức để kiểm tra
        event.preventDefault();

        // Reset trạng thái lỗi
        password.classList.remove('is-invalid');
        confirm_password.classList.remove('is-invalid');

        let isValid = true;

        // 1. Kiểm tra mật khẩu mới có trống không
        if (password.value.trim() === '') {
            password.classList.add('is-invalid');
            password.nextElementSibling.textContent = 'Vui lòng nhập mật khẩu mới.';
            isValid = false;
        }

        // 2. Kiểm tra xác nhận mật khẩu có trống không
        if (confirm_password.value.trim() === '') {
            confirm_password.classList.add('is-invalid');
            confirm_password.nextElementSibling.textContent = 'Vui lòng xác nhận mật khẩu mới.';
            isValid = false;
        }

        // 3. Kiểm tra hai mật khẩu có khớp nhau không (chỉ khi cả hai đều không trống)
        if (password.value.trim() !== '' && confirm_password.value.trim() !== '' && password.value !== confirm_password.value) {
            confirm_password.classList.add('is-invalid');
            confirm_password.nextElementSibling.textContent = 'Mật khẩu xác nhận không khớp.';
            isValid = false;
        }

        // Nếu tất cả đều hợp lệ, gửi form đi
        if (isValid) {
            form.submit();
        }
    });
});
</script>
