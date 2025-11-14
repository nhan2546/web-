<?php
// File: api.php

// --- BẮT ĐẦU: MÃ SỬA LỖI CORS ---
header("Access-Control-Allow-Origin: https://web-chat-bot-php.onrender.com"); // URL của chatbot trên Render
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// --- KẾT THÚC: MÃ SỬA LỖI CORS ---


// --- BẮT ĐẦU: ĐỌC API KEY TỪ BIẾN MÔI TRƯỜNG (AN TOÀN) ---
// Lấy key từ biến môi trường mà bạn đã thiết lập trên Render
$apiKey = getenv('GEMINI_API_KEY');

// Kiểm tra xem key có tồn tại không
if (!$apiKey) {
    http_response_code(500);
    // Gửi thông báo lỗi cho frontend để dễ dàng gỡ rối
    echo json_encode(['error' => 'GEMINI_API_KEY is not set on the server environment.']);
    exit;
}
// --- KẾT THÚC: ĐỌC API KEY TỪ BIẾN MÔI TRƯỜDNG ---


// --- Cấu hình ---
header('Content-Type: application/json');
$geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;
$systemInstruction = "You are an AI assistant for an e-commerce website named 'Shop Táo Ngon'. Your role is to help users by answering their questions about Apple products. Always be helpful, friendly, and professional. Respond in Vietnamese.";

// --- Xử lý yêu cầu ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method.']);
    http_response_code(405);
    exit;
}

$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

if (json_last_error() !== JSON_ERROR_NONE || !isset($data['messages']) || !is_array($data['messages'])) {
    echo json_encode(['error' => 'Invalid JSON input.']);
    http_response_code(400);
    exit;
}

// --- Chuyển đổi lịch sử tin nhắn sang định dạng của Gemini ---
$contents = [];
$messageHistory = $data['messages'];

if (end($messageHistory)['role'] === 'model' && empty(end($messageHistory)['content'])) {
    array_pop($messageHistory);
}

foreach ($messageHistory as $message) {
    if (!empty($message['content'])) {
         $contents[] = [
            'role' => $message['role'],
            'parts' => [['text' => $message['content']]]
        ];
    }
}

// --- Gửi yêu cầu đến Gemini API ---
$payload = [
    'contents' => $contents,
    'systemInstruction' => [
        'parts' => [['text' => $systemInstruction]]
    ]
];

$ch = curl_init($geminiApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// --- Xử lý phản hồi từ Gemini và gửi lại cho Frontend ---
if ($response === false || $httpcode >= 400) {
    http_response_code($httpcode > 0 ? $httpcode : 500);
    echo json_encode(['error' => 'Failed to connect to Gemini API.', 'details' => $curlError, 'api_response' => $response]);
    exit;
}

$responseData = json_decode($response, true);
$modelText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not process the response.';

echo json_encode(['response' => $modelText]);

?>
