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

    <!-- KHUNG CHATBOT AI -->
    <div class="chatbot-container">
        <div class="chatbot-header">
            <h2>Trợ lý AI</h2>
        </div>
        <div class="chatbot-box">
            <div class="chat outgoing">
                <span class="chat-avatar">
                    <img src="TaiNguyen/hinh_anh/Chatbot Chat Message.jpg" alt="AI Avatar">
                </span>
                <p>Xin chào! Tôi có thể giúp gì cho bạn?</p>
            </div>
        </div>
        <div class="chat-input">
            <input type="text" class="form-control" placeholder="Nhập tin nhắn..." required>
            <button class="btn btn-primary" aria-label="Gửi">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </div>
    </div>

    <!-- Bootstrap JS (nếu bạn đang dùng) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JAVASCRIPT CHO CHATBOT -->
    <script>
        const chatbotToggler = document.querySelector(".chatbot-toggler");
        const chatbotContainer = document.querySelector(".chatbot-container");
        const chatInput = document.querySelector(".chat-input input");
        const sendChatBtn = document.querySelector(".chat-input button");
        const chatbox = document.querySelector(".chatbot-box");

        // Hàm để ẩn/hiện chatbot
        chatbotToggler.addEventListener("click", () => {
            chatbotContainer.classList.toggle("show");
            chatbotToggler.classList.toggle("show-close");
        });

        // Hàm tạo bong bóng chat
        const createChatLi = (message, className) => {
            const chatLi = document.createElement("div");
            chatLi.classList.add("chat", className);
            // Nếu là tin nhắn của bot (outgoing), thêm avatar
            let chatContent = (className === "outgoing")
                              ? `<span class="chat-avatar">
                                   <img src="TaiNguyen/hinh_anh/Chatbot Chat Message.jpg" alt="AI Avatar">
                                 </span><p>${message}</p>`
                              : `<p>${message}</p>`;
            chatLi.innerHTML = chatContent;
            return chatLi;
        }

        // Hàm tạo câu trả lời từ Bot
        const generateResponse = (userMessage) => {
            const lowerCaseMessage = userMessage.toLowerCase();
            let response = "Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể hỏi về 'sản phẩm', 'khuyến mãi' hoặc 'địa chỉ'.";

            if (lowerCaseMessage.includes("sản phẩm") || lowerCaseMessage.includes("iphone")) {
                response = "Shop Táo Ngon chuyên cung cấp các dòng iPhone chính hãng. Bạn có thể xem tất cả sản phẩm trên trang chủ nhé!";
            } else if (lowerCaseMessage.includes("khuyến mãi") || lowerCaseMessage.includes("giảm giá")) {
                response = "Hiện tại shop đang có chương trình giảm giá 10% cho tất cả các sản phẩm. Hãy nhanh tay đặt hàng!";
            } else if (lowerCaseMessage.includes("địa chỉ") || lowerCaseMessage.includes("cửa hàng")) {
                response = "Cửa hàng của chúng tôi ở địa chỉ: 123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh.";
            } else if (lowerCaseMessage.includes("chào") || lowerCaseMessage.includes("hello")) {
                response = "Chào bạn, Shop Táo Ngon có thể giúp gì cho bạn?";
            }

            const outgoingChatLi = createChatLi(response, "outgoing");
            chatbox.appendChild(outgoingChatLi);
            chatbox.scrollTo(0, chatbox.scrollHeight); // Tự động cuộn xuống
        }

        // Hàm xử lý khi người dùng gửi tin nhắn
        const handleChat = () => {
            const userMessage = chatInput.value.trim();
            if (!userMessage) return;

            // Xóa nội dung input và hiển thị tin nhắn của người dùng
            chatInput.value = "";
            const incomingChatLi = createChatLi(userMessage, "incoming");
            chatbox.appendChild(incomingChatLi);
            chatbox.scrollTo(0, chatbox.scrollHeight);

            // Bot trả lời sau một khoảng thời gian ngắn
            setTimeout(() => {
                generateResponse(userMessage);
            }, 600);
        }

        // Bắt sự kiện click nút gửi hoặc nhấn Enter
        sendChatBtn.addEventListener("click", handleChat);
        chatInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                handleChat();
            }
        });
    </script>

    <!-- FOOTER -->
    <footer class="footer-custom mt-auto">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Shop Táo Ngon. Mọi quyền được bảo lưu.</p>
        </div>
    </footer>
</body>
</html>