<?php
// Tệp: DieuKhien/DieuKhienTrang.php
require_once __DIR__ . '/../MoHinh/Voucher.php';

require_once __DIR__ . '/../MoHinh/DonHang.php';
require_once __DIR__ . '/../MoHinh/nguoidung.php';
require_once __DIR__ . '/../MoHinh/SanPham.php';
require_once __DIR__ . '/../MoHinh/DanhMuc.php';
require_once __DIR__ . '/../MoHinh/BinhLuan.php';

class controller {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function trangchu() {
        // Lấy một vài sản phẩm để hiển thị trên trang chủ (ví dụ)
        $sp_model = new sanpham($this->pdo);
        $danh_sach_san_pham = $sp_model->getallsanpham(8); // Lấy 8 sản phẩm
        $danh_sach_hot_sale = $sp_model->getHotSaleProducts(5); // Lấy 5 sản phẩm hot sale
        include __DIR__.'/../GiaoDien/trang/trang_chu.php';
    }

    public function hienthi_sp() {
        $sp_model = new sanpham($this->pdo);
        $dm_model = new danhmuc($this->pdo);

        // Lấy các tham số lọc và sắp xếp từ URL
        $filters = [
            'brands' => $_GET['brands'] ?? [],
            'price_range' => $_GET['price'] ?? '',
        ];
        $sort_by = $_GET['sort'] ?? 'newest';

        // Logic phân trang
        $products_per_page = 8;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $products_per_page;

        // Lấy tổng số sản phẩm và danh sách sản phẩm đã lọc
        $total_products = $sp_model->countAllSanPham($filters);
        $total_pages = ceil($total_products / $products_per_page);
        $danh_sach_san_pham = $sp_model->getallsanpham($products_per_page, $offset, $sort_by, $filters);

        // Lấy dữ liệu cho sidebar filter
        $available_brands = $dm_model->getDS_Danhmuc();

        include __DIR__.'/../GiaoDien/trang/danh_sach_san_pham.php';
        include __DIR__.'/../GiaoDien/trang/bo_cuc/pagination.php';
    }

    public function hienthi_sp_theo_danhmuc($return_data = false) {
        $category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($category_id <= 0) {
            header('Location: index.php?act=trangchu');
            exit();
        }

        $sp_model = new sanpham($this->pdo);
        $dm_model = new danhmuc($this->pdo);

        // Lấy các tham số lọc và sắp xếp từ URL
        $filters = [
            'brands' => $_GET['brands'] ?? [],
            'price_range' => $_GET['price'] ?? '',
        ];
        $sort_by = $_GET['sort'] ?? 'newest';

        // Lấy thông tin danh mục để hiển thị tiêu đề
        $category_info = $dm_model->getDanhMucById($category_id);
        // KIỂM TRA: Nếu không tìm thấy danh mục, chuyển hướng về trang chủ
        if (!$category_info) {
            header('Location: index.php?act=trangchu');
            exit();
        }

        if ($return_data) {
            return $category_info;
        }

        // Logic phân trang
        $products_per_page = 8;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $products_per_page;

        $total_products = $sp_model->countSanPhamByDanhMuc($category_id, $filters);
        $total_pages = ceil($total_products / $products_per_page);

        $danh_sach_san_pham = $sp_model->getSanPhamByDanhMuc($category_id, $products_per_page, $offset, $sort_by, $filters);

        // Lấy dữ liệu cho sidebar filter
        $available_brands = $dm_model->getDS_Danhmuc();

        include __DIR__.'/../GiaoDien/trang/danh_sach_san_pham.php';
        include __DIR__.'/../DieuKhien/phan_trang.php';
    }

    public function chi_tiet_san_pham($return_data = false) {
        $id = $_GET['id'] ?? 0;
        $sp_model = new sanpham($this->pdo);
        $san_pham = $sp_model->getone_sanpham($id); // Sửa lại tên hàm cho đúng
        if ($return_data) {
            return $san_pham;
        }
        if (!$san_pham) {
            header('Location: index.php?act=trangchu');
            exit;
        }

        // --- LOGIC SẢN PHẨM ĐÃ XEM ---
        if (!isset($_SESSION['recently_viewed'])) {
            $_SESSION['recently_viewed'] = [];
        }
        $current_product_id = (int)$san_pham['id'];
        // Xóa ID sản phẩm hiện tại nếu đã tồn tại để đưa lên đầu
        if (($key = array_search($current_product_id, $_SESSION['recently_viewed'])) !== false) {
            unset($_SESSION['recently_viewed'][$key]);
        }
        // Thêm ID sản phẩm hiện tại vào đầu mảng
        array_unshift($_SESSION['recently_viewed'], $current_product_id);
        // Giới hạn số lượng sản phẩm đã xem là 6 (5 sản phẩm cũ + 1 sản phẩm hiện tại)
        $_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 6);

        // Lấy danh sách sản phẩm đã xem (trừ sản phẩm hiện tại) để hiển thị
        $recently_viewed_products = [];
        $viewed_ids_to_fetch = array_diff($_SESSION['recently_viewed'], [$current_product_id]);
        
        if (!empty($viewed_ids_to_fetch)) {
            // Giả sử model sanpham có phương thức getProductsByIds
            $recently_viewed_products = $sp_model->getProductsByIds($viewed_ids_to_fetch);
        }

        // Tạo breadcrumbs
        // File helper đã được include trong GiaoDien/trang/bo_cuc/dau_trang.php
        if (function_exists('generate_breadcrumbs')) {
            $breadcrumbs = generate_breadcrumbs($this->pdo, 'chi_tiet_san_pham', ['san_pham' => $san_pham]);
        }

        // Lấy thông tin bình luận và đánh giá
        $reviewModel = new BinhLuan($this->pdo);

        // Lấy các bộ lọc từ URL
        $filters = [
            'rating' => $_GET['rating'] ?? null,
            'has_image' => isset($_GET['has_image']),
            'verified' => isset($_GET['verified']),
        ];

        $flat_reviews = $reviewModel->getReviewsByProductId($id, $filters);
        $rating_info = $reviewModel->getAverageRating($id);
        $rating_counts = $reviewModel->getRatingCounts($id);


        // Sắp xếp bình luận thành cây cha-con
        $reviews_tree = [];
        $reviews_by_id = [];
        foreach ($flat_reviews as $review) {
            $reviews_by_id[$review['id']] = $review;
            $reviews_by_id[$review['id']]['replies'] = [];
        }

        // Lọc riêng cho "Đã mua hàng" sau khi đã lấy dữ liệu
        if ($filters['verified']) {
            $flat_reviews = array_filter($flat_reviews, fn($review) => $review['is_verified_purchase']);
        }

        foreach ($reviews_by_id as $review_id => &$review) {
            if ($review['parent_id'] && isset($reviews_by_id[$review['parent_id']])) {
                // Đây là một bình luận trả lời, thêm nó vào mảng 'replies' của cha
                $reviews_by_id[$review['parent_id']]['replies'][] = &$review;
            } else {
                // Đây là bình luận gốc
                $reviews_tree[] = &$review;
            }
        }

        include __DIR__.'/../GiaoDien/trang/chi_tiet_san_pham.php';
    }

    public function tim_kiem_san_pham() {
        $keyword = $_GET['keyword'] ?? '';
        $price_range = $_GET['price_range'] ?? '';

        $sp_model = new sanpham($this->pdo);
        $danh_sach_san_pham = $sp_model->timKiemSanPham($keyword, $price_range);
        
        include __DIR__.'/../GiaoDien/trang/ket_qua_tim_kiem.php';
    }

    public function ajax_tim_kiem() {
        header('Content-Type: application/json'); // Báo cho trình duyệt biết đây là dữ liệu JSON
        $keyword = $_GET['keyword'] ?? '';
        $sp_model = new sanpham($this->pdo);

        // Giới hạn số lượng kết quả trả về để không làm chậm trang
        $danh_sach_san_pham = $sp_model->timKiemSanPham($keyword, 5); 

        echo json_encode($danh_sach_san_pham);
        exit(); // Dừng thực thi sau khi trả về JSON
    }

    // --- CÁC HÀM XỬ LÝ GIỎ HÀNG ---

    public function them_vao_gio() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $image_url = $_POST['image_url'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'] ?? 1;
            $variant_color = $_POST['variant_color'] ?? null; // Lấy thông tin màu sắc

            // Tạo một ID duy nhất cho sản phẩm trong giỏ hàng, kết hợp ID sản phẩm và màu sắc
            $cart_item_id = ($variant_color) ? $id . '_' . str_replace(' ', '_', $variant_color) : (string)$id;

            // Kiểm tra sản phẩm (với màu cụ thể) đã có trong giỏ hàng chưa
            if (isset($_SESSION['cart'][$cart_item_id])) {
                // Nếu có, tăng số lượng
                $_SESSION['cart'][$cart_item_id]['quantity'] += $quantity;
            } else {
                // Nếu chưa, thêm mới
                $_SESSION['cart'][$cart_item_id] = [
                    'id' => $id,
                    'name' => $name,
                    'image_url' => $image_url,
                    'price' => $price,
                    'quantity' => $quantity,
                    'variant_color' => $variant_color // Lưu lại màu để hiển thị nếu cần
                ];
            }
        }

        // Tính toán tổng số lượng mới
        $new_total_quantity = array_sum(array_column($_SESSION['cart'], 'quantity'));

        // Trả về kết quả dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'new_total_quantity' => $new_total_quantity]);
        exit(); // Dừng thực thi
    }

    public function ap_dung_voucher() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['voucher_code'] ?? '';
            $cart = $_SESSION['cart'] ?? [];
            
            // Xóa voucher cũ trước khi áp dụng cái mới
            unset($_SESSION['voucher']);
            unset($_SESSION['voucher_error']);
            unset($_SESSION['voucher_success']);

            if (empty($code)) {
                $_SESSION['voucher_error'] = "Vui lòng nhập mã voucher.";
            } else {
                $voucherModel = new Voucher($this->pdo);
                $voucher = $voucherModel->findVoucherByCode($code);
                
                // Sửa đổi: findVoucherByCode đã kiểm tra các điều kiện (active, usage, expiry)
                if ($voucher) { 
                    // 1. Tính tổng tiền hàng (subtotal)
                    $subtotal = 0;
                    foreach ($cart as $item) {
                        $subtotal += $item['price'] * $item['quantity'];
                    }

                    // 2. KIỂM TRA ĐIỀU KIỆN ĐƠN HÀNG TỐI THIỂU
                    if ($subtotal >= $voucher['min_order_amount']) {
                        // 3. Tính toán số tiền giảm giá
                        $discount_amount = 0;
                        if ($voucher['discount_type'] === 'percentage') {
                            $discount_amount = ($subtotal * $voucher['discount_value']) / 100;
                        } else { // 'fixed'
                            $discount_amount = $voucher['discount_value'];
                        }

                        // 4. Lưu thông tin voucher vào session
                        $_SESSION['voucher'] = [
                            'code' => $voucher['code'],
                            'discount_amount' => $discount_amount
                        ];
                        $_SESSION['voucher_success'] = "Áp dụng voucher thành công!";
                    } else {
                        // Báo lỗi nếu không đủ điều kiện
                        $min_amount_formatted = number_format($voucher['min_order_amount'], 0, ',', '.');
                        $_SESSION['voucher_error'] = "Voucher này chỉ áp dụng cho đơn hàng từ {$min_amount_formatted}₫.";
                    }
                } else {
                    // Báo lỗi nếu voucher không tồn tại hoặc không hợp lệ
                    $_SESSION['voucher_error'] = "Mã voucher không hợp lệ, đã hết hạn hoặc đã hết lượt sử dụng.";
                }
            }
        }
        header('Location: index.php?act=gio_hang');
        exit();
    }

    public function hien_thi_gio_hang() {
        $cart = $_SESSION['cart'] ?? []; // Lấy giỏ hàng từ session
        $subtotal = 0; // Tạm tính
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Lấy thông tin voucher từ session nếu có
        $voucher_code = $_SESSION['voucher']['code'] ?? null;
        $discount_amount = $_SESSION['voucher']['discount_amount'] ?? 0;

        // Lấy danh sách sản phẩm đã lưu (wishlist)
        $saved_products = [];
        if (isset($_SESSION['user_id'])) {
            $userModel = new NguoiDung($this->pdo);
            // Lấy 4 sản phẩm để hiển thị
            $saved_products = $userModel->getWishlist($_SESSION['user_id'], 4);
        }

        // --- BỔ SUNG: Lấy sản phẩm gợi ý nếu giỏ hàng trống ---
        $suggested_products = [];
        if (empty($cart)) {
            $sp_model = new sanpham($this->pdo);
            $suggested_products = $sp_model->getallsanpham(4); // Lấy 4 sản phẩm để gợi ý
        }

        include __DIR__.'/../GiaoDien/trang/gio_hang.php';
    }

    public function xoa_voucher() {
        // Xóa thông tin voucher khỏi session
        unset($_SESSION['voucher']);
        unset($_SESSION['voucher_error']);
        unset($_SESSION['voucher_success']);

        // Chuyển hướng về trang giỏ hàng
        header('Location: index.php?act=gio_hang');
        exit();
    }

    public function xoa_san_pham_gio_hang() {
        $id = $_GET['id'] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: index.php?act=gio_hang');
        exit();
    }

    public function cap_nhat_gio_hang() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

            if ($id && isset($_SESSION['cart'][$id])) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                } else {
                    // Nếu số lượng là 0 hoặc nhỏ hơn, xóa sản phẩm khỏi giỏ hàng
                    unset($_SESSION['cart'][$id]);
                }
            }

            // Tính toán lại các giá trị tổng
            $subtotal = 0;
            foreach ($_SESSION['cart'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $total_quantity = array_sum(array_column($_SESSION['cart'], 'quantity'));

            // Trả về dữ liệu JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'new_subtotal' => $subtotal,
                'new_total_quantity' => $total_quantity
            ]);
            exit();
        }
    }

    public function them_binh_luan() {
        // 1. Kiểm tra đăng nhập và phương thức POST
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit();
        }

        // 2. Thu thập và xác thực dữ liệu
        $product_id = $_POST['product_id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        $rating = $_POST['rating'] ?? 0;
        $comment = trim($_POST['comment'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        // Xử lý upload ảnh bình luận (nếu có)
        $review_image_url = null;
        if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] == 0) {
            $target_dir = __DIR__ . "/../TaiLen/reviews/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES["review_image"]["name"], PATHINFO_EXTENSION);
            $new_filename = 'review_' . $product_id . '_' . $user_id . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["review_image"]["tmp_name"], $target_file)) {
                $review_image_url = $new_filename;
            }
        }

        // Điều kiện hợp lệ:
        // - Phải có product_id và comment.
        // - Hoặc là trả lời (có parent_id).
        // - Hoặc là đánh giá mới (có rating > 0).
        if ($product_id > 0 && !empty($comment) && ($parent_id !== null || ($rating > 0 && $rating <= 5))) {
            // 3. Gọi model để thêm bình luận
            $reviewModel = new BinhLuan($this->pdo);
            $reviewModel->addReview($product_id, $user_id, $rating, $comment, $parent_id, $review_image_url);
        }

        // 4. Chuyển hướng người dùng trở lại trang sản phẩm
        header('Location: index.php?act=chi_tiet_san_pham&id=' . $product_id);
        exit();
    }

    // --- CÁC HÀM XỬ LÝ THANH TOÁN ---

    public function hien_thi_thanh_toan() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = 'index.php?act=thanh_toan';
            header('Location: index.php?act=dang_nhap');
            exit();
        }

        $cart_to_checkout = [];
        $full_cart = $_SESSION['cart'] ?? [];

        // A. User is coming from gio_hang.php with selected items
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_items'])) {
            $selected_ids = $_POST['selected_items'];
            foreach ($full_cart as $id => $item) {
                if (in_array($id, $selected_ids)) {
                    $cart_to_checkout[$id] = $item;
                }
            }
            // Store the selected cart for processing the order later
            $_SESSION['checkout_cart'] = $cart_to_checkout;
        } 
        // B. User might be redirected here (e.g. after login), or refreshed the page
        else if (isset($_SESSION['checkout_cart'])) {
            $cart_to_checkout = $_SESSION['checkout_cart'];
        }

        // 2. Kiểm tra giỏ hàng (để thanh toán) có rỗng không
        if (empty($cart_to_checkout)) {
            // If nothing was ever selected, redirect back to cart with a message
            $_SESSION['cart_error'] = 'Vui lòng chọn sản phẩm trong giỏ hàng trước khi thanh toán.';
            header('Location: index.php?act=gio_hang');
            exit();
        }

        // Lấy lỗi từ session nếu có (sau khi xử lý đơn hàng thất bại)
        $order_error = $_SESSION['order_error'] ?? null;
        unset($_SESSION['order_error']); // Xóa lỗi khỏi session sau khi đọc

        // 3. Lấy thông tin người dùng để điền sẵn
        $userModel = new NguoiDung($this->pdo);
        $user_info = $userModel->findUserById($_SESSION['user_id']);

        // 4. Tính toán lại tổng tiền (chỉ cho các sản phẩm được chọn)
        $subtotal = 0;
        foreach ($cart_to_checkout as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Lấy thông tin voucher và các thông báo
        $voucher_code = $_SESSION['voucher']['code'] ?? null;
        $discount_amount = $_SESSION['voucher']['discount_amount'] ?? 0;
        $final_total = $subtotal - $discount_amount;

        $voucher_error = $_SESSION['voucher_error'] ?? null;
        $voucher_success = $_SESSION['voucher_success'] ?? null;

        // Xóa thông báo sau khi đã lấy để chúng không hiển thị lại
        unset($_SESSION['voucher_error']);
        unset($_SESSION['voucher_success']);

        // 5. Hiển thị view và truyền các biến
        // Pass the correct cart to the view
        $cart = $cart_to_checkout; 
        include __DIR__.'/../GiaoDien/trang/thanh_toan.php';
    }

    public function xu_ly_dat_hang() {
        // 1. Kiểm tra đăng nhập và phương thức POST
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit();
        }

        $checkout_cart = $_SESSION['checkout_cart'] ?? [];
        
        // The items submitted from the thanh_toan form.
        // This assumes the checkout page form still uses name="selected_items[]" for the products to be purchased.
        $final_selected_ids = $_POST['selected_items'] ?? [];
        $full_cart = $_SESSION['cart'] ?? [];

        // 2. Kiểm tra giỏ hàng và các mục đã chọn
        if (empty($checkout_cart) || empty($final_selected_ids)) {
            $_SESSION['order_error'] = 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.';
            header('Location: index.php?act=thanh_toan');
            exit();
        }

        // 3. Lọc ra các sản phẩm thực sự được mua từ giỏ hàng thanh toán
        $purchased_cart = [];
        foreach ($checkout_cart as $item_id => $item) {
            if (in_array($item_id, $final_selected_ids)) {
                $purchased_cart[$item_id] = $item;
            }
        }
        
        if (empty($purchased_cart)) {
            $_SESSION['order_error'] = 'Không có sản phẩm nào hợp lệ được chọn để đặt hàng.';
            header('Location: index.php?act=thanh_toan');
            exit();
        }

        // 4. KIỂM TRA SỐ LƯỢNG TỒN KHO (trên các sản phẩm đã mua)
        $sp_model = new sanpham($this->pdo);
        foreach ($purchased_cart as $item) {
            $product = $sp_model->getone_sanpham($item['id']);
            if (!$product || $product['quantity'] < $item['quantity']) {
                $_SESSION['cart_error'] = "Sản phẩm '{$item['name']}' không đủ số lượng tồn kho. Vui lòng cập nhật giỏ hàng của bạn.";
                header('Location: index.php?act=gio_hang');
                exit();
            }
        }

        // 5. Tính toán lại tổng tiền CHỈ DỰA TRÊN các sản phẩm đã mua
        $subtotal = 0;
        foreach ($purchased_cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $voucher_code = $_SESSION['voucher']['code'] ?? null;
        $discount_amount = $_SESSION['voucher']['discount_amount'] ?? 0;
        
        // Đảm bảo voucher vẫn hợp lệ với subtotal mới
        // (Logic này có thể cần phức tạp hơn nếu bạn có nhiều loại voucher)
        if ($subtotal <= 0) {
            $discount_amount = 0; // Không giảm giá nếu không có sản phẩm
        }

        $final_total = $subtotal - $discount_amount;
        if ($final_total < 0) {
            $final_total = 0; // Đảm bảo tổng tiền không âm
        }

        // 6. Thu thập thông tin giao hàng
        $user_id = $_SESSION['user_id'];
        $fullname = $_POST['fullname'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $address = $_POST['address'] ?? '';
        $shipping_address = "Họ tên: $fullname\nSố điện thoại: $phone_number\nĐịa chỉ: $address";
        $payment_method = $_POST['payment_method'] ?? 'cod';

        // 7. Gọi model để tạo đơn hàng
        $donHangModel = new donhang($this->pdo);
        $orderId = $donHangModel->createOrder($user_id, $purchased_cart, $final_total, $shipping_address, $payment_method, $voucher_code, $discount_amount);

        if ($orderId) {
            // 8. Cập nhật lại giỏ hàng session với các sản phẩm còn lại
            $remaining_cart = array_diff_key($full_cart, $purchased_cart);
            $_SESSION['cart'] = $remaining_cart;
            
            // Xóa giỏ hàng thanh toán tạm thời và voucher
            unset($_SESSION['checkout_cart']);
            unset($_SESSION['voucher']);

            // 10. Chuyển hướng đến trang thành công
            header('Location: index.php?act=dat_hang_thanh_cong&id=' . $orderId); // Sửa: Chuyển hướng đến trang thành công
            exit();
        } else {
            $_SESSION['order_error'] = 'Không thể tạo đơn hàng do lỗi hệ thống.';
            header('Location: index.php?act=thanh_toan');
            exit();
        }
    }

    public function dat_hang_thanh_cong() {
        $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($order_id <= 0) {
            header('Location: index.php?act=trangchu');
            exit();
        }
        // Lấy thông tin chi tiết đơn hàng để hiển thị trên trang xác nhận
        $donHangModel = new donhang($this->pdo);
        $chi_tiet_don_hang = $donHangModel->getOrderDetail($order_id);

        // Để bảo mật, kiểm tra xem đơn hàng này có thực sự thuộc về người dùng đang đăng nhập không
        if (!$chi_tiet_don_hang['order_info'] || $chi_tiet_don_hang['order_info']['user_id'] != $_SESSION['user_id']) {
            header('Location: index.php?act=trangchu');
            exit();
        }

        include __DIR__.'/../GiaoDien/trang/dat_hang_thanh_cong.php';
    }

    public function huy_don_hang() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?act=dang_nhap');
            exit();
        }

        // 2. Lấy ID đơn hàng và ID người dùng
        $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user_id = $_SESSION['user_id'];

        if ($order_id <= 0) {
            header('Location: index.php?act=lich_su_mua_hang');
            exit();
        }

        // 3. Gọi model để xử lý hủy đơn
        $donHangModel = new donhang($this->pdo);
        if ($donHangModel->cancelOrder($order_id, $user_id)) {
            header('Location: index.php?act=lich_su_mua_hang&success=cancelled');
        } else {
            header('Location: index.php?act=lich_su_mua_hang&error=cancel_failed');
        }
        exit();
    }
    public function chi_tiet_don_hang() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?act=dang_nhap');
            exit();
        }

        // 2. Lấy ID đơn hàng và ID người dùng
        $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user_id = $_SESSION['user_id'];

        if ($order_id <= 0) {
            header('Location: index.php?act=lich_su_mua_hang');
            exit();
        }

        // 3. Lấy chi tiết đơn hàng từ model
        require_once __DIR__ . '/../MoHinh/DonHang.php';
        $donHangModel = new donhang($this->pdo);
        $chi_tiet_don_hang = $donHangModel->getOrderDetail($order_id);

        // 4. KIỂM TRA BẢO MẬT: Đảm bảo đơn hàng này thuộc về người dùng đang đăng nhập
        if (!$chi_tiet_don_hang['order_info'] || $chi_tiet_don_hang['order_info']['user_id'] != $user_id) {
            header('Location: index.php?act=lich_su_mua_hang&error=unauthorized');
            exit();
        }

        // 5. Hiển thị view
        include __DIR__.'/../GiaoDien/trang/chi_tiet_don_hang.php';
    }

    public function lich_su_mua_hang() {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?act=dang_nhap');
            exit();
        }

        // Lấy thông tin người dùng để hiển thị trên sidebar
        $userModel = new NguoiDung($this->pdo);
        $user_info = $userModel->findUserById($_SESSION['user_id']);

        // 2. Lấy trạng thái lọc từ URL
        $status_filter = $_GET['status'] ?? '';

        // 3. Lấy đơn hàng từ CSDL dựa trên bộ lọc
        require_once __DIR__ . '/../MoHinh/DonHang.php';
        $donHangModel = new donhang($this->pdo);
        $danh_sach_don_hang = $donHangModel->getOrdersByUserId($user_info['id'], $status_filter);

        // 4. Định nghĩa các trạng thái để hiển thị trên view
        $order_statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'success' => 'Thành công',
            'cancelled' => 'Đã hủy'
        ];

        // 5. Hiển thị view
        include __DIR__.'/../GiaoDien/trang/lich_su_mua_hang.php';
    }

    public function thong_tin_tai_khoan() {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?act=dang_nhap&redirect=thong_tin_tai_khoan');
            exit();
        }

        // 2. Lấy thông tin người dùng từ CSDL
        $userModel = new NguoiDung($this->pdo);
        $user_info = $userModel->findUserById($_SESSION['user_id']);

        // --- LOGIC TÍNH HẠNG THÀNH VIÊN ---
        if (!function_exists('getCustomerRank')) {
            function getCustomerRank($spending)
            {
                $ranks = [
                    'Đồng' => ['threshold' => 0, 'class' => 'rank-copper'],
                    'Bạc' => ['threshold' => 5000000, 'class' => 'rank-silver'],
                    'Vàng' => ['threshold' => 15000000, 'class' => 'rank-gold'],
                    'Kim Cương' => ['threshold' => 30000000, 'class' => 'rank-diamond'],
                ];

                $current_rank_name = 'Đồng';
                $next_rank_name = 'Bạc';

                if ($spending >= $ranks['Kim Cương']['threshold']) {
                    $current_rank_name = 'Kim Cương';
                    $next_rank_name = null; // Hạng cao nhất
                } elseif ($spending >= $ranks['Vàng']['threshold']) {
                    $current_rank_name = 'Vàng';
                    $next_rank_name = 'Kim Cương';
                } elseif ($spending >= $ranks['Bạc']['threshold']) {
                    $current_rank_name = 'Bạc';
                    $next_rank_name = 'Vàng';
                }

                $needed_for_next = $next_rank_name ? $ranks[$next_rank_name]['threshold'] - $spending : 0;
                $progress_percentage = $next_rank_name ? ($spending / $ranks[$next_rank_name]['threshold']) * 100 : 100;

                return [
                    'rank' => $current_rank_name,
                    'class' => $ranks[$current_rank_name]['class'],
                    'next_rank' => $next_rank_name,
                    'needed_for_next' => $needed_for_next,
                    'progress_percentage' => min($progress_percentage, 100) // Đảm bảo không vượt quá 100%
                ];
            }
        }
        // Tính tổng chi tiêu và xác định hạng
        $total_spending = $userModel->getTotalSpendingByUserId($user_info['id']);
        $rank_info = getCustomerRank($total_spending);

        // 3. Hiển thị view
        include __DIR__.'/../GiaoDien/trang/thong_tin_tai_khoan.php';
    }

    public function cap_nhat_tai_khoan() {
        // 1. Kiểm tra người dùng đã đăng nhập và gửi form bằng POST
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=trangchu');
            exit();
        }

        // 2. Lấy dữ liệu từ form
        $user_id = $_SESSION['user_id'];
        $fullname = $_POST['fullname'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $address = $_POST['address'] ?? '';

        // 3. Gọi model để cập nhật
        $userModel = new NguoiDung($this->pdo);
        $userModel->updateUserInfo($user_id, $fullname, $phone_number, $address);

        // 4. Cập nhật lại session để tên mới hiển thị ngay lập tức
        $_SESSION['user_fullname'] = $fullname;

        // 4. Chuyển hướng lại trang thông tin với thông báo thành công
        header('Location: index.php?act=thong_tin_tai_khoan&success=1');
        exit();
    }

    public function doi_mat_khau() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=trangchu');
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';

        // Kiểm tra mật khẩu mới có khớp không
        if ($new_password !== $confirm_new_password) {
            header('Location: index.php?act=thong_tin_tai_khoan&error=password_mismatch');
            exit();
        }

        $userModel = new NguoiDung($this->pdo);
        $user = $userModel->findUserById($user_id);

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!$user || !password_verify($current_password, $user['password'])) {
            header('Location: index.php?act=thong_tin_tai_khoan&error=wrong_password');
            exit();
        }

        // Cập nhật mật khẩu mới
        $userModel->updatePassword($user_id, $new_password);

        // Chuyển hướng với thông báo thành công
        header('Location: index.php?act=thong_tin_tai_khoan&success=password_changed');
        exit();
    }

    public function cap_nhat_avatar() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=trangchu');
            exit();
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $target_dir = __DIR__ . "/../TaiLen/avatars/";
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
            // Tạo tên tệp duy nhất để tránh ghi đè
            $new_filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Di chuyển tệp đã tải lên
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                // Cập nhật CSDL
                $userModel = new NguoiDung($this->pdo);
                $userModel->updateAvatar($_SESSION['user_id'], $new_filename);
                header('Location: index.php?act=thong_tin_tai_khoan&success=avatar_updated');
                exit();
            }
        }

        // Nếu có lỗi, chuyển hướng lại
        header('Location: index.php?act=thong_tin_tai_khoan&error=avatar_upload_failed');
        exit();
    }

    public function thu_cu_doi_moi() {
        // Chỉ cần hiển thị view, logic chính nằm ở JavaScript
        include __DIR__.'/../GiaoDien/trang/thu_cu_doi_moi.php';
    }
}