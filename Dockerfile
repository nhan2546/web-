# Sử dụng hình ảnh (image) PHP 8.1 chính thức với Apache
FROM php:8.1-apache

# --- BƯỚC SỬA LỖI ---
# 1. Cài đặt các thư viện hệ thống (dependencies) mà 'gd' và 'pdo_mysql' cần
# (libpng, libjpeg, libfreetype là cho 'gd')
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Cấu hình 'gd' để nó tìm thấy các thư viện trên
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# 3. Bây giờ mới chạy lệnh cài đặt extensions
RUN docker-php-ext-install pdo pdo_mysql gd

# 4. Kích hoạt module 'rewrite' của Apache (cho .htaccess)
RUN a2enmod rewrite
# --- KẾT THÚC SỬA LỖI ---

# Sao chép (Copy) toàn bộ code website của bạn
# từ thư mục hiện tại (trên GitHub) vào thư mục web của Apache
COPY . /var/www/html/

# (Tùy chọn) Đặt quyền sở hữu cho thư mục web
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Mở cổng 80 (cổng web tiêu chuẩn)
EXPOSE 80
