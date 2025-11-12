<!-- GIAO DIỆN AI CHATBOT TÙY CHỈNH -->
<div class="chatbot-container">
        <div class="chatbot-header">
            <h2>Trợ lý AI</h2> 
        </div>
        <div class="chatbot-box" id="chatbot-box">
            <!-- Tin nhắn sẽ được thêm vào đây bằng JavaScript -->
        </div>
        <div class="chat-input">
            <textarea id="chat-input-field" placeholder="Nhập tin nhắn..." required></textarea>
            <button id="send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </div> 
    </div>  

    <!-- CÁC NÚT LIÊN HỆ NỔI -->
    <div class="cp-fab-container">
        <button class="cp-fab chatbot-toggler">
             <img src="TaiNguyen/hinh_anh/Chatbot Chat Message.jpg" alt="AI Chat" class="icon-open">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <div class="cp-fab-options">
            <a href="https://zalo.me/0837277347" target="_blank" class="cp-fab-item" title="Chat Zalo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo">
            </a>
            <a href="tel:0837277347" class="cp-fab-item" title="Gọi điện">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.28 1.465l-2.135 2.136a11.942 11.942 0 0 0 5.586 5.586l2.136-2.135a1.745 1.745 0 0 1 1.465-.28l2.305 1.152a1.745 1.745 0 0 1 .163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03.003-2.137.703-2.877L1.885.511z"/></svg>
            </a>
        </div>
    </div>
</main> <!-- Đóng thẻ main được mở trong dau_trang.php -->
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
    <script>
        const chatbotToggler = document.querySelector(".chatbot-toggler");
        const chatbotContainer = document.querySelector(".chatbot-container");
        const chatbotBox = document.getElementById('chatbot-box');
        const inputField = document.getElementById('chat-input-field');
        const sendBtn = document.getElementById('send-btn');
        
        // ĐÂY LÀ ĐIỂM KẾT NỐI QUAN TRỌNG NHẤT
        const RASA_SERVER_URL = 'http://localhost:5005/webhooks/rest/webhook';
        const BOT_AVATAR_URL = 'TaiNguyen/hinh_anh/Chatbot Chat Message.jpg'; // Đường dẫn tới ảnh avatar của bot

        // ----- Logic bật/tắt chatbot -----
        chatbotToggler.addEventListener("click", () => {
            chatbotContainer.classList.toggle("show");
            chatbotToggler.classList.toggle("show-close");
        });

        // ----- Logic hiển thị tin nhắn -----
        const displayMessage = (message, className) => {
            const chatDiv = document.createElement('div');
            // Sử dụng class `chat-message` và `user`/`bot` để khớp với CSS
            chatDiv.classList.add('chat-message', className);

            let chatContent = '';
            if (className === 'botoutgoing') { // Tin nhắn của Bot (có avatar)
                chatContent = `
                    <div class="bot-avatar">
                        <img src="${BOT_AVATAR_URL}" alt="AI Avatar">
                    </div>
                    <div class="message-content"><p>${message}</p></div>
                `;
            } else { // Tin nhắn của Người dùng (không có avatar)
                chatContent = `<div class="message-content"><p>${message}</p></div>`;
            }
            chatDiv.innerHTML = chatContent;
            chatbotBox.appendChild(chatDiv);
            chatbotBox.scrollTop = chatbotBox.scrollHeight;
        }

        // ----- Logic gửi và nhận tin nhắn -----
       // Tệp: GiaoDien/trang/bo_cuc/chan_trang.php

// ----- Logic gửi và nhận tin nhắn -----
const handleSendMessage = async () => {
    // (Kiểm tra CSS của bạn dùng class 'user' hay 'incoming' cho người dùng)
    const USER_CLASS = 'incoming'; 
    // (Kiểm tra CSS của bạn dùng class 'bot' hay 'outgoing' cho bot)
    const BOT_CLASS = 'outgoing';

    const userMessage = inputField.value.trim();
    if (!userMessage) return;

    displayMessage(userMessage, USER_CLASS);
    inputField.value = '';
    inputField.style.height = 'auto';

    displayMessage("...", BOT_CLASS); // Hiển thị tin nhắn chờ

    try {
        // 3. Gọi đến tệp api.php (thay vì Rasa Server)
        const response = await fetch('api.php', { // <-- THAY ĐỔI QUAN TRỌNG
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: userMessage }) // Gửi dữ liệu theo định dạng api.php cần
        });

        // Xóa tin nhắn chờ "..."
        chatbotBox.removeChild(chatbotBox.lastChild);

        if (!response.ok) {
            displayMessage("Xin lỗi, tôi đang gặp sự cố kết nối (máy chủ PHP).", BOT_CLASS);
            return;
        }

        const botResponseData = await response.json();

        // 4. Hiển thị phản hồi từ api.php
        if (botResponseData && botResponseData.response) {
            // Chuyển đổi ký tự xuống dòng \n từ JSON thành thẻ <br>
            const formattedResponse = botResponseData.response.replace(/\n/g, '<br>');
            displayMessage(formattedResponse, BOT_CLASS);
        } else {
            displayMessage("Tôi chưa hiểu ý bạn.", BOT_CLASS);
        }

    } catch (error) {
        chatbotBox.removeChild(chatbotBox.lastChild); 
        console.error("Lỗi khi kết nối đến api.php:", error);
        displayMessage("Xin lỗi, tôi đang gặp sự cố kết nối. Vui lòng thử lại sau.", BOT_CLASS);
    }
}

        sendBtn.addEventListener('click', handleSendMessage);
        inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) { // Gửi khi nhấn Enter, xuống dòng bằng Shift + Enter
                e.preventDefault();
                handleSendMessage();
            }
        });

        // Tự động điều chỉnh chiều cao ô nhập liệu
        inputField.addEventListener('input', () => {
            inputField.style.height = 'auto';
            inputField.style.height = (inputField.scrollHeight) + 'px';
        });
        
        // Tin nhắn chào mừng ban đầu
        setTimeout(() => {
            displayMessage("Xin chào! Tôi có thể giúp gì cho bạn?", 'bot');
        }, 500);
    </script>

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
</body>
</html>
