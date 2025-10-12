<<<<<<< HEAD
<div class="admin-card">
    <div class="admin-card-header">
        Thêm sản phẩm mới
    </div>
    <div class="admin-card-body">
        <form action="admin.php?act=xl_themsp" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Số lượng kho</label>
                    <input type="number" name="stock_quantity" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($danh_sach_danh_muc as $danh_muc): ?>
                        <option value="<?= $danh_muc['id'] ?>"><?= htmlspecialchars($danh_muc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh sản phẩm</label>
                <input type="file" name="image" class="form-control">
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="admin.php?act=ds_sanpham" class="admin-btn" style="background-color: #6c757d; color: white; margin-right: 10px;">Hủy</a>
                <button type="submit" class="admin-btn admin-btn-primary">Thêm sản phẩm</button>
            </div>
=======
<div class="page-header">
    <h1>Thêm sản phẩm mới</h1>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <!-- 
            - enctype="multipart/form-data" là BẮT BUỘC để có thể upload file.
            - action trỏ đến route xử lý logic thêm sản phẩm.
        -->
        <form action="admin.php?act=xl_themsp" method="POST" enctype="multipart/form-data">
            
            <!-- Tên sản phẩm -->
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <!-- Giá sản phẩm -->
            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" class="form-control" id="price" name="price" step="1000" required>
            </div>

            <!-- Mô tả -->
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
            </div>

            <!-- Hình ảnh -->
            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
            </div>

            <!-- Nút bấm -->
            <button type="submit" class="admin-btn admin-btn-primary">Thêm sản phẩm</button>
            <a href="admin.php?act=ds_sanpham" class="admin-btn">Quay lại danh sách</a>
>>>>>>> afca77cd971748c14b4406f6a402e47fed186b97
        </form>
    </div>
</div>