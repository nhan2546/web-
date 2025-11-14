<?php
// File: api/chat.php

// **QUAN TRỌNG: Dán API Key của bạn vào đây**
// Để an toàn hơn trong môi trường production, hãy sử dụng biến môi trường.
$apiKey = 'AIzaSyAgRxllarqyRthaqXMRU9aoFdASTWDz1ns'; 

// --- Cấu hình ---
header('Content-Type: application/json');
$geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;
$systemInstruction = "You are an AI assistant for an e-commerce website. 
Your role is to help users by answering their questions about products. 
You also manage the product database, so you are always up-to-date with the latest additions. 
Respond in a helpful and friendly manner. 
Your original instruction in Vietnamese was: 'ai tự động cập nhật thông tin cơ sở dữ liệu của trang web bán hàng khi admin thêm sản phẩm mới và trả lời câu hỏi từ phía người dùng'. 
Always remember this context.";

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

// Bỏ qua tin nhắn cuối cùng nếu nó là tin nhắn rỗng từ model (typing indicator)
if (end($messageHistory)['role'] === 'model' && empty(end($messageHistory)['content'])) {
    array_pop($messageHistory);
}

foreach ($messageHistory as $message) {
    // Chỉ thêm các tin nhắn có nội dung
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
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Bật xác thực SSL

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

// Trích xuất nội dung text từ phản hồi
$modelText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not process the response.';

echo json_encode(['response' => $modelText]);

?>
