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
        </form>
    </div>
</div>