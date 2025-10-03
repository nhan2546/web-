<?php
include_once __DIR__ . '/../MoHinh/NguoiDung.php';

class DieuKhienXacThuc {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Hiển thị form đăng nhập
    public function hien_thi_dang_nhap() {
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/dau_trang.php';
        include __DIR__ . '/../GiaoDien/trang/dang_nhap.php';
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/chan_trang.php';
    }

    // Hiển thị form đăng ký
    public function hien_thi_dang_ky() {
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/dau_trang.php';
        include __DIR__ . '/../GiaoDien/trang/dang_ky.php';
        include __DIR__ . '/../GiaoDien/trang/bo_cuc/chan_trang.php';
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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_fullname'] = $user['fullname'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                header('Location: index.php?act=trangchu');
                exit;
            } else {
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
}
?>