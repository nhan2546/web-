        </div> <!-- Đóng thẻ .main-content-wrapper -->
    </main>

<footer class="footer-custom text-white text-center p-3 mt-auto">
    <div class="main-content-wrapper">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> táo ngon.</p>
    </div>
</footer>

<script src="TaiNguyen/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- CÁC HÀM HỖ TRỢ VALIDATION ---
    const validateEmail = (email) => {
        return String(email)
            .toLowerCase()
            .match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    };

    const validatePhone = (phone) => {
        return /^(0[3|5|7|8|9])+([0-9]{8})\b/.test(phone);
    };

    const setFieldValidity = (input, isValid, message) => {
        const feedback = input.nextElementSibling;
        if (isValid) {
            input.classList.remove('is-invalid');
        } else {
            input.classList.add('is-invalid');
            if (feedback && message) {
                feedback.textContent = message;
            }
        }
    };

    const validateField = (field) => {
        let isValid = true;
        let message = '';

        // Reset state
        field.classList.remove('is-invalid', 'is-valid');

        switch (field.id) {
            case 'fullname':
                isValid = field.value.trim() !== '';
                message = 'Vui lòng nhập họ và tên.';
                break;
            case 'email':
            case 'forgot-email':
                isValid = validateEmail(field.value);
                message = 'Vui lòng nhập một địa chỉ email hợp lệ.';
                break;
            case 'password':
                isValid = field.value.length >= 6;
                message = 'Mật khẩu phải có ít nhất 6 ký tự.';
                break;
            case 'confirm_password':
                const passwordField = field.form.querySelector('#password');
                isValid = field.value === passwordField.value && field.value !== '';
                message = 'Mật khẩu xác nhận không khớp.';
                break;
            case 'FullName':
                isValid = field.value.trim() !== '';
                message = 'Vui lòng nhập họ tên của bạn.';
                break;
            case 'Address':
                isValid = field.value.trim() !== '';
                message = 'Vui lòng nhập địa chỉ nhận hàng.';
                break;
            case 'Phone':
                isValid = validatePhone(field.value);
                message = 'Vui lòng nhập số điện thoại hợp lệ (10 số).';
                break;
            default:
                // For simple required fields not listed above
                if (field.hasAttribute('required')) {
                    isValid = field.value.trim() !== '';
                    message = 'Trường này là bắt buộc.';
                }
                break;
        }

        setFieldValidity(field, isValid, message);
        return isValid;
    };

    // --- GẮN SỰ KIỆN VALIDATE CHO CÁC FORM ---
    const formsToValidate = document.querySelectorAll('#login-form, #register-form, #payment-form, #forgot-password-form');

    formsToValidate.forEach(form => {
        const fields = form.querySelectorAll('input[required]');

        // Validate khi người dùng rời khỏi một ô input (blur)
        fields.forEach(field => {
            field.addEventListener('blur', function () {
                validateField(this);
            });
        });

        // Validate khi người dùng đang gõ (input) nếu ô đó đã báo lỗi
        fields.forEach(field => {
            field.addEventListener('input', function () {
                // Chỉ validate lại khi đang gõ nếu nó đã bị báo lỗi trước đó
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });

        // Validate toàn bộ form trước khi gửi đi (submit)
        form.addEventListener('submit', function (event) {
            let isFormValid = true;
            fields.forEach(field => {
                if (!validateField(field)) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                event.preventDefault();
            }
        });
    });
});
</script>

<!-- Các nút liên hệ nổi -->
<div class="contact-float">
    <!-- Nút Zalo -->
    <a href="https://zalo.me/0123456789" target="_blank" class="contact-item zalo" title="Chat Zalo">
        <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/01/Logo-Zalo-Arc.png" alt="Zalo">
    </a>
    <!-- Nút Gọi điện -->
    <a href="tel:0123456789" class="contact-item phone" title="Gọi tư vấn">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.28 1.465l-2.135 2.136a11.942 11.942 0 0 0 5.583 5.583l2.136-2.135a1.465 1.465 0 0 1 1.465.28l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg>
    </a>
</div>

<!-- Tích hợp Rasa Webchat -->
<style>
    /* Tùy chỉnh màu sắc của Rasa Webchat để phù hợp với trang web */
    :root {
        --rasa-green: #e74c3c; /* Màu header và nút gửi */
        --rasa-blue: #e74c3c;  /* Màu nền tin nhắn của người dùng */
    }

    .rw-widget-container .rw-launcher {
        background-color: var(--rasa-green) !important;
    }

    .rw-widget-container .rw-header {
        background-color: var(--rasa-green) !important;
    }

    .rw-widget-container .rw-client {
        background-color: var(--rasa-blue) !important;
    }
</style>

<div id="rasa-webchat-container"></div>
<script src="https://unpkg.com/@rasahq/rasa-webchat@latest/build/v1/rasa-webchat.js" type="application/javascript"></script>
<script>
  window.rasaWebchat.init({
    selector: "#rasa-webchat-container",
    socketUrl: "http://localhost:5005", // QUAN TRỌNG: Thay đổi URL này thành địa chỉ máy chủ Rasa của bạn
    title: "Trợ lý ảo Táo Ngon",
    subtitle: "Sẵn sàng hỗ trợ bạn 24/7"
  })
</script>

</body>
</html>