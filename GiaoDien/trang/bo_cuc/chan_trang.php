<script>
        const chatbotToggler = document.querySelector(".chatbot-toggler");
        const chatbotContainer = document.querySelector(".chatbot-container");
        const chatbotBox = document.getElementById('chatbot-box');
        const inputField = document.getElementById('chat-input-field');
        const sendBtn = document.getElementById('send-btn');
        
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
const handleSendMessage = async () => {
    // Class cho tin nhắn người dùng
    const USER_CLASS = 'incoming'; 
    
    // Class cho tin nhắn bot (PHẢI KHỚP với 'botoutgoing' ở hàm displayMessage)
    const BOT_CLASS = 'botoutgoing'; // <-- SỬA LỖI Ở ĐÂY (trước đây là 'outgoing')

    const userMessage = inputField.value.trim();
    if (!userMessage) return;

    displayMessage(userMessage, USER_CLASS);
    inputField.value = '';
    inputField.style.height = 'auto';

    displayMessage("...", BOT_CLASS); // Hiển thị tin nhắn chờ

    try {
        // 3. Gọi đến tệp api.php
        const response = await fetch('api.php', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: userMessage }) 
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
            if (e.key === 'Enter' && !e.shiftKey) { 
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
            // PHẢI KHỚP với 'botoutgoing' ở hàm displayMessage
            displayMessage("Xin chào! Tôi có thể giúp gì cho bạn?", 'botoutgoing'); // <-- SỬA LỖI Ở ĐÂY (trước đây là 'bot')
        }, 500);
    </script>
