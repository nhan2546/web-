    </main> <!-- Đóng thẻ main từ file dau_trang.php -->
    <!-- CÁC NÚT LIÊN HỆ NỔI -->
    <div class="contact-float">
        <!-- Nút Chatbot AI -->
        <button class="contact-item ai chatbot-toggler">
            <img class="icon-open" src="TaiNguyen/hinh_anh/Chatbot Chat Message.jpg" alt="AI Assistant">
            <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <!-- Nút Zalo -->
        <a href="https://zalo.me/0837277347" target="_blank" class="contact-item zalo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo">
        </a>
        <!-- Nút Gọi điện --> 
        <a href="tel: 0837277347" class="contact-item phone">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.28 1.465l-2.135 2.136a11.942 11.942 0 0 0 5.586 5.586l2.136-2.135a1.745 1.745 0 0 1 1.465-.28l2.305 1.152a1.745 1.745 0 0 1 .163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03.003-2.137.703-2.877L1.885.511z"/></svg>
        </a>
    </div>
<button class="chatbot-toggler">
    <span class="icon-open">🤖</span>
    <span class="icon-close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </span>
</button>

<div class="chatbot-container">
    <div class="chatbot-header">
        <h2>Trợ lý AI</h2>
    </div>
    <div id="chatbot-box" class="chatbot-box">
        </div>
    <div class="chat-input">
        <input id="chat-input-field" type="text" class="form-control" placeholder="Nhập tin nhắn..." required>
        <button id="send-btn" class="btn btn-primary" aria-label="Gửi">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </div>
</div>

<script>
    const chatbotToggler = document.querySelector(".chatbot-toggler");
    const chatbotContainer = document.querySelector(".chatbot-container");
    const chatbotBox = document.getElementById('chatbot-box');
    const inputField = document.getElementById('chat-input-field');
    const sendBtn = document.getElementById('send-btn');
    
    const RASA_SERVER_URL = 'http://localhost:5005/webhooks/rest/webhook';
    const BOT_AVATAR_URL = 'TaiNguyen/hinh_anh/Chatbot Chat Message.jpg';

    // --- Logic bật/tắt chatbot ---
    chatbotToggler.addEventListener("click", () => {
        chatbotContainer.classList.toggle("show");
        chatbotToggler.classList.toggle("show-close");
    });

    // --- Logic hiển thị tin nhắn (ĐÃ SỬA LỖI) ---
    const displayMessage = (message, type) => {
        const chatDiv = document.createElement('div');
        chatDiv.classList.add('chat', type);

        // Theo CSS của bạn, 'outgoing' là của bot (có avatar)
        if (type === 'outgoing') { 
            chatDiv.innerHTML = `
                <span class="chat-avatar">
                    <img src="${BOT_AVATAR_URL}" alt="AI Avatar">
                </span>
                <p>${message}</p>
            `;
        } else { // 'incoming' là của người dùng (không có avatar)
            chatDiv.innerHTML = `<p>${message}</p>`;
        }
        
        chatbotBox.appendChild(chatDiv);
        chatbotBox.scrollTop = chatbotBox.scrollHeight;
    }

    // --- Logic gửi và nhận tin nhắn (ĐÃ SỬA LỖI) ---
    const handleSendMessage = async () => {
        const userMessage = inputField.value.trim();
        if (!userMessage) return;

        // Hiển thị tin nhắn của người dùng với class 'incoming'
        displayMessage(userMessage, 'incoming');
        inputField.value = '';

        // Hiển thị tin nhắn chờ của bot
        displayMessage("...", 'outgoing');

        try {
            const response = await fetch(RASA_SERVER_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sender: 'user', message: userMessage })
            });

            const botResponses = await response.json();
            
            // Xóa tin nhắn chờ "..."
            chatbotBox.removeChild(chatbotBox.lastChild);

            // Hiển thị (các) câu trả lời của bot với class 'outgoing'
                botResponses.forEach(botMessage => {
            displayMessage(botMessage.text, 'outgoing');
        });

        } catch (error) {
            chatbotBox.removeChild(chatbotBox.lastChild); // Xóa tin nhắn chờ "..."
            console.error("Lỗi khi kết nối đến Rasa server:", error);
            displayMessage("Xin lỗi, tôi đang gặp sự cố kết nối. Vui lòng thử lại sau.", 'outgoing');
        }
    }

    sendBtn.addEventListener('click', handleSendMessage);
    inputField.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleSendMessage();
        }
    });
    
    // Tin nhắn chào mừng ban đầu
    displayMessage("Xin chào! Tôi có thể giúp gì cho bạn?", 'outgoing');
</script>
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

    <!-- FOOTER -->
    <footer class="footer-custom mt-auto">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Shop Táo Ngon.</p>
        </div>
    </footer>
</body>
</html>