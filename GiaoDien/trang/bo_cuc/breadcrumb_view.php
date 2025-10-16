<?php
// File: GiaoDien/trang/bo_cuc/breadcrumb_view.php

// Không hiển thị breadcrumb trên trang chủ
if ($act === 'trangchu') {
    return;
}
?>

<div class="cp-container">
    <nav class="cp-breadcrumb" aria-label="breadcrumb">
        <ol>
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <li>
                    <?php if (isset($crumb['url']) && $crumb['url'] !== null && $index < count($breadcrumbs) - 1): ?>
                        <a href="<?php echo htmlspecialchars($crumb['url']); ?>">
                            <?php echo htmlspecialchars($crumb['title']); ?>
                        </a>
                    <?php else: ?>
                        <span><?php echo htmlspecialchars($crumb['title']); ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>

<!-- JSON-LD for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    <?php foreach ($breadcrumbs as $index => $crumb): ?>
    {
      "@type": "ListItem",
      "position": <?php echo $index + 1; ?>,
      "name": "<?php echo htmlspecialchars($crumb['title']); ?>",
      "item": "<?php echo (isset($crumb['url']) && $crumb['url'] !== null) ? 'http://localhost/web-/' . htmlspecialchars($crumb['url']) : htmlspecialchars($_SERVER['REQUEST_URI']); ?>"
    }<?php echo ($index < count($breadcrumbs) - 1) ? ',' : ''; ?>
    <?php endforeach; ?>
  ]
}
</script>