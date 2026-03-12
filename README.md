# TOYSTORE - NỀN TẢNG THƯƠNG MẠI ĐIỆN TỬ DÀNH CHO TRẺ EM

## 📖 Mục Lục

1. [Tổng Quan Dự Án](#-tổng-quan-dự-án)
2. [Cấu Trúc Thư Mục](#-cấu-trúc-thư-mục-project-structure)
3. [Kiến Trúc Hệ Thống](#-kiến-trúc-hệ-thống)
4. [Chức Năng Chi Tiết](#-chức-năng-chi-tiết)
5. [Yêu Cầu Cài Đặt](#-yêu-cầu-cài-đặt)
6. [Hướng Dẫn Triển Khai](#-hướng-dẫn-triển-khai)
7. [Đội Ngũ Phát Triển](#-đội-ngũ-phát-triển)

---

## 🚀 Tổng Quan Dự Án

**ToyStore** là giải pháp website bán đồ chơi trực tuyến trọn gói (Full-stack), được xây dựng từ đầu (From Scratch) sử dụng PHP thuần và MySQL. Dự án tập trung vào việc tối ưu hóa trải nghiệm người dùng và cung cấp công cụ quản trị mạnh mẽ, thể hiện tư duy lập trình hệ thống bài bản.

---

## 📂 Cấu Trúc Thư Mục (Project Structure)

Cấu trúc mã nguồn được tổ chức theo mô hình module hóa, tách biệt rõ ràng giữa quản trị (Admin), giao diện (Assets) và logic xử lý (Modules):

---

ToyStore/
├── admin/                       # Các trang quản trị dành cho chủ cửa hàng
│   ├── index.php                # Bảng điều khiển (Dashboard) admin
│   ├── order_detail.php         # Xem và xử lý chi tiết đơn hàng
│   ├── product_add.php          # Thêm sản phẩm đồ chơi mới
│   ├── product_delete.php       # Xóa sản phẩm khỏi hệ thống
│   ├── product_edit.php         # Chỉnh sửa thông tin sản phẩm
│   └── sidebar.php              # Thanh điều hướng bên của trang admin
├── assets/                      # TÀI NGUYÊN TĨNH
│   ├── css/                     # Các file định dạng giao diện 
│   └── images/                  # Kho ảnh sản phẩm và banner
├── config/                      # Cấu hình hệ thống
│   └── db.php                   # Thiết lập kết nối cơ sở dữ liệu MySQL
├── includes/                    # Các thành phần giao diện dùng chung (Components)
│   ├── footer.php               # Chân trang web
│   └── header.php               # Thanh menu và đầu trang web
├── models/                      # Chứa các lớp xử lý và truy vấn dữ liệu tập trung
│   ├── OrderModel.php           # Xử lý các nghiệp vụ liên quan đến đơn hàng
│   ├── ProductModel.php         # Xử lý các nghiệp vụ liên quan đến sản phẩm
│   └── UserModel.php            # Xử lý các nghiệp vụ liên quan đến tài khoản người dùng
├── modules/                     # Chứa nội dung và logic của các trang chức năng người dùng
│   ├── cart.php                 # Giao diện và xử lý giỏ hàng
│   ├── category.php             # Hiển thị sản phẩm theo danh mục
│   ├── checkout.php             # Trang thanh toán và hoàn tất đặt hàng
│   ├── detail.php               # Xem thông tin chi tiết một món đồ chơi
│   ├── home.php                 # Giao diện trang chủ hiển thị sản phẩm mới
│   ├── orders.php               # Xem lịch sử các đơn hàng đã đặt
│   ├── profile.php              # Quản lý thông tin cá nhân khách hàng
│   └── search.php               # Kết quả tìm kiếm sản phẩm
├── index.php                    # Tệp điều hướng chính của hệ thống
├── login.php                    # Trang đăng nhập thành viên
├── logout.php                   # Xử lý đăng xuất khỏi hệ thống
├── register.php                 # Trang đăng ký tài khoản mới
└── README.md                    # Tài liệu hướng dẫn dự án
---

## 🏗 Kiến Trúc Hệ Thống

Hệ thống hoạt động dựa trên cơ chế Routing cơ bản tại index.php. Mọi yêu cầu từ người dùng sẽ đi qua Router để gọi các module tương ứng từ thư mục modules/.

Sơ đồ luồng dữ liệu (Simplified Data Flow):
Request: Người dùng truy cập index.php?page=cart

Routing: File index.php kiểm tra tham số page.

Controller: Gọi file modules/cart.php.

View: Hiển thị giao diện Giỏ hàng kèm dữ liệu từ Session/Database.

---

## ✨ Chức Năng Chi Tiết

1. Phân hệ Khách hàng (Front-end)
   Authentication: Đăng ký, Đăng nhập, Đăng xuất (login.php, register.php).

Mua sắm: Xem sản phẩm, Lọc theo danh mục, Tìm kiếm thông minh.

Đặt hàng: Quy trình thêm vào giỏ hàng và thanh toán nhanh gọn.

Cá nhân: Quản lý thông tin tài khoản (profile.php).

2. Phân hệ Quản trị (Back-end)
   Quản lý Sản phẩm (CRUD): Thêm, Sửa, Xóa, Cập nhật hình ảnh.

Quản lý Đơn hàng: Theo dõi trạng thái đơn hàng, xem chi tiết người mua.

Thống kê: Báo cáo doanh thu và tình trạng kho hàng thực tế.

---

## ⚙️ Yêu Cầu Cài Đặt

Web Server: XAMPP / WAMP (Apache).

PHP Version: 7.4 trở lên.

Database: MySQL.

Trình duyệt: Chrome/Edge bản mới nhất.
