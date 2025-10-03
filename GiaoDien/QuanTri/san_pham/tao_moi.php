<?php
// File này chủ yếu là HTML, chỉ cần kết nối CSDL để lấy danh mục
require_once '../../includes/db_connection.php';
// (Tùy chọn) Bạn có thể tạo file category_functions.php để lấy danh mục
$result_categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
    <h2>Thêm sản phẩm mới</h2>
    <form action="process_create.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Giá</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Số lượng kho</label>
                <input type="number" name="stock_quantity" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label>Hình ảnh sản phẩm</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>