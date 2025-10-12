        </div> <!-- Đóng thẻ .container từ dau_trang.php -->
    </main> <!-- Đóng thẻ main từ dau_trang.php -->
    <!-- CÁC NÚT LIÊN HỆ NỔI -->
    <div class="contact-float">
        <!-- Các nút sẽ được định vị riêng bằng CSS -->
    </div>
    <a href="https://zalo.me/0837277347" target="_blank" class="contact-item zalo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo">
    </a>
    <a href="tel: 0837277347" class="contact-item phone">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.28 1.465l-2.135 2.136a11.942 11.942 0 0 0 5.586 5.586l2.136-2.135a1.745 1.745 0 0 1 1.465-.28l2.305 1.152a1.745 1.745 0 0 1 .163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03.003-2.137.703-2.877L1.885.511z"/></svg>
    </a>

<!-- TÍCH HỢP RASA WEBCHAT -->
<div id="rasa-webchat-container"></div>
<script src="https://unpkg.com/rasa-webchat@^1/lib/index.js" async></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    
    window.WebChat.default(
      {
        initPayload: "/greet",
        customData: { language: "vi" }, // Đặt ngôn ngữ là Tiếng Việt
        socketUrl: "http://localhost:5005",
        title: "Trợ lý AI",
        subtitle: "Sẵn sàng giúp đỡ bạn",
        inputTextFieldHint: "Nhập tin nhắn...",
        connectingText: "Đang kết nối...",
        profileAvatar: "TaiNguyen/hinh_anh/—Pngtree—dial phone flat rectangle blue_4478675.png",
        params: {
          storage: "session" // "local" or "session"
        }
      },
      null
    );
  });
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

    <!-- JAVASCRIPT CHO NÚT ẨN/HIỆN MẬT KHẨU -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hàm trợ giúp để xử lý việc chuyển đổi cho một cặp nút và trường nhập
            const setupPasswordToggle = (toggleButtonId, passwordFieldId) => {
                const toggleButton = document.getElementById(toggleButtonId);
                const passwordField = document.getElementById(passwordFieldId);

                // Chỉ chạy nếu cả hai phần tử tồn tại trên trang
                if (toggleButton && passwordField) {
                    // SVG cho icon con mắt
                    const eyeIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>`;
                    const eyeSlashIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/></svg>`;

                    toggleButton.addEventListener('click', function () {
                        // Lấy loại hiện tại của trường nhập
                        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordField.setAttribute('type', type);

                        // Thay đổi icon trên nút
                        if (type === 'password') {
                            this.innerHTML = eyeIcon;
                        } else {
                            this.innerHTML = eyeSlashIcon;
                        }
                    });
                }
            };

            // Thiết lập cho trường mật khẩu chính
            setupPasswordToggle('togglePassword', 'password');

            // Thiết lập cho trường xác nhận mật khẩu
            setupPasswordToggle('toggleConfirmPassword', 'confirm_password');
        });
    </script>

    <!-- FOOTER -->
    <footer class="footer-custom mt-auto">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Shop Táo Ngon.</p>
        </div>
    </footer>
</body>
</html>