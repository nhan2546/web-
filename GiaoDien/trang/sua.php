<div class="admin-form-container">
    <div class="admin-card">
        <div class="admin-card-header">
            Chỉnh sửa Voucher
        </div>
        <div class="admin-card-body">
            <form action="admin.php?act=xl_sua_voucher" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($voucher['id']) ?>">

                <div class="form-group-grid">
                    <label class="form-label">Mã Voucher</label>
                    <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($voucher['code']) ?>" required style="text-transform: uppercase;">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Mô tả</label>
                    <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($voucher['description']) ?>">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Loại giảm giá</label>
                    <select name="discount_type" class="form-select">
                        <option value="fixed" <?= ($voucher['discount_type'] == 'fixed') ? 'selected' : '' ?>>Số tiền cố định (VNĐ)</option>
                        <option value="percentage" <?= ($voucher['discount_type'] == 'percentage') ? 'selected' : '' ?>>Phần trăm (%)</option>
                    </select>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Giá trị giảm</label>
                    <input type="number" name="discount_value" class="form-control" min="0" value="<?= htmlspecialchars($voucher['discount_value']) ?>" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Đơn hàng tối thiểu (VNĐ)</label>
                    <input type="number" name="min_order_amount" class="form-control" min="0" value="<?= htmlspecialchars($voucher['min_order_amount']) ?>" required>
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Giới hạn lượt sử dụng</label>
                    <input type="number" name="usage_limit" class="form-control" min="1" value="<?= htmlspecialchars($voucher['usage_limit'] ?? '') ?>" placeholder="Để trống nếu không giới hạn">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Ngày hết hạn</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="<?= !empty($voucher['expiry_date']) ? date('Y-m-d\TH:i', strtotime($voucher['expiry_date'])) : '' ?>">
                </div>

                <div class="form-group-grid">
                    <label class="form-label">Trạng thái</label>
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?= $voucher['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Kích hoạt
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="admin.php?act=ds_voucher" class="admin-btn admin-btn-secondary">Hủy</a>
                    <button type="submit" class="admin-btn admin-btn-primary">Cập nhật Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>