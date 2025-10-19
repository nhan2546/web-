<div class="admin-form-container">
    <div class="admin-card">
        <div class="admin-card-header">
            Thêm Voucher mới
        </div>
        <div class="admin-card-body">
            <?php if (isset($_GET['error']) && $_GET['error'] === 'code_exists'): ?>
                <div class="alert alert-danger">Mã voucher này đã tồn tại. Vui lòng chọn một mã khác.</div>
            <?php endif; ?>

            <form action="admin.php?act=xl_them_voucher" method="POST">
                <div class="form-group-grid">
                    <label class="form-label">Mã Voucher</label>
                    <input type="text" name="code" class="form-control" placeholder="Ví dụ: HE2024" required style="text-transform: uppercase;">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Mô tả</label>
                    <input type="text" name="description" class="form-control" placeholder="Ví dụ: Giảm giá mừng hè">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Loại giảm giá</label>
                    <select name="discount_type" class="form-select">
                        <option value="fixed">Số tiền cố định (VNĐ)</option>
                        <option value="percentage">Phần trăm (%)</option>
                    </select>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Giá trị giảm</label>
                    <input type="number" name="discount_value" class="form-control" min="0" required placeholder="Nhập số tiền hoặc phần trăm">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Đơn hàng tối thiểu (VNĐ)</label>
                    <input type="number" name="min_order_amount" class="form-control" min="0" value="0" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Giới hạn lượt sử dụng</label>
                    <input type="number" name="usage_limit" class="form-control" min="1" placeholder="Để trống nếu không giới hạn">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Ngày hết hạn</label>
                    <input type="datetime-local" name="expires_at" class="form-control">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Trạng thái</label>
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Kích hoạt
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="admin.php?act=ds_voucher" class="admin-btn admin-btn-secondary">Hủy</a>
                    <button type="submit" class="admin-btn admin-btn-primary">Thêm Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>