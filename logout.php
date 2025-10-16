<?php
/*bắt đầu session*/
session_start();
/*xóa tất cả các biến session*/
$_SESSION = [];
/*hủy session*/
session_destroy;
/*chuyển hướng về trang đăng nhập*/
header("Location: GiaoDien/trang/dang_nhap.php.php");
exit();    