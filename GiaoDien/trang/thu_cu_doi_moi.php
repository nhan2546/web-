<div class="trade-in-wrapper">
    <div class="trade-in-header">
        <h1>Thu Cũ Đổi Mới - Lên Đời iPhone</h1>
        <p>Chỉ với 4 bước đơn giản để biết giá trị máy cũ của bạn và sẵn sàng lên đời iPhone mới nhất.</p>
    </div>

    <!-- Step 1: Chọn dòng máy -->
    <div id="step1" class="trade-in-step">
        <h3><span class="step-number">1</span>Chọn dòng máy của bạn</h3>
        <div class="model-grid">
            <div class="model-item" data-model="iPhone 17 Pro Max">iPhone 17 Pro Max</div>
            <div class="model-item" data-model="iPhone 17 Pro">iPhone 17 Pro</div>
            <div class="model-item" data-model="iPhone 16 Pro Max">iPhone 16 Pro Max</div>
            <div class="model-item" data-model="iPhone 16 Pro">iPhone 16 Pro</div>
            <div class="model-item" data-model="iPhone 16 Plus">iPhone 16 Plus</div>
            <div class="model-item" data-model="iPhone 15 Pro Max">iPhone 15 Pro Max</div>
            <div class="model-item" data-model="iPhone 15 Pro">iPhone 15 Pro</div>
            <div class="model-item" data-model="iPhone 15 Plus">iPhone 15 Plus</div>
            <div class="model-item" data-model="iPhone 14 Pro Max">iPhone 14 Pro Max</div>
            <div class="model-item" data-model="iPhone 14 Pro">iPhone 14 Pro</div>
            <div class="model-item" data-model="iPhone 14 Plus">iPhone 14 Plus</div>
            <div class="model-item" data-model="iPhone 14">iPhone 14</div>
            <div class="model-item" data-model="iPhone 13 Pro Max">iPhone 13 Pro Max</div>
            <div class="model-item" data-model="iPhone 13 Pro">iPhone 13 Pro</div>
            <div class="model-item" data-model="iPhone 13">iPhone 13</div>
            <div class="model-item" data-model="iPhone 12 Pro Max">iPhone 12 Pro Max</div>
            <div class="model-item" data-model="iPhone 12 Pro">iPhone 12 Pro</div>
            <div class="model-item" data-model="iPhone 12">iPhone 12</div>
            <div class="model-item" data-model="iPhone 11 Pro Max">iPhone 11 Pro Max</div>
            <div class="model-item" data-model="iPhone 11">iPhone 11</div>
        </div>
    </div>
    <div id="step2" class="trade-in-step" style="display: none;">
        <h3><span class="step-number">2</span>Chọn dung lượng</h3>
        <div class="option-buttons">
            <button class="option-btn" data-capacity="128GB">128GB</button>
            <button class="option-btn" data-capacity="256GB">256GB</button>
            <button class="option-btn" data-capacity="512GB">512GB</button>
            <button class="option-btn" data-capacity="1TB">1TB</button>
        </div>
    </div>
    <div id="step3" class="trade-in-step" style="display: none;">
        <h3><span class="step-number">3</span>Tình trạng máy của bạn như thế nào?</h3>
        <div class="condition-options">
            <label class="condition-item">
                <input type="radio" name="condition" value="good">
                <div class="condition-content">
                    <h4>Loại 1: Tốt</h4>
                    <p>Thân máy và màn hình đẹp như mới, không trầy xước. Mọi chức năng hoạt động hoàn hảo.</p>
                </div>
            </label>
            <label class="condition-item">
                <input type="radio" name="condition" value="fair">
                <div class="condition-content">
                    <h4>Loại 2: Khá</h4>
                    <p>Thân máy trầy xước nhẹ, màn hình có thể xước dăm. Mọi chức năng hoạt động hoàn hảo.</p>
                </div>
            </label>
            <label class="condition-item">
                <input type="radio" name="condition" value="poor">
                <div class="condition-content">
                    <h4>Loại 3: Xấu</h4>
                    <p>Thân máy cấn móp, màn hình trầy xước nhiều hoặc có lỗi hiển thị nhẹ. Các chức năng cơ bản hoạt động.</p>
                </div>
            </label>
        </div>
    </div>

    <!-- Step 4: Kết quả -->
    <div id="step4" class="trade-in-step" style="display: none;">
        <h3><span class="step-number">4</span>Giá trị thu lại dự kiến</h3>
        <div class="trade-in-result">
            <p>Giá thu lại dự kiến cho máy <strong id="result-model-info"></strong> là:</p>
            <h2 id="result-price"></h2>
            <small>*Giá trên chỉ là dự kiến và có thể thay đổi sau khi kỹ thuật viên kiểm tra thực tế.</small>
        </div>
        <div class="trade-in-actions">
            <a href="index.php?act=danhmuc&id=1" class="cp-btn">Lên đời iPhone mới ngay</a>
            <button id="reset-btn" class="cp-btn-secondary">Thử lại từ đầu</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu giá tham khảo (bạn có thể thay bằng API hoặc lấy từ DB)
    // Lưu ý: Giá cho các dòng iPhone 15, 16, 17 là giả định
    const priceData = {
        "iPhone 17 Pro Max": {"256GB": 38000000, "512GB": 42000000, "1TB": 46000000},
        "iPhone 17 Pro":     {"256GB": 35000000, "512GB": 39000000, "1TB": 43000000},
        "iPhone 16 Pro Max": {"256GB": 32000000, "512GB": 36000000, "1TB": 40000000},
        "iPhone 16 Pro":     {"128GB": 29000000, "256GB": 31000000, "512GB": 34000000},
        "iPhone 16 Plus":    {"128GB": 26000000, "256GB": 28000000, "512GB": 31000000},
        "iPhone 15 Pro Max": {"256GB": 26000000, "512GB": 29000000, "1TB": 32000000},
        "iPhone 15 Pro":     {"128GB": 23000000, "256GB": 25000000, "512GB": 28000000, "1TB": 31000000},
        "iPhone 15 Plus":    {"128GB": 20000000, "256GB": 22000000, "512GB": 25000000},
        "iPhone 14 Pro Max": {"128GB": 22000000, "256GB": 23500000, "512GB": 25000000, "1TB": 26000000},
        "iPhone 14 Pro": {"128GB": 20000000, "256GB": 21500000, "512GB": 23000000, "1TB": 24000000},
        "iPhone 13 Pro Max": {"128GB": 17000000, "256GB": 18500000, "512GB": 20000000, "1TB": 21000000},
        "iPhone 12 Pro Max": {"128GB": 13000000, "256GB": 14000000, "512GB": 15000000},
    };

    const conditionMultiplier = {
        "good": 1,      // Loại 1: 100% giá
        "fair": 0.85,   // Loại 2: 85% giá
        "poor": 0.65    // Loại 3: 65% giá
    };

    let selections = {
        model: null,
        capacity: null,
        condition: null
    };

    const steps = {
        step1: document.getElementById('step1'),
        step2: document.getElementById('step2'),
        step3: document.getElementById('step3'),
        step4: document.getElementById('step4'),
    };

    // Xử lý chọn Model
    steps.step1.querySelectorAll('.model-item').forEach(item => {
        item.addEventListener('click', function() {
            selections.model = this.dataset.model;
            // Bỏ active tất cả, rồi active cái được click
            steps.step1.querySelectorAll('.model-item').forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            
            steps.step2.style.display = 'block';
            window.scrollTo({ top: steps.step2.offsetTop - 80, behavior: 'smooth' });
        });
    });

    // Xử lý chọn Dung lượng
    steps.step2.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selections.capacity = this.dataset.capacity;
            steps.step2.querySelectorAll('.option-btn').forEach(el => el.classList.remove('active'));
            this.classList.add('active');

            steps.step3.style.display = 'block';
            window.scrollTo({ top: steps.step3.offsetTop - 80, behavior: 'smooth' });
        });
    });

    // Xử lý chọn Tình trạng
    steps.step3.querySelectorAll('input[name="condition"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selections.condition = this.value;
            calculatePrice();
            steps.step4.style.display = 'block';
            window.scrollTo({ top: steps.step4.offsetTop - 80, behavior: 'smooth' });
        });
    });

    // Tính toán và hiển thị giá
    function calculatePrice() {
        if (!selections.model || !selections.capacity || !selections.condition) return;

        const basePrice = (priceData[selections.model] && priceData[selections.model][selections.capacity]) ? priceData[selections.model][selections.capacity] : 0;
        const finalPrice = basePrice * conditionMultiplier[selections.condition];

        document.getElementById('result-model-info').textContent = `${selections.model} ${selections.capacity}`;
        document.getElementById('result-price').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(finalPrice);
    }

    // Xử lý nút "Thử lại"
    document.getElementById('reset-btn').addEventListener('click', function() {
        selections = { model: null, capacity: null, condition: null };
        
        // Ẩn các bước sau và cuộn lên đầu
        steps.step2.style.display = 'none';
        steps.step3.style.display = 'none';
        steps.step4.style.display = 'none';

        // Bỏ chọn active
        document.querySelectorAll('.model-item.active, .option-btn.active').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('input[name="condition"]').forEach(radio => radio.checked = false);

        window.scrollTo({ top: steps.step1.offsetTop - 80, behavior: 'smooth' });
    });
});
</script>