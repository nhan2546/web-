<?php include "header.php"; ?>
<h2>Tiềm Kiếm Sản Phẩm</h2>
<form action="search.php" method="GET">
    <input type="text" name="keyworn" placeholder="Nhập tên sản phẩm...">
    <button type="submit">Tìm Kiếm</button>
    </form>
    <div class="search-results">
        <h3>Kết Quả Tìm Kiếm: "<?php echo isset($_GET['keyworn']) ? htmlspecialchars($_GET['keyworn']) : ''; ?>"</h3>
        <p>Không tìm thấy sản phẩm nào.</p>
        </div>
    <?php include 'footer.php'; ?>