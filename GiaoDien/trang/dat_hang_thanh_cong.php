<div class="container text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-success">Cảm ơn bạn đã đặt hàng!</h1>
                    <p class="card-text">Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
                    <?php if (isset($order_id) && $order_id > 0): ?>
                        <p class="card-text">Mã đơn hàng của bạn là: <strong>#<?php echo htmlspecialchars($order_id); ?></strong></p>
                    <?php endif; ?>
                    <p class="card-text">Bạn có thể xem lại chi tiết đơn hàng trong <a href="index.php?act=lich_su_mua_hang">lịch sử mua hàng</a>.</p>
                    <a href="index.php?act=trangchu" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    </div>
</div>