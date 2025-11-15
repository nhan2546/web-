# Bước 1: Sử dụng image chính thức của PHP 8.2 với máy chủ Apache
FROM php:8.2-apache

# Bước 2: Cập nhật và cài đặt các thư viện hệ thống cần thiết
# (Cần cho các extension bên dưới)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    && rm -rf /var/lib/apt/lists/*

# Bước 3: Cài đặt các extension PHP
# *** PHẦN SỬA LỖI BẮT ĐẦU TỪ ĐÂY ***

# Tách riêng cài đặt 'gd' (xử lý ảnh) vì nó cần 'configure'
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Cài đặt các extension còn lại
RUN docker-php-ext-install pdo pdo_mysql mbstring zip

# *** HẾT PHẦN SỬA LỖI ***

# Bước 4: Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Bước 5: Đặt thư mục làm việc
WORKDIR /var/www/html

# Bước 6: Sao chép tệp composer để tận dụng cache
COPY composer.json composer.lock ./

# Bước 7: Chạy composer install
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Bước 8: Sao chép toàn bộ mã nguồn còn lại của dự án
COPY . .

# Bước 9: Đặt quyền sở hữu cho máy chủ web
RUN chown -R www-data:www-data /var/www/html
