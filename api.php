<?php
/* ---- CORS (giữ nguyên) ---- */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/* ---- KẾT NỐI DATABASE BẰNG CÁC BIẾN DB_*** ---- */
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

// Đối với MySQL PDO DSN
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

/* ---- ĐỌC PARAMETERS – ?q=… (giữ nguyên) ---- */
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search === '') {
    header('Content-Type: application/json');
    echo json_encode(['products' => []]);
    exit;
}

/* ---- TRUY VẤN DATABASE ---- */
$sql = <<<SQL
SELECT id, name, price, description
FROM products
WHERE name LIKE :q         -- MySQL không có ILIKE, dùng LIKE (case‑insensitive nếu collation là utf8_general_ci)
ORDER BY name
LIMIT 10
SQL;

$stmt = $pdo->prepare($sql);
$stmt->execute([':q' => "%$search%"]);
$rows = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode(['products' => $rows]);
