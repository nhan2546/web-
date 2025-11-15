# Bước 1: Sử dụng image chính thức của PHP 8.2 với máy chủ Apache
FROM php:8.2-apache

# Bước 2: Cập nhật và cài đặt các thư viện hệ thống cần thiết
# - libzip-dev: Cần cho extension 'zip'
# - libpng-dev, libjpeg-dev, libfreetype-dev: Cần cho extension 'gd' (xử lý ảnh)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    && rm -rf /var/lib/apt/lists/*

# Bước 3: Cài đặt các extension PHP phổ biến
# - pdo_mysql: Bắt buộc để kết nối MySQL (bạn đã có)
# - mbstring: Thường dùng để xử lý chuỗi UTF-8
# - zip: Thường dùng để xử lý các tệp nén (composer hay dùng)
# - gd: Thường dùng để xử lý hình ảnh
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Bước 4: Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Bước 5: Đặt thư mục làm việc
WORKDIR /var/www/html

# Bước 6: Sao chép tệp composer
# Chúng ta sao chép riêng để tận dụng cache của Docker
COPY composer.json composer.lock ./

# Bước 7: Chạy composer install
# Đây là bước có khả năng thất bại ở tệp Dockerfile cũ của bạn
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Bước 8: Sao chép toàn bộ mã nguồn còn lại của dự án
COPY . .

# Bước 9: Đặt quyền sở hữu cho máy chủ web
RUN chown -R www-data:www-data /var/www/html
