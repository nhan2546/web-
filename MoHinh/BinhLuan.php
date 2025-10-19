<?php

class BinhLuan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Thêm một bình luận/đánh giá mới vào CSDL.
     */
    public function addReview($product_id, $user_id, $rating, $comment, $parent_id = null, $image_url = null) {
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, parent_id, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$product_id, $user_id, $rating, $comment, $parent_id, $image_url]);
    }

    /**
     * Lấy tất cả bình luận của một sản phẩm, kèm thông tin người dùng.
     */
    public function getReviewsByProductId($product_id, $filters = []) {
        // Subquery để kiểm tra xem người dùng đã mua sản phẩm này chưa
        $verified_purchase_subquery = "
            EXISTS (
                SELECT 1 FROM orders o
                JOIN order_details od ON o.id = od.order_id
                WHERE o.user_id = r.user_id AND od.product_id = r.product_id AND o.status = 'delivered'
            )
        ";

        $sql = "SELECT r.*, u.fullname, u.avatar_url, ($verified_purchase_subquery) as is_verified_purchase
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = ? 
        ";

        $params = [$product_id];

        if (!empty($filters['rating'])) {
            $sql .= " AND r.rating = ?";
            $params[] = $filters['rating'];
        }
        if (!empty($filters['has_image'])) {
            $sql .= " AND r.image_url IS NOT NULL AND r.image_url != ''";
        }

        $sql .= " ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
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

    /**
     * Đếm số lượng đánh giá cho mỗi mức sao (1 đến 5).
     */
    public function getRatingCounts($product_id) {
        $sql = "SELECT 
                    rating, 
                    COUNT(id) as count 
                FROM reviews 
                WHERE product_id = ? AND rating > 0
                GROUP BY rating";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$product_id]);
        // Chuyển đổi kết quả thành dạng [5 => count, 4 => count, ...]
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}