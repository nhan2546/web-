<?php
include_once __DIR__ . '/../MoHinh/NguoiDung.php';

class DieuKhienXacThuc {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Hiển thị form đăng nhập
    public function hien_thi_dang_nhap() {
        include __DIR__ . '/../GiaoDien/trang/dang_nhap.php';
    }

    // Hiển thị form đăng ký
    public function hien_thi_dang_ky() {
        include __DIR__ . '/../GiaoDien/trang/dang_ky.php';
    }

    // Hiển thị form quên mật khẩu
    public function hien_thi_quen_mat_khau() {
        include __DIR__ . '/../GiaoDien/trang/quen_mat_khau.php';
    }

    // Hiển thị form đặt lại mật khẩu
    public function hien_thi_dat_lai_mat_khau() {
        include __DIR__ . '/../GiaoDien/trang/dat_lai_mat_khau.php';
    }

    // Xử lý logic đăng ký
    public function xu_ly_dang_ky() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($fullname) || empty($email) || empty($password)) {
                header('Location: index.php?act=dang_ky&error=empty_fields');
                exit;
            }

            if ($password !== $confirm_password) {
                header('Location: index.php?act=dang_ky&error=password_mismatch');
                exit;
            }

            $userModel = new NguoiDung($this->db);

            if ($userModel->findUserByEmail($email)) {
                header('Location: index.php?act=dang_ky&error=email_exists');
                exit;
            }

            if ($userModel->register($fullname, $email, $password)) {
                header('Location: index.php?act=dang_nhap&success=registered');
                exit;
            } else {
                header('Location: index.php?act=dang_ky&error=registration_failed');
                exit;
            }
        }
    }

    // Xử lý logic đăng nhập
    public function xu_ly_dang_nhap() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                header('Location: index.php?act=dang_nhap&error=empty_fields');
                exit;
            }

            $userModel = new NguoiDung($this->db);
            $user = $userModel->login($email, $password);

            
            if ($user) {
                // đăng nhập thành công lưu thông tin 
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_fullname'] = $user['fullname'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // PHÂN LOẠI VÀ CHUYỂN HƯỚNG DỰA TRÊN VAI TRÒ
                if ($user['role'] === 'admin' || $user['role'] === 'staff') {
                    // Nếu là admin hoặc nhân viên, chuyển đến trang quản trị
                    header('Location: admin.php');
                } elseif (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
                    // Nếu có trang chuyển hướng được chỉ định (ví dụ: từ giỏ hàng, tài khoản)
                    header('Location: index.php?act=' . urlencode($_POST['redirect']));
                } else {
                    // Nếu là người dùng thường, chuyển về trang chủ
                    header('Location: index.php?act=trangchu');
                }
                exit;
            } else {
                // Nếu thông tin không hợp lệ, quay lại trang đăng nhập với thông báo lỗi
                header('Location: index.php?act=dang_nhap&error=invalid_credentials');
                exit;
            }
        }
    }

    // Xử lý đăng xuất
    public function dang_xuat() {
        session_unset();
        session_destroy();
        header('Location: index.php?act=trangchu');
        exit;
    }

    // Xử lý yêu cầu quên mật khẩu
    public function xu_ly_quen_mat_khau() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: index.php?act=quen_mat_khau&error=invalid_email');
                exit;
            }

            $userModel = new NguoiDung($this->db);
            $user = $userModel->findUserByEmail($email);

            // Để tăng cường bảo mật, chúng ta luôn hiển thị thông báo thành công
            // ngay cả khi email không tồn tại, để tránh việc kẻ xấu dò email.
            if ($user) {
                // 1. Tạo token và thời gian hết hạn
                $token = bin2hex(random_bytes(32)); // Tạo token ngẫu nhiên, an toàn
                $expires = date("U") + 1800; // Token hết hạn sau 30 phút

                // 2. Lưu token vào CSDL (bạn cần thêm hàm này vào Model/NguoiDung.php)
                $userModel->savePasswordResetToken($email, $token, $expires);

                // 3. Gửi email cho người dùng (PHẦN NÀY CẦN CẤU HÌNH SERVER MAIL)
                // Đây là ví dụ, bạn cần một thư viện như PHPMailer để gửi email thật
                $reset_link = "http://localhost/web-/index.php?act=dat_lai_mat_khau&token=" . $token;
                
                // mail($email, "Yêu cầu đặt lại mật khẩu", "Nhấp vào liên kết sau để đặt lại mật khẩu: " . $reset_link);
                
                // Vì chưa cấu hình gửi mail, chúng ta sẽ tạm thời chuyển hướng với thông báo
                // và có thể hiển thị link để test.
                // TRONG THỰC TẾ, BẠN SẼ XÓA DÒNG NÀY VÀ CHỈ HIỂN THỊ THÔNG BÁO
                // echo "Link đặt lại (chỉ để test): <a href='$reset_link'>$reset_link</a>";
            }

            // Chuyển hướng về trang quên mật khẩu với thông báo thành công
            header('Location: index.php?act=quen_mat_khau&success=sent');
            exit;
        }
    }

    // Xử lý việc đặt lại mật khẩu mới
    public function xu_ly_dat_lai_mat_khau() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validate dữ liệu đầu vào
            if (empty($token) || empty($password) || $password !== $confirm_password) {
                header('Location: index.php?act=dat_lai_mat_khau&token=' . $token . '&error=invalid_data');
                exit;
            }

            $userModel = new NguoiDung($this->db);
            
            // Tìm người dùng bằng token và kiểm tra token có hợp lệ không
            $user = $userModel->findUserByResetToken($token);

            if (!$user) {
                // Token không hợp lệ hoặc đã hết hạn
                header('Location: index.php?act=dat_lai_mat_khau&token=' . $token . '&error=invalid_token');
                exit;
            }

            // Cập nhật mật khẩu mới (bạn cần thêm hàm này vào Model/NguoiDung.php)
            $success = $userModel->updatePassword($user['id'], $password);

            if ($success) {
                // Chuyển hướng đến trang đăng nhập với thông báo thành công
                header('Location: index.php?act=dang_nhap&success=reset');
                exit;
            } else {
                // Lỗi khi cập nhật CSDL
                header('Location: index.php?act=dat_lai_mat_khau&token=' . $token . '&error=update_failed');
                exit;
            }
        }
    }
}