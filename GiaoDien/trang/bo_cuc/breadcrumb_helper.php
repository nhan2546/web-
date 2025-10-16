<?php
// File: GiaoDien/trang/bo_cuc/breadcrumb_helper.php

if (!function_exists('generate_breadcrumbs')) {
    /**
     * Tạo mảng breadcrumb dựa trên action và dữ liệu được cung cấp.
     *
     * @param PDO $pdo Đối tượng kết nối CSDL.
     * @param string $act Action hiện tại từ URL.
     * @param array $data Dữ liệu bổ sung (ví dụ: thông tin sản phẩm, danh mục).
     * @return array Mảng chứa các mục breadcrumb.
     */
    function generate_breadcrumbs($pdo, $act, $data = []) {
        $breadcrumbs = [];
        // Mục đầu tiên luôn là Trang chủ
        $breadcrumbs[] = ['title' => 'Trang chủ', 'url' => 'index.php?act=trangchu'];

        switch ($act) {
            case 'hienthi_sp':
                $breadcrumbs[] = ['title' => 'Tất cả sản phẩm', 'url' => null];
                break;

            case 'danhmuc':
                if (isset($data['category_info'])) {
                    $breadcrumbs[] = ['title' => $data['category_info']['name'], 'url' => null];
                }
                break;

            case 'chi_tiet_san_pham':
                if (isset($data['san_pham'])) {
                    $product = $data['san_pham'];
                    // Lấy thông tin danh mục của sản phẩm
                    $dm_model = new danhmuc($pdo);
                    $category_info = $dm_model->getDanhMucById($product['category_id']);
                    if ($category_info) {
                        $breadcrumbs[] = ['title' => $category_info['name'], 'url' => 'index.php?act=danhmuc&id=' . $category_info['id']];
                    }
                    $breadcrumbs[] = ['title' => $product['name'], 'url' => null];
                }
                break;

            case 'tim_kiem':
                $keyword = $data['keyword'] ?? '';
                $breadcrumbs[] = ['title' => 'Tìm kiếm', 'url' => null];
                if (!empty($keyword)) {
                    $breadcrumbs[] = ['title' => '"' . htmlspecialchars($keyword) . '"', 'url' => null];
                }
                break;

            case 'gio_hang':
                $breadcrumbs[] = ['title' => 'Giỏ hàng', 'url' => null];
                break;

            case 'thanh_toan':
                $breadcrumbs[] = ['title' => 'Giỏ hàng', 'url' => 'index.php?act=gio_hang'];
                $breadcrumbs[] = ['title' => 'Thanh toán', 'url' => null];
                break;

            case 'thong_tin_tai_khoan':
                $breadcrumbs[] = ['title' => 'Tài khoản của tôi', 'url' => null];
                break;

            case 'lich_su_mua_hang':
                $breadcrumbs[] = ['title' => 'Tài khoản của tôi', 'url' => 'index.php?act=thong_tin_tai_khoan'];
                $breadcrumbs[] = ['title' => 'Lịch sử mua hàng', 'url' => null];
                break;

            case 'chi_tiet_don_hang_user':
                $breadcrumbs[] = ['title' => 'Tài khoản của tôi', 'url' => 'index.php?act=thong_tin_tai_khoan'];
                $breadcrumbs[] = ['title' => 'Lịch sử mua hàng', 'url' => 'index.php?act=lich_su_mua_hang'];
                $breadcrumbs[] = ['title' => 'Chi tiết đơn hàng', 'url' => null];
                break;

            case 'thu_cu_doi_moi':
                $breadcrumbs[] = ['title' => 'Thu cũ đổi mới', 'url' => null];
                break;

            case 'dang_nhap':
                $breadcrumbs[] = ['title' => 'Đăng nhập', 'url' => null];
                break;

            case 'dang_ky':
                $breadcrumbs[] = ['title' => 'Đăng ký', 'url' => null];
                break;

            case 'quen_mat_khau':
                $breadcrumbs[] = ['title' => 'Quên mật khẩu', 'url' => null];
                break;

            case 'dat_lai_mat_khau':
                $breadcrumbs[] = ['title' => 'Đặt lại mật khẩu', 'url' => null];
                break;
        }

        return $breadcrumbs;
    }
}
?>