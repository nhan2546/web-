<?php include "GiaoDien/trang/bo_cuc/dau_trang.php"; ?>
<h2>Tìm Kiếm Sản Phẩm</h2>
<form action="timkiemsanpham.php" method="GET">
    <input type="text" name="keyword" placeholder="Nhập tên sản phẩm...">
    <button type="submit">Tìm Kiếm</button>
</form>
<div class="search-results">
    <h3>Kết Quả Tìm Kiếm: "<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"</h3>
    <p>Không tìm thấy sản phẩm nào.</p>
</div>
<?php include 'GiaoDien/trang/bo_cuc/chan_trang.php'; ?>