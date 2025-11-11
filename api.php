<?php
// Tệp: api.php
// Điểm cuối (endpoint) cho web-chat từ chan_trang.php

require_once 'AI_Logic.php';

// 1. Nhận dữ liệu JSON từ web-chat
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(['response' => 'Lỗi: Không nhận được tin nhắn.']);
    exit;
}

// 2. Gọi bộ não AI
$botResponse = getBotReply($userMessage);

// 3. Trả về kết quả dạng JSON
header('Content-Type: application/json');
echo json_encode(['response' => $botResponse]);
?>
