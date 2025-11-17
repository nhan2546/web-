<?php
/* --------------------------------------------------------------
 *  CORS – cho phép frontend (origin khác) gọi API
 * -------------------------------------------------------------- */
header('Access-Control-Allow-Origin: *');          // Production: ghi domain frontend thay *
header('Access-Control-Allow-Methods: GET,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/* --------------------------------------------------------------
 *  ĐỌC CÁC BIẾN MÔI TRƯỜNG MYSQL
 * -------------------------------------------------------------- */
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

if (!$host || !$port || !$db || !$user) {
    http_response_code(500);
    echo json_encode(['error' => 'Missing DB environment variables']);
    exit;
}

/* --------------------------------------------------------------
 *  TẠO DSN PDO cho MySQL
 * -------------------------------------------------------------- */
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

/* --------------------------------------------------------------
 *  LẤY TỪ KHÓA ?q=…
 * -------------------------------------------------------------- */
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search === '') {
    header('Content-Type: application/json');
    echo json_encode(['products' => []]);
    exit;
}

/* --------------------------------------------------------------
 *  TRUY VẤN DATABASE (MySQL không có ILIKE – dùng LIKE)
 * -------------------------------------------------------------- */
// *** ĐÂY LÀ DÒNG ĐÃ ĐƯỢC CẬP NHẬT ***
// Thêm `sale_price` và `promotion` để AI có thể đọc được thông tin khuyến mãi
$sql = <<<SQL
SELECT id, name, price, sale_price, description, promotion
FROM products
WHERE name LIKE :q
ORDER BY name
LIMIT 10
SQL;

$stmt = $pdo->prepare($sql);
$stmt->execute([':q' => "%$search%"]);
$rows = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode(['products' => $rows]);
