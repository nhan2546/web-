<?php  include 'pages/header.php'; ?>

<h2>Đăng Nhập</h2>
<form method="post" action="process_login.php">
    <label>Email:</label>
    <input type="email" name="email"><br>
    <label>Mật Khẩu:</label>
    <input type="password" name="password" required><br>
    <button type="submit">Đăng Nhập</button>
    </form>
    <?php include 'pages/footer.php'; ?>