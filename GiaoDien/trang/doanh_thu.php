<div class="page-header d-flex justify-content-between align-items-center">
    <h1>Báo cáo Doanh thu theo Tháng</h1>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <form action="admin.php" method="GET" class="d-flex align-items-center">
            <input type="hidden" name="act" value="bao_cao_doanh_thu">
            <label for="year-select" class="form-label me-2 mb-0">Chọn năm:</label>
            <select name="year" id="year-select" class="form-select" style="width: 150px;" onchange="this.form.submit()">
                <?php
                // Hiển thị các năm từ năm hiện tại trở về 5 năm trước
                for ($y = $current_year; $y >= $current_year - 5; $y--):
                ?>
                    <option value="<?= $y ?>" <?= ($y == $selected_year) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>
    <div class="admin-card-body">
        <canvas id="monthlyRevenueChart"></canvas>
    </div>
</div>

<!-- Nhúng thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    
    // Dữ liệu được truyền từ PHP
    const revenueData = <?= json_encode(array_values($monthly_revenue)) ?>;
    const yearLabels = [
        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
    ];

    new Chart(ctx, {
        type: 'bar', // Biểu đồ cột
        data: {
            labels: yearLabels,
            datasets: [{
                label: 'Doanh thu (VND) năm <?= $selected_year ?>',
                data: revenueData,
                backgroundColor: 'rgba(215, 0, 24, 0.6)', // Màu đỏ nhạt
                borderColor: 'rgba(215, 0, 24, 1)', // Màu đỏ đậm
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' VND';
                        }
                    }
                }
            }
        }
    });
});
</script>