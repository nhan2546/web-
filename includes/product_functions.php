<?php
/**
 * Helper functions for product CRUD using mysqli $conn
 */

/**
 * Insert a new product into `products` table.
 * @param mysqli $conn
 * @param array $data keys: name, description, price, stock_quantity, category_id, image_url
 * @return bool
 */
function addProduct($conn, array $data) {
    $sql = "INSERT INTO products (name, description, price, image_url, stock_quantity, category_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    // Ensure numeric types are cast correctly
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price = isset($data['price']) ? (float)$data['price'] : 0.0;
    $image_url = $data['image_url'] ?? '';
    $stock_quantity = isset($data['stock_quantity']) ? (int)$data['stock_quantity'] : 0;
    $category_id = isset($data['category_id']) ? (int)$data['category_id'] : null;

    // If category_id is null, bind as NULL (use 'i' and pass null will convert to 0; to set NULL properly we'd need dynamic SQL)
    $stmt->bind_param('ssdsii', $name, $description, $price, $image_url, $stock_quantity, $category_id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

?>
