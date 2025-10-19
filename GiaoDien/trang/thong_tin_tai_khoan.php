<div class="account-page-wrapper">
    <h1 class="text-center mb-5">Tài khoản của tôi</h1>

    <!-- Hiển thị thông báo thành công/lỗi -->
    <?php
    $success_msg = '';
    $error_msg = '';
    if (isset($_GET['success'])) {
        switch ($_GET['success']) {
            case '1': $success_msg = 'Cập nhật thông tin tài khoản thành công!'; break;
            case 'password_changed': $success_msg = 'Đổi mật khẩu thành công!'; break;
            case 'avatar_updated': $success_msg = 'Cập nhật ảnh đại diện thành công!'; break;
        }
    }
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'password_mismatch': $error_msg = 'Mật khẩu mới không khớp.'; break;
            case 'wrong_password': $error_msg = 'Mật khẩu hiện tại không đúng.'; break;
            case 'avatar_upload_failed': $error_msg = 'Tải ảnh đại diện lên thất bại.'; break;
        }
    }
    ?>
    <?php if ($success_msg): ?>
        <div class="alert alert-success mb-4"><?= $success_msg ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger mb-4"><?= $error_msg ?></div>
    <?php endif; ?>

    <div class="account-layout">
        <!-- Cột trái: Menu điều hướng -->
        <aside class="account-sidebar">
            <div class="account-user-info">
                <div class="account-avatar">
                    <?php if (!empty($user_info['avatar_url'])): ?>
                        <img src="TaiLen/avatars/<?= htmlspecialchars($user_info['avatar_url']) ?>" alt="Avatar">
                    <?php else: ?>
                        <span><?= htmlspecialchars(strtoupper(mb_substr($user_info['fullname'], 0, 1, 'UTF-8'))) ?></span>
                    <?php endif; ?>
                </div>
                <div class="user-details">
                    <p>Tài khoản của</p>
                    <strong><?= htmlspecialchars($user_info['fullname']) ?></strong>
                </div>
            </div>
            <nav class="account-nav">
                <a href="index.php?act=thong_tin_tai_khoan" class="account-nav-item active"><i class="fas fa-user"></i><span>Thông tin chung</span></a>
                <a href="index.php?act=lich_su_mua_hang" class="account-nav-item"><i class="fas fa-receipt"></i><span>Lịch sử mua hàng</span></a>
                <a href="index.php?act=chinh_sua_thong_tin" class="account-nav-item"><i class="fas fa-edit"></i><span>Chỉnh sửa thông tin</span></a>
                <a href="index.php?act=dang_xuat" class="account-nav-item logout"><i class="fas fa-sign-out-alt"></i><span>Đăng xuất</span></a>
            </nav>
        </aside>

        <!-- Cột phải: Nội dung chính -->
        <main class="account-content">
            <!-- Thanh tiến trình hạng thành viên -->
            <div class="rank-progress-container">
                <div class="rank-header">
                    <div class="greeting-name-rank">
                        <span class="customer-rank-badge <?= htmlspecialchars($rank_info['class']) ?>"><?= htmlspecialchars($rank_info['rank']) ?></span>
                    </div>
                    <div class="total-spending">Tổng chi tiêu: <strong><?= number_format($total_spending, 0, ',', '.') ?>₫</strong></div>
                </div>
                <div class="rank-progress-bar">
                    <div class="rank-progress-bar-fill" style="width: <?= $rank_info['progress_percentage'] ?>%;"></div>
                </div>
                <div class="rank-progress-info">
                    <?php if ($rank_info['next_rank']): ?>
                        <p>Bạn cần chi tiêu thêm <strong><?= number_format($rank_info['needed_for_next'], 0, ',', '.') ?>₫</strong> để đạt hạng <strong><?= $rank_info['next_rank'] ?></strong>.</p>
                    <?php else: ?>
                        <p>Chúc mừng! Bạn đã đạt hạng cao nhất.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lưới các thẻ chức năng -->
            <div class="account-dashboard-grid">
                <a href="index.php?act=chinh_sua_thong_tin" class="account-dashboard-card">
                    <div class="card-icon"><i class="fas fa-edit"></i></div>
                    <div class="card-info">
                        <h4>Thông tin cá nhân</h4>
                        <p>Xem và chỉnh sửa thông tin cá nhân, địa chỉ.</p>
                    </div>
                </a>
                <a href="index.php?act=lich_su_mua_hang" class="account-dashboard-card">
                    <div class="card-icon"><i class="fas fa-receipt"></i></div>
                    <div class="card-info">
                        <h4>Lịch sử mua hàng</h4>
                        <p>Theo dõi trạng thái và chi tiết các đơn hàng đã đặt.</p>
                    </div>
                </a>
            </div>
        </main>
    </div>
</div>