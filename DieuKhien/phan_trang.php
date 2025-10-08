<?php if (isset($total_pages) && $total_pages > 1): ?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-5">
        <!-- Nút Previous -->
        <?php if ($current_page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="index.php?act=hienthi_sp&page=<?= $current_page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Các nút số trang -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                <a class="page-link" href="index.php?act=hienthi_sp&page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Nút Next -->
        <?php if ($current_page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="index.php?act=hienthi_sp&page=<?= $current_page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>