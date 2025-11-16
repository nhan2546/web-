<?php
/* --------------------------------------------------------------
 *  CORS – cho phép frontend gọi API từ domain khác
 * -------------------------------------------------------------- */
header('Access-Control-Allow-Origin: *');   // production: thay * bằng domain frontend
header('Access-Control-Allow-Methods: GET,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/* --------------------------------------------------------------
 *  KẾT NỐI DATABASE (đọc biến môi trường DATABASE_URL do Render cung cấp)
 * -------------------------------------------------------------- */
$dsn = getenv('DATABASE_URL');   // Render injects this variable
if (!$dsn) {
    http_response_code(500);
    echo json_encode(['error' => 'DATABASE_URL not set']);
    exit;
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, null, null, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

/* --------------------------------------------------------------
 *  ĐỌC PARAMETERS – ?q=… (câu hỏi người dùng)
 * -------------------------------------------------------------- */
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search === '') {
    header('Content-Type: application/json');
    echo json_encode(['products' => []]);
    exit;
}

/* --------------------------------------------------------------
 *  TRUY VẤN DATABASE (ILIKE = case‑insensitive LIKE trên PostgreSQL)
 * -------------------------------------------------------------- */
$sql = <<<SQL
SELECT id, name, price, description
FROM products
WHERE name ILIKE :q
ORDER BY name
LIMIT 10
SQL;

$stmt = $pdo->prepare($sql);
$stmt->execute([':q' => "%$search%"]);
$rows = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode(['products' => $rows]);
