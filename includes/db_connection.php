<?php
// MySQLi connection used by older scripts (e.g. process_resister.php)
// Adjust credentials if your XAMPP/MySQL uses a different user/password
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'store_db';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_errno) {
	// In production you might log this instead of displaying
	die('Kết nối CSDL thất bại: (' . $conn->connect_errno . ') ' . $conn->connect_error);
}

// Set charset to utf8mb4
if (!$conn->set_charset('utf8mb4')) {
	// Non-fatal: continue but you may want to handle this
	error_log('Không thể đặt charset utf8mb4: ' . $conn->error);
}

?>
