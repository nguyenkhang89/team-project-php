# Công việc nhóm PHP và MySQL

Ứng dụng cho một project cố định. Quản lý thành viên và giao việc, sửa, xóa,
lọc trạng thái, xem việc quá hạn. PHP 8.3 và MySQL 8, không cần Composer.
Chưa có đăng nhập hoặc phân quyền. Chỉ dùng dữ liệu giả và giới hạn truy cập IP.

## Chạy nhanh trên Ubuntu 24.04 cục bộ

1. Cài php-cli php-mysql mysql-server git.
2. Dùng sudo mysql tạo database team_project với utf8mb4 và tài khoản
   team_app@127.0.0.1. Cấp SELECT, INSERT, UPDATE, DELETE trên team_project.*.
3. Tại thư mục dự án: sudo mysql team_project < database/schema.sql.
   Chỉ chạy một lần trên CSDL trống; không nhập lại khi cập nhật mã.
4. cp config/config.example.php config/config.local.php.
   Điền mật khẩu riêng vào config.local.php. Tệp này không được commit.
5. chmod 600 config/config.local.php.
6. php -S 127.0.0.1:8000 -t public.
7. Mở http://127.0.0.1:8000 trong trình duyệt cùng máy Ubuntu.

Chi tiết SQL, GitHub, EC2 và xử lý lỗi có trong Huong_dan.md và tài liệu Word đi kèm.
Tên người dùng CSDL, mật khẩu mẫu trong hướng dẫn phải được thay bằng thông tin riêng.

## Apache trên EC2

Cài apache2 php libapache2-mod-php php-mysql mysql-server git.
Clone vào /var/www/team-project. Tạo CSDL và cấu hình riêng trên EC2.
Sao chép deploy/team-project.conf vào /etc/apache2/sites-available/ rồi bật site.
Chỉ public được phục vụ qua web; config và .git phải ở ngoài DocumentRoot.
config.local.php thuộc ubuntu:www-data, quyền 640. Không mở MySQL 3306.
Security Group chỉ cho phép SSH 22 và HTTP 80 từ IP của người thực hiện.

## Quản lý mã và dữ liệu

GitHub lưu code và schema, không đồng bộ dữ liệu MySQL. Cần nhập lại dữ liệu giả
ở EC2 hoặc chuyển dữ liệu bằng quy trình sao lưu riêng. Push không tự triển khai.
Trên EC2 dùng git pull --ff-only origin main rồi kiểm tra cú pháp và reload Apache.
Không sửa trực tiếp tệp được Git theo dõi trên EC2.

## Giới hạn và kiểm tra

Biểu mẫu dùng CSRF token, SQL dùng tham số, HTML được escape. Không có đăng nhập,
phân quyền, lịch sử giao việc hoặc xác nhận trước nút xóa. Không dùng làm sản phẩm thật.
Các kiểm thử tự động của bộ tài liệu dùng PHP 8.3 và SQLite trên bản sao chỉ dành
cho kiểm tra luồng xử lý; không thay thế kiểm thử MySQL 8 và triển khai EC2 thực tế.
Khi dùng MySQL, chạy đủ bảng kiểm thử trong hướng dẫn. Không cần SQLite để triển khai.
