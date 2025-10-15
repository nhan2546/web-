<?php

class BinhLuan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Thêm một bình luận/đánh giá mới vào CSDL.
     */
    public function addReview($product_id, $user_id, $rating, $comment) {
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$product_id, $user_id, $rating, $comment]);
    }

    /**
     * Lấy tất cả bình luận của một sản phẩm, kèm thông tin người dùng.
     */
    public function getReviewsByProductId($product_id) {
        $sql = "SELECT r.*, u.fullname, u.avatar_url 
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = ?
                ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tính toán số sao trung bình và tổng số lượt đánh giá của một sản phẩm.
     */
    public function getAverageRating($product_id) {
        $sql = "SELECT 
                    COUNT(id) as review_count, 
                    AVG(rating) as average_rating 
                FROM reviews 
                WHERE product_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$product_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Xử lý để tránh lỗi khi chưa có đánh giá nào
        if ($result) {
            $result['review_count'] = (int)$result['review_count'];
            $result['average_rating'] = $result['average_rating'] ? round($result['average_rating'], 1) : 0;
        }

        return $result;
    }
}