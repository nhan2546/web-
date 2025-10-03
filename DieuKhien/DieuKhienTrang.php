<?php
// Model includes (đường dẫn tương đối từ thư mục này)
include_once __DIR__ . '/../MoHinh/SanPham.php';
// load DB (creates $pdo)
if (file_exists(__DIR__ . '/../MoHinh/CSDL.php')) {
    include_once __DIR__ . '/../MoHinh/CSDL.php';
} elseif (file_exists(__DIR__ . '/../database/database.php')) {
    include_once __DIR__ . '/../database/database.php';
}
// danhmuc model may be in MoHinh or model folder; try common locations
if (file_exists(__DIR__ . '/../MoHinh/danhmuc.php')) {
    include_once __DIR__ . '/../MoHinh/danhmuc.php';
} elseif (file_exists(__DIR__ . '/../model/danhmuc.php')) {
    include_once __DIR__ . '/../model/danhmuc.php';
}
if (file_exists(__DIR__ . '/../MoHinh/donhang.php')) {
    include_once __DIR__ . '/../MoHinh/donhang.php';
} elseif (file_exists(__DIR__ . '/../model/donhang.php')) {
    include_once __DIR__ . '/../model/donhang.php';
}

class controller {

    // ---- TRANG CHỦ ----
    public function trangchu() {
        // Thư mục view trang chủ (nếu có) - điều chỉnh đường dẫn nếu khác
        $view = __DIR__ . '/../GiaoDien/trang/trangchu.php';
        if (file_exists($view)) {
            include $view;
            return;
        }
        // Fallback: nếu không có view cụ thể, include file index hoặc home
        if (file_exists(__DIR__ . '/../GiaoDien/QuanTri/bang_dieu_khien.php')) {
            include __DIR__ . '/../GiaoDien/QuanTri/bang_dieu_khien.php';
            return;
        }
        echo "Trang chủ tạm thời chưa có giao diện.";
    }

    // ---- SẢN PHẨM ----
    public function hienthi_sp() {
        // SanPham expects a PDO in constructor
        if (!isset($pdo)) {
            // try to include CSDL.php
            if (file_exists(__DIR__ . '/../MoHinh/CSDL.php')) {
                include_once __DIR__ . '/../MoHinh/CSDL.php';
            }
        }
        $sp_model = new sanpham(isset($pdo) ? $pdo : null);
        // Danh mục model là tuỳ repo; chỉ gọi nếu class tồn tại
        $danhmuc = [];
        if (class_exists('danhmuc')) {
            $dmClass = 'danhmuc';
            $dm_model = new $dmClass(isset($pdo) ? $pdo : null);
            if (method_exists($dm_model, 'getDS_Danhmuc')) {
                $danhmuc = $dm_model->getDS_Danhmuc();
            }
        }

        $danhsach = [];
        if (method_exists($sp_model, 'getallsanpham')) {
            $danhsach = $sp_model->getallsanpham();
        }

    // Nạp view và truyền dữ liệu qua (danh_sach.php)
    $view = __DIR__ . '/../GiaoDien/QuanTri/san_pham/danh_sach.php';
        if (file_exists($view)) {
            include $view;
            return;
        }
        echo "Chưa có giao diện danh sách sản phẩm.";
    }

    public function xl_themsp() {
        // 1. Nhận dữ liệu từ form (dùng ?? để tránh undefined index)
        $id_danhmuc = $_POST['id_danhmuc'] ?? null;
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $mount = $_POST['mount'] ?? 0;
        $sale = $_POST['sale'] ?? 0;
        $decribe = $_POST['decribe'] ?? '';

        // 2. Xử lý upload ảnh
        $image_name = "default.jpg";
        if (!empty($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = __DIR__ . "/../uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $image_name = time() . "_" . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;

            // Di chuyển file vào thư mục uploads
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_name = "default.jpg"; // Nếu lỗi thì dùng ảnh mặc định
            }
        }

    // 3. Gọi model để thêm vào CSDL
    $sp_model = new sanpham(isset($pdo) ? $pdo : null);
        $sp_model->themsp($id_danhmuc, $name, $price, $mount, $image_name, $sale, $decribe);

        // 4. Chuyển hướng về trang danh sách sản phẩm
        header('Location: index.php?act=hienthi_sp');
        exit;
    }

    public function xoa_sp() {
        if (!empty($_GET['idsp_del'])) {
            $id = $_GET['idsp_del'];
            $sp_model = new sanpham(isset($pdo) ? $pdo : null);
            $sp_model->deletesp($id);
        }
        header('Location: index.php?act=hienthi_sp');
        exit;
    }
}
?>