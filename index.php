<?php include 'GiaoDien/trang/bo_cuc/dau_trang.php'; ?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Trang chủ - ClickShop (frontend)</title>
  <link rel="stylesheet" href="TaiNguyen/css/style.css">

</head>
<body>
<header class="topbar">
  <div class="container">
    <a class="brand" href="index.html">CLICKSHOP</a>
    <form class="search" onsubmit="gotoSearch(event)">
      <input id="q" placeholder="Bạn cần tìm gì...">
      <button>Tìm</button>
    </form>
    <nav class="nav">
      <a href="trang/danh_sach_san_pham.html">iPhone</a>
      <a href="#" onclick="alert('Demo frontend');return false;">Giỏ hàng</a>
    </nav>
  </div>
</header>
<main class="container">
  <h1>Chào mừng đến Shop </h1>
  <p>Xem <a href="trang/danh_sach_san_pham.html">danh sách iPhone</a> hoặc thử ô tìm kiếm.</p>
</main>
<footer class="footer"><div class="container">© Demo frontend</div></footer>
<script>
function gotoSearch(e){
  e.preventDefault();
  const v = document.getElementById('q').value || '';
  location.href = 'timkiemsanpham.html?q=' + encodeURIComponent(v);
}
</script>
</body>
</html>
<?php include 'GiaoDien/trang/bo_cuc/chan_trang.php'; ?>