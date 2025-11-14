<!-- GIAO DIỆN AI CHATBOT TÙY CHỈNH -->
<?php
// File: api/chat.php

// --- BẮT ĐẦU: THÊM MÃ NÀY ĐỂ SỬA LỖI CORS ---
// Thay thế 'https://gemini-chat-vtqc.onrender.com' bằng URL Render của bạn
header("Access-Control-Allow-Origin: https://gemini-chat-vtqc.onrender.com");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Xử lý yêu cầu OPTIONS (trình duyệt gửi trước khi gửi POST)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
// --- KẾT THÚC: MÃ SỬA LỖI CORS ---

// **QUAN TRỌNG: Dán API Key của bạn vào đây**
$apiKey = 'AIzaSyAgRxllarqyRthaqXMRU9aoFdASTWDz1ns';     
?>
<script>
        // --- JAVASCRIPT CHO TÌM KIẾM TRỰC TIẾP ---
        const searchInput = document.getElementById('search-input');
        const resultsContainer = document.getElementById('search-results-container');

        if (searchInput && resultsContainer) {
            searchInput.addEventListener('keyup', function() {
                const keyword = this.value.trim();

                if (keyword.length > 1) {
                    fetch(`index.php?act=ajax_tim_kiem&keyword=${keyword}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsContainer.innerHTML = ''; // Xóa kết quả cũ
                            if (data.length > 0) {
                                const ul = document.createElement('ul');
                                ul.className = 'search-results-list';
                                data.forEach(product => {
                                    const li = document.createElement('li');
                                    li.className = 'search-result-item';
                                    li.innerHTML = `
                                        <a href="index.php?act=chi_tiet_san_pham&id=${product.id}">
                                            <img src="TaiLen/san_pham/${product.image_url}" alt="${product.name}">
                                            <span>${product.name}</span>
                                        </a>
                                    `;
                                    ul.appendChild(li);
                                });
                                resultsContainer.appendChild(ul);
                                resultsContainer.style.display = 'block';
                            } else {
                                resultsContainer.style.display = 'none';
                            }
                        });
                } else {
                    resultsContainer.innerHTML = '';
                    resultsContainer.style.display = 'none';
                }
            });
            // Ẩn kết quả khi click ra ngoài
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target)) {
                    resultsContainer.style.display = 'none';
                }
            });
        }
    </script>

    <!-- JAVASCRIPT CHO AI CHATBOT -->
 <!-- TÍCH HỢP AI CHATBOT MỚI (ỨNG DỤNG REACT) -->
<iframe 
    src="https://gemini-chat-n0e7.onrender.com" 
    style="position: fixed; bottom: 20px; right: 20px; width: 400px; height: 600px; border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); z-index: 9999;"
    title="AI Sales Assistant"
    allow="microphone">
</iframe>
    <!-- JAVASCRIPT CHO SLIDER SẢN PHẨM -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Footer -->
<footer class="cp-footer">
  <div class="cp-container cp-footer__grid">
    <div>
      <img class="cp-logo--footer" src="TaiNguyen/hinh_anh/ChatGPT_Image_Oct_15__2025__05_00_01_PM-removebg-preview.png" alt="">
      <p>Shop Táo Ngon – Chuyên Apple chính hãng.</p>
    </div>
    <div>
      <h5>Hỗ trợ</h5>
      <a href="#">Chính sách bảo hành</a>
      <a href="#">Giao hàng & thanh toán</a>
      <a href="#">Đổi trả</a>
    </div>
    <div>
      <h5>Liên hệ</h5>
      <a href="tel:18000000">Hotline: 1800 0000</a>
      <a href="mailto:hello@shoptaongon.vn">hello@shoptaongon.vn</a>
    </div>
  </div>
  <div class="cp-footer__copy">© 2025 Shop Táo Ngon</div>
</footer>

<script src="TaiNguyen/js/main.js"></script>
<!-- AI Chatbot (React App) -->
<style>
  .ai-chatbot-iframe {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 400px;
    height: 600px;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    transition: transform 0.3s ease-in-out;
  }
</style>
</body>
</html>
