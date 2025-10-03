<div class="container mt-3">
    <?php
    // Bắt đầu session ở đầu mỗi tệp cần sử dụng session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Hiển thị thông báo lỗi nếu có
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
        // Xóa thông báo sau khi đã hiển thị để không hiện lại
        unset($_SESSION['error_message']);
    }

    // Hiển thị thông báo thành công nếu có
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    ?>
</div>
<?php 
    // Giả sử bạn có tệp header.php và footer.php trong thư mục /pages
    // Nếu chưa có, bạn có thể tạo chúng hoặc xóa 2 dòng include đi
    include 'pages/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Đăng ký tài khoản</h3>
                </div>
                <div class="card-body">
                    <form action="process_register.php" method="POST">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Đăng ký</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Đã có tài khoản? <a href="đăng_nhập.php">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include 'pages/footer.php'; 
?>