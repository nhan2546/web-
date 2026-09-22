# Táo Store - Website Bán Lẻ Sản Phẩm & Phụ Kiện Apple

## 1. Giới thiệu
Hệ thống website thương mại điện tử mô phỏng cửa hàng bán lẻ các thiết bị công nghệ (iPhone, iPad, MacBook, phụ kiện). Cung cấp trải nghiệm mua sắm trực tuyến trực quan cho khách hàng và trang quản trị quản lý sản phẩm cho cửa hàng.

## 2. Công nghệ sử dụng (Tech Stack)
- **Front-end:** HTML5, CSS3, JavaScript (Bootstrap / Responsive Design)
- **Back-end & Xử lý:** PHP / ASP.NET / Node.js (tùy theo stack bạn viết)
- **Cơ sở dữ liệu:** MySQL / SQL Server
- **Triển khai (Deployment):** Đã từng cấu hình/triển khai thử nghiệm trên môi trường Web Server / Vercel

## 3. Các chức năng chính

### Dành cho Khách hàng (Client):
- **Trang chủ & Danh mục:** Hiển thị banner khuyến mãi, danh sách sản phẩm nổi bật, phân loại theo dòng máy (iPhone, phụ kiện...).
- **Chi tiết sản phẩm:** Xem thông số kỹ thuật, hình ảnh, giá bán và tình trạng kho.
- **Giỏ hàng & Đặt hàng:** Thêm/sửa số lượng sản phẩm trong giỏ hàng, tính tổng tiền và gửi thông tin đặt hàng.
- **Tìm kiếm & Bộ lọc:** Tìm kiếm sản phẩm nhanh theo tên và khoảng giá.

### Dành cho Quản trị viên (Admin):
- **Quản lý sản phẩm (CRUD):** Thêm mới sản phẩm, cập nhật giá, hình ảnh và xóa sản phẩm hết hàng.
- **Quản lý đơn hàng:** Theo dõi trạng thái đơn hàng của khách hàng gửi về.

## 4. Kiến trúc & Thiết kế
- Thiết kế giao diện thân thiện, tương thích tốt trên cả máy tính và thiết bị di động (Responsive UI).
- Cấu trúc cơ sở dữ liệu quan hệ chặt chẽ giữa các bảng: Danh mục (Categories), Sản phẩm (Products), Đơn hàng (Orders) và Chi tiết đơn hàng (Order_Details).
