<div class="page-header">
    <h1>Tổng Doanh Thu</Table></h1>
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
</div>

<!-- Vùng chứa biểu đồ -->
<div class="admin-card mt-4">
    <div class="admin-card-header">
        Thống kê Doanh thu (12 tháng gần nhất)
    </div>
    <div class="admin-card-body">
        <canvas id="revenueChart"></canvas>
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
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line', // Loại biểu đồ
                data: {
                    labels: chartData.labels, // Nhãn trục X (các tháng)
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: chartData.data, // Dữ liệu doanh thu
                        borderColor: 'rgba(52, 152, 219, 1)',
                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Định dạng lại số trên trục Y cho dễ đọc
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + ' VND';
                                }
                            }
                        }
                    }
                }
            });
        });
});
</script>
