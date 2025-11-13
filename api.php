<?php
/* -----------------------------------------------------------
   api.php – Endpoint cho chatbot
   ---------------------------------------------------------- */
header('Content-Type: application/json');

// ---- 1. Debug (bỏ khi đưa lên production) -----------------
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ---- 2. Đưa vào logic AI -----------------------------------
require_once __DIR__ . '/AI_Logic.php';   // <-- đường dẫn tới file AI của bạn

// ---- 3. Đọc body JSON --------------------------------------
$rawInput = file_get_contents('php://input');
$payload   = json_decode($rawInput, true);

// ---- 4. Kiểm tra message ------------------------------------
if (!isset($payload['message']) || trim($payload['message']) === '') {
    echo json_encode(['error' => 'Lỗi: Không nhận được tin nhắn.']);
    exit;
}
$userMessage = trim($payload['message']);

/* -----------------------------------------------------------
   5. Gọi bot (có thể đổi tên hàm tùy bạn)
   ---------------------------------------------------------- */
$botResponse = getAIReply($userMessage);   // hàm được định nghĩa trong AI_Logic.php

// ---- 6. Trả về JSON cho front‑end ---------------------------
echo json_encode(['response' => $botResponse]);
exit;
?>
