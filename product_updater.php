<?php
// product_updater.php - Thêm vào sau khi admin thêm sản phẩm
require_once 'CSDL.php';

class ProductNotifier {
     private $db;
    private $botToken;
    
    public function __construct() {
        $this->db = new CSDL();
        // === THAY THẾ TOKEN THỰC TẾ ===
        $this->botToken = "8542742273:AAFQ93vtvO2ImMTAUABmOhi4jN3h6-PMPRw"; // THAY TOKEN CỦA BẠN
    }
    
    public function notifyNewProduct($productId) {
        $product = $this->getProduct($productId);
        $subscribers = $this->getSubscribers();
        
        if ($product && !empty($subscribers)) {
            $message = $this->createNewProductMessage($product);
            
            foreach ($subscribers as $chatId) {
                $this->sendTelegramMessage($chatId, $message);
            }
        }
    }
    
    private function getProduct($productId) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $result = $this->db->read($sql, [$productId]);
        return $result[0] ?? null;
    }
    
    private function getSubscribers() {
        // Trong thực tế, bạn cần bảng lưu subscriber
        // Tạm thời trả về mảng rỗng
        return [];
    }
    
    private function createNewProductMessage($product) {
        $price = number_format($product['price'], 0, ',', '.');
        $salePrice = $product['sale_price'] ? number_format($product['sale_price'], 0, ',', '.') : null;
        
        $message = "🆕 SẢN PHẨM MỚI!\n\n";
        $message .= "📱 {$product['name']}\n\n";
        
        if ($salePrice) {
            $message .= "💵 Giá: ~~{$price} VNĐ~~\n";
            $message .= "🎯 Khuyến mãi: {$salePrice} VNĐ\n";
        } else {
            $message .= "💵 Giá: {$price} VNĐ\n";
        }
        
        $message .= "📦 Số lượng: {$product['stock_quantity']}\n\n";
        $message .= "🛒 Xem ngay: " . $this->getProductUrl($product['id']);
        
        return $message;
    }
    
    private function sendTelegramMessage($chatId, $text) {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];
        
        $this->makeRequest($url, $data);
    }
    
    private function makeRequest($url, $data) {
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }
    
    private function getProductUrl($productId) {
        return "https://yourwebsite.com/index.php?act=chi_tiet_san_pham&id={$productId}";
    }
}

// Sử dụng: Gọi hàm này sau khi thêm sản phẩm
// $notifier = new ProductNotifier();
// $notifier->notifyNewProduct($newProductId);
?>