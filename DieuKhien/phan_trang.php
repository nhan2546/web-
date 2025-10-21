<?php
// Các biến $total_pages, $current_page được controller chuẩn bị.
// Hàm generate_filter_url() sẽ được định nghĩa trong view (ví dụ: danh_sach_san_pham.php)
// để giữ lại các bộ lọc khi chuyển trang.
?>

<?php if (isset($total_pages) && $total_pages > 1): ?>
<nav class="cp-pagination" aria-label="Page navigation">
    <ul class="pagination-list">
        <!-- Nút Trang Trước -->
        <?php if ($current_page > 1): ?>
            <li class="pagination-item">
                <a href="<?= generate_filter_url(['page' => $current_page - 1]) ?>" class="pagination-link">‹</a>
            </li>
        <?php else: ?>
            <li class="pagination-item disabled">
                <span class="pagination-link">‹</span>
            </li>
        <?php endif; ?>

        <!-- Các nút số trang -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="pagination-item <?= ($i == $current_page) ? 'active' : '' ?>">
                <a href="<?= generate_filter_url(['page' => $i]) ?>" class="pagination-link"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Nút Trang Sau -->
        <?php if ($current_page < $total_pages): ?>
            <li class="pagination-item">
                <a href="<?= generate_filter_url(['page' => $current_page + 1]) ?>" class="pagination-link">›</a>
            </li>
        <?php else: ?>
            <li class="pagination-item disabled">
                <span class="pagination-link">›</span>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>