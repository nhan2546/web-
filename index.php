
<?php include 'GiaoDien/trang/bo_cuc/dau_trang.php'; ?>
 <main>
        <h2>Chào Mừng Đến Shop!</h2>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Trang chủ - ClickShop</title>
  <link rel="stylesheet" href="TaiNguyen/css/style.css">

</head>
<body>
<header class="topbar">
  <div class="container">
    <a class="brand" href="index.php">CLICKSHOP</a>
    <form class="search" onsubmit="gotoSearch(event)">
      <input id="q" placeholder="Bạn cần tìm gì...">
      <button>Tìm</button>
    </form>
    <nav class="nav">
      <a href="timkiemsanpham.php">Tìm kiếm sản phẩm</a>
      <a href="GiaoDien/trang/gio_hang.php">Giỏ hàng</a>
    </nav>
  </div>
</header>
</script>
</body>
</html>
<?php

// Load the CSDL class definition (creates a PDO connection)
require_once __DIR__ . '/MoHinh/CSDL.php';
// Create database connection and get PDO
$db = new CSDL();
$pdo = $db->conn;

// Load controller implementations
include_once __DIR__ . '/DieuKhien/DieuKhienTrang.php';
include_once __DIR__ . '/DieuKhien/dieukhienxacthuc.php';

// Instantiate controllers, passing the PDO connection object
$c = new controller($pdo);
$authController = new DieuKhienXacThuc($pdo);

// Determine action
$act = $_GET['act'] ?? 'trangchu';

switch ($act) {
    // Page/Product Actions
    case 'trangchu':
        $c->trangchu();
        break;
    case 'hienthi_sp':
        $c->hienthi_sp();
        break;
    case 'xl_themsp':
        $c->xl_themsp();
        break;
    case 'deletesp':
        $c->xoa_sp();
        break;

    // Authentication Actions
    case 'dang_nhap':
        $authController->hien_thi_dang_nhap();
        break;
    case 'xu_ly_dang_nhap':
        $authController->xu_ly_dang_nhap();
        break;
    case 'dang_ky':
        $authController->hien_thi_dang_ky();
        break;
    case 'xu_ly_dang_ky':
        $authController->xu_ly_dang_ky();
        break;
    case 'dang_xuat':
        $authController->dang_xuat();
        break;

    default:
        $c->trangchu();
        break;
}
?>
   

