# --- Giai đoạn 1: Sử dụng image PHP chính thức với Apache ---
# Chọn phiên bản PHP 8.2 (hoặc phiên bản bạn đang dùng) với máy chủ web Apache
FROM php:8.2-apache

# --- Giai đoạn 2: Cài đặt các extension cần thiết ---
# Cập nhật danh sách package và cài đặt các extension PHP mà ứng dụng cần
# - pdo và pdo_mysql: Cần thiết để kết nối tới cơ sở dữ liệu MySQL bằng PDO.
# - gd: Thư viện xử lý hình ảnh (hữu ích nếu bạn có chức năng upload/resize ảnh).
# - docker-php-ext-install: Lệnh tiện ích để cài đặt extension.
# - docker-php-ext-enable: Lệnh tiện ích để kích hoạt extension.
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql

# --- Giai đoạn 3: Cấu hình Apache ---
# Bật module `mod_rewrite` của Apache. Điều này rất quan trọng để các URL thân thiện
# (ví dụ: /san-pham/iphone-15 thay vì index.php?act=chi_tiet&id=1) có thể hoạt động.
RUN a2enmod rewrite

# --- Giai đoạn 4: Sao chép mã nguồn ứng dụng ---
# Sao chép toàn bộ file từ thư mục hiện tại (nơi có Dockerfile) vào thư mục web root của Apache.
COPY . /var/www/html/

# --- Giai đoạn 5: Thiết lập quyền sở hữu ---
# Đảm bảo rằng máy chủ web Apache có quyền đọc và ghi vào các file của ứng dụng.
# Điều này đặc biệt quan trọng cho các thư mục upload.
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Cổng 80 là cổng mặc định mà Apache lắng nghe.
EXPOSE 80
