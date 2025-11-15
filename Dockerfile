# Sử dụng một image Docker chính thức có chứa PHP và máy chủ Apache
FROM php:8.2-apache

# Máy chủ Apache mặc định phục vụ các tệp từ /var/www/html
# Sao chép tệp api.php của bạn vào thư mục đó
COPY api.php /var/www/html/api.php

# (Tùy chọn) Cài đặt PDO driver cho MySQL nếu chưa có
RUN docker-php-ext-install pdo pdo_mysql
