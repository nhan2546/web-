# Bước 1: Sử dụng image chính thức của PHP 8.2 với máy chủ Apache
FROM php:8.2-apache

# Bước 2: Cài đặt các extension PHP cần thiết
# pdo_mysql là bắt buộc để kết nối cơ sở dữ liệu của bạn
RUN docker-php-ext-install pdo pdo_mysql

# Bước 3: Cài đặt Composer (trình quản lý gói của PHP)
# Chúng ta lấy tệp thực thi Composer từ một image chính thức khác
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Bước 4: Đặt thư mục làm việc bên trong container
WORKDIR /var/www/html

# Bước 5: Sao chép tệp composer trước để tận dụng cache
# Điều này giúp build nhanh hơn nếu các gói không thay đổi
COPY composer.json composer.lock ./

# Bước 6: Chạy composer install để tải về thư mục 'vendor'
# --no-interaction: Không đặt câu hỏi
# --no-dev: Bỏ qua các gói chỉ dùng cho phát triển (tốt cho production)
# --optimize-autoloader: Tối ưu hóa trình tải tự động
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Bước 7: Sao chép toàn bộ mã nguồn còn lại của dự án vào
# Dấu "." đầu tiên là thư mục hiện tại (dự án của bạn)
# Dấu "." thứ hai là thư mục làm việc trong container (/var/www/html)
COPY . .

# Bước 8: (Tùy chọn) Đặt quyền sở hữu cho máy chủ web
# Điều này có thể cần thiết nếu ứng dụng cần ghi tệp (ví dụ: tải lên ảnh)
RUN chown -R www-data:www-data /var/www/html
