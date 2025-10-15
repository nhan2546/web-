<div class="container">
    <h2 class="mb-4">Tìm Kiếm Sản Phẩm</h2>
    <form action="timkiemsanpham.php" method="GET" class="search-form mb-4">
        <input type="text" name="keyword" class="form-control" placeholder="Nhập tên sản phẩm..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
    </form>
    <div class="search-results">
        <h3>Kết Quả Tìm Kiếm cho: "<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"</h3>
        <!-- Logic hiển thị kết quả sẽ ở đây -->
        <p>Không tìm thấy sản phẩm nào.</p>
    </div>
</div>