<div class="auth-page-wrapper">
    <div class="auth-form-container">
        <h3 class="auth-form-title">Đặt Lại Mật Khẩu</h3>
        <p class="text-center text-muted mb-4">Nhập email của bạn để nhận hướng dẫn đặt lại mật khẩu.</p>
        
        <!-- TODO: Thêm logic xử lý thông báo lỗi/thành công ở đây -->

        <form action="index.php?act=xu_ly_quen_mat_khau" method="POST" id="forgot-password-form" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="forgot-email" name="email" required>
                <div class="invalid-feedback">Vui lòng nhập một địa chỉ email hợp lệ.</div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Gửi Mã</button>
            </div>
        </form>
        <div class="auth-form-footer text-center">
            <p>Đã nhớ lại mật khẩu? <a href="index.php?act=dang_nhap">Đăng nhập</a></p>
        </div>
    </div>
</div>