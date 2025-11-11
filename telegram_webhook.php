<?php
// Tệp: telegram_webhook.php
// Điểm cuối (endpoint) cho Telegram Bot

require_once 'AI_Logic.php';

// --- CẤU HÌNH TELEGRAM ---
define('TELEGRAM_TOKEN', '7810658991:AAHDpLTq0u7_9rp3qBh19QVAmM4lAwSjJsU'); // <-- THAY TOKEN CỦA BẠN VÀO ĐÂY
define('TELEGRAM_API_URL', 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/');

// 1. Nhận tin nhắn từ Telegram
$input = file_get_contents('php://input');
$update = json_decode($input, true);

if (!isset($update['message']['text'])) {
    exit; // Không phải tin nhắn text, bỏ qua
}

$chatId = $update['message']['chat']['id'];
$userMessage = $update['message']['text'];

// 2. Lấy câu trả lời từ "bộ não"
$botResponse = getBotReply($userMessage);

// 3. Gửi trả lời về Telegram
sendTelegramMessage($chatId, $botResponse);


/**
 * Hàm gửi tin nhắn về Telegram
 */
function sendTelegramMessage($chatId, $text) {
    $url = TELEGRAM_API_URL . 'sendMessage';
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'Markdown' // Cho phép dùng \n xuống dòng
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ]
    ];
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>
