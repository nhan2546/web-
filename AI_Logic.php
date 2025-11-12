<?php
// Tệp: AI_Logic.php
// Bộ não xử lý trung tâm, dùng cho cả web-chat và telegram.

require_once 'MoHinh/CSDL.php';

// --- CẤU HÌNH AI (VÍ DỤ VỚI GEMINI) ---
// BẠN PHẢI LẤY API KEY CỦA RIÊNG MÌNH TỪ GOOGLE AI STUDIO
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY'));
// DÒNG CẬP NHẬT (Dùng API v1 ổn định và model Flash mới nhất)
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash-latest:generateContent?key=' . GEMINI_API_KEY);
/**
 * Hàm chính để xử lý tin nhắn
 * @param string $userMessage Tin nhắn từ người dùng
 * @return string Phản hồi từ bot
 */
function getBotReply($userMessage) {
    $db = new CSDL();
    $lowerMessage = strtolower(trim($userMessage));

    // --- Ưu tiên 1: Lấy thông tin sản phẩm từ CSDL ---
    if (str_contains($lowerMessage, 'sản phẩm mới') || str_contains($lowerMessage, 'hàng mới')) {
        return getLatestProducts($db);
    }

    if (str_contains($lowerMessage, 'tìm sản phẩm')) {
        // Tách từ khóa, ví dụ: "tìm sản phẩm iphone 15"
        $parts = explode(' ', $lowerMessage, 3);
        $keyword = $parts[2] ?? '';
        return searchProducts($db, $keyword);
    }
    
    // --- Ưu tiên 2: Gọi AI để tư vấn/trò chuyện ---
    // Nếu không khớp các từ khóa CSDL, hãy để AI trả lời
    return getAIReply($userMessage);
}

/**
 * Truy vấn CSDL để lấy sản phẩm mới nhất
 */
function getLatestProducts($db) {
    try {
        // Giả sử bảng của bạn là 'san_pham' và có cột 'ngay_nhap' hoặc 'id'
        $products = $db->read("SELECT ten_sp, gia_sp FROM san_pham ORDER BY id DESC LIMIT 3");

        if (empty($products)) {
            return "Hiện tại cửa hàng chưa có sản phẩm mới nào ạ.";
        }

        $response = "Dưới đây là 3 sản phẩm mới nhất của shop:\n";
        foreach ($products as $sp) {
            $response .= "- " . $sp['ten_sp'] . " (Giá: " . number_format($sp['gia_sp']) . " VNĐ)\n";
        }
        return $response;

    } catch (Exception $e) {
        return "Xin lỗi, tôi không thể truy cập danh sách sản phẩm lúc này.";
    }
}

/**
 * Truy vấn CSDL để tìm sản phẩm
 */
function searchProducts($db, $keyword) {
    if (empty($keyword)) {
        return "Bạn muốn tìm sản phẩm gì ạ? (Ví dụ: tìm sản phẩm iPhone 15)";
    }
    try {
        $products = $db->read("SELECT ten_sp, gia_sp FROM san_pham WHERE ten_sp LIKE ?", ['%' . $keyword . '%']);

        if (empty($products)) {
            return "Tôi không tìm thấy sản phẩm nào có chứa từ khóa '" . htmlspecialchars($keyword) . "'.";
        }

        $response = "Tôi tìm thấy " . count($products) . " sản phẩm liên quan:\n";
        foreach ($products as $sp) {
            $response .= "- " . $sp['ten_sp'] . " (Giá: " . number_format($sp['gia_sp']) . " VNĐ)\n";
        }
        return $response;

    } catch (Exception $e) {
        return "Xin lỗi, tôi gặp lỗi khi tìm kiếm sản phẩm.";
    }
}

/**
 * Gọi API AI (Gemini) để trả lời các câu hỏi tư vấn
 */
function getAIReply($userMessage) {
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => "Bạn là trợ lý AI cho một shop bán máy tính tên là Táo Ngon. Hãy tư vấn cho khách hàng một cách thân thiện. Câu hỏi: " . $userMessage]
                ]
            ]
        ]
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents(GEMINI_API_URL, false, $context);

    if ($result === FALSE) {
        return "Xin lỗi, AI đang bảo trì. Bạn vui lòng thử lại sau.";
    }

    $response = json_decode($result, true);

    if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
        return $response['candidates'][0]['content']['parts'][0]['text'];
    } else {
        // Ghi lại lỗi để debug
        error_log("Lỗi API Gemini: " . $result);
        return "Xin lỗi, tôi chưa hiểu ý bạn. Bạn có thể hỏi về sản phẩm mới không?";
    }
}
?>




