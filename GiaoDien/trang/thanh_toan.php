<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Thông tin thanh toán</h2>
            <form action="index.php?act=xu_ly_dat_hang" method="POST" id="payment-form" novalidate>
                <div class="mb-3">
                    <label for="FullName" class="form-label">Họ Tên:</label>
                    <input type="text" id="FullName" name="FullName" class="form-control" required>
                    <div class="invalid-feedback">Vui lòng nhập họ tên của bạn.</div>
                </div>
                <div class="mb-3">
                    <label for="Address" class="form-label">Địa Chỉ:</label>
                    <input type="text" id="Address" name="Address" class="form-control" required>
                    <div class="invalid-feedback">Vui lòng nhập địa chỉ nhận hàng.</div>
                </div>
                <div class="mb-3">
                    <label for="Phone" class="form-label">Số Điện Thoại:</label>
                    <input type="text" id="Phone" name="Phone" class="form-control" required>
                    <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Xác Nhận Đặt Hàng</button>
            </form>
        </div>
    </div>
</div>