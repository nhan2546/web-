<?php
// Lấy chữ cái đầu của tên để làm avatar mặc định
$first_letter = mb_substr($user_info['fullname'], 0, 1, 'UTF-8');
?>

<div class="account-page-wrapper">
    <div class="account-dashboard-header">
        <div class="account-dashboard-avatar">
            <?php if (!empty($user_info['avatar_url'])): ?>
                <img src="TaiLen/avatars/<?= htmlspecialchars($user_info['avatar_url']) ?>" alt="Avatar">
            <?php else: ?>
                <span><?= htmlspecialchars(strtoupper($first_letter)) ?></span>
            <?php endif; ?>
        </div>
        <div class="account-dashboard-greeting">
            <p>Xin chào,</p>
            <div class="greeting-name-rank">
                <h3><?= htmlspecialchars($user_info['fullname']) ?></h3>
                <span class="customer-rank-badge <?= $rank_info['class'] ?>"><?= $rank_info['rank'] ?></span>
            </div>
        </div>
    </div>

    <!-- Khu vực tiến trình nâng hạng -->
    <div class="rank-progress-container">
        <div class="rank-progress-bar">
            <div class="rank-progress-bar-fill" style="width: <?= $rank_info['progress_percentage'] ?>%;"></div>
        </div>
        <div class="rank-progress-info">
            <?php if ($rank_info['next_rank']): ?>
                <p>Chi tiêu thêm <strong><?= number_format($rank_info['needed_for_next'], 0, ',', '.') ?>₫</strong> để lên hạng <strong><?= $rank_info['next_rank'] ?></strong></p>
            <?php else: ?>
                <p>🎉 Chúc mừng! Bạn đã đạt hạng cao nhất.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="account-dashboard-grid">
        <!-- Card: Thông tin tài khoản -->
        <a href="index.php?act=chinh_sua_thong_tin" class="account-dashboard-card">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="card-info">
                <h4>Thông tin tài khoản</h4>
                <p>Chỉnh sửa thông tin cá nhân, email, số điện thoại của bạn.</p>
            </div>
        </a>

        <!-- Card: Lịch sử mua hàng -->
        <a href="index.php?act=lich_su_mua_hang" class="account-dashboard-card">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </div>
            <div class="card-info">
                <h4>Lịch sử mua hàng</h4>
                <p>Theo dõi và quản lý các đơn hàng đã đặt.</p>
            </div>
        </a>

        <!-- Card: Đăng xuất -->
        <a href="index.php?act=dang_xuat" class="account-dashboard-card">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </div>
            <div class="card-info">
                <h4>Đăng xuất</h4>
                <p>Kết thúc phiên đăng nhập của bạn.</p>
            </div>
        </a>
    </div>
</div>