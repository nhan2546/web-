# Sử dụng hình ảnh (image) PHP 8.1 chính thức với Apache
FROM php:8.1-apache

# Cài đặt các phần mở rộng (extensions) PHP cần thiết
# pdo_mysql: Để kết nối với database Railway (MySQL)
# gd: Để xử lý hình ảnh (nếu website của bạn có upload ảnh)
RUN docker-php-ext-install pdo pdo_mysql gd && a2enmod rewrite

# Sao chép (Copy) toàn bộ code website của bạn
# từ thư mục hiện tại (trên GitHub) vào thư mục web của Apache
COPY . /var/www/html/

# (Tùy chọn) Đặt quyền sở hữu cho thư mục web
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Mở cổng 80 (cổng web tiêu chuẩn)
EXPOSE 80
