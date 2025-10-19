<div class="page-header">
    <h1>Tổng Doanh Thu</Table></h1>
    <h1>Tổng quan</h1>
</div>

<!-- Lưới các thẻ thống kê -->
<div class="dashboard-cards">
    <!-- Thẻ Doanh thu -->
    <div class="dashboard-card">
        <div class="card-title">Tổng Doanh thu</div>
        <div class="card-value">
            <?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?> VND
        </div>
    </div>

    <!-- Thẻ Đơn hàng mới -->
    <div class="dashboard-card">
        <div class="card-title">Đơn hàng mới</div>
        <div class="card-value">
            <?php echo $stats['new_orders_count']; ?>
        </div>
    </div>

    <!-- Thẻ Khách hàng -->
    <div class="dashboard-card">
        <div class="card-title">Tổng số Khách hàng</div>
        <div class="card-value">
            <?php echo $stats['customer_count']; ?>
        </div>
    </div>

    <!-- Thẻ Sản phẩm -->
    <div class="dashboard-card">
        <div class="card-title">Tổng số Sản phẩm</div>
        <div class="card-value">
            <?php echo $stats['product_count']; ?>
        </div>
    </div>

    <!-- Thẻ Lượt truy cập -->
    <div class="dashboard-card">
        <div class="card-title">Tổng lượt truy cập</div>
        <div class="card-value">
            <?php echo number_format($stats['total_visits'], 0, ',', '.'); ?>
        </div>
    </div>
</div>

<!-- Vùng chứa các bảng sản phẩm bán chạy/ế -->
<div class="dashboard-cards">
    <!-- Thẻ sản phẩm bán chạy -->
    <div class="admin-card">
        <div class="admin-card-header">
            Top 5 Sản phẩm bán chạy nhất
        </div>
        <div class="admin-card-body">
            <table class="admin-table simple-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Đã bán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['best_selling_products'] as $product): ?>
                    <tr>
                        <td>
                            <div class="product-info-cell">
                                <img src="TaiLen/san_pham/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <span><?php echo htmlspecialchars($product['name']); ?></span>
                            </div>
                        </td>
                        <td class="text-center"><?php echo $product['total_sold']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Thẻ sản phẩm bán ế -->
    <div class="admin-card">
        <div class="admin-card-header">
            Top 5 Sản phẩm bán chậm nhất
        </div>
        <div class="admin-card-body">
            <table class="admin-table simple-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Đã bán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['worst_selling_products'] as $product): ?>
                    <tr>
                        <td>
                            <div class="product-info-cell">
                                <img src="TaiLen/san_pham/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <span><?php echo htmlspecialchars($product['name']); ?></span>
                            </div>
                        </td>
                        <td class="text-center"><?php echo $product['total_sold']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Vùng chứa biểu đồ -->
<div class="admin-card">
    <div class="admin-card-header">
        Thống kê Doanh thu (12 tháng gần nhất)
    </div>
    <div class="admin-card-body">
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Nhúng thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Lấy dữ liệu từ AJAX endpoint
    fetch('admin.php?act=ajax_get_chart_data')
        .then(response => response.json())
        .then(chartData => {
            // Tính tổng doanh thu để tính phần trăm
            const totalRevenue = chartData.data.reduce((sum, value) => sum + value, 0);

            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie', // Thay đổi: Loại biểu đồ tròn
                data: {
                    labels: chartData.labels, // Nhãn trục X (các tháng)
                    datasets: [{
                        label: 'Doanh thu',
                        data: chartData.data, // Dữ liệu doanh thu
                        // Thêm một mảng màu sắc cho từng phần của biểu đồ tròn
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ],
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top', // Hiển thị chú thích các tháng ở trên
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const percentage = totalRevenue > 0 ? ((value / totalRevenue) * 100).toFixed(2) : 0;

                                    const formattedValue = new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(value);

                                    return `${label}: ${formattedValue} (${percentage}%)`;
                                }
                            }
                        }
                    }
                    // Biểu đồ tròn không cần trục X, Y (scales)
                }
            });
        });
});
</script>
