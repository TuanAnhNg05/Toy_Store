<?php
session_start();
require_once '../config/db.php';

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. XỬ LÝ KHI BẤM LƯU (POST)
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $cat_id = intval($_POST['category_id']);
    $desc = $_POST['description'];

    $image = 'no-image.png';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . rand(100, 999) . '.' . $file_extension;
        $target_file = $target_dir . $file_name;

        $allow_types = ['jpg', 'png', 'jpeg', 'webp'];
        if (in_array(strtolower($file_extension), $allow_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $file_name;
            } else {
                $error = "Lỗi không thể lưu file ảnh!";
            }
        } else {
            $error = "Chỉ chấp nhận file ảnh (JPG, PNG, WEBP).";
        }
    }

    if (empty($error)) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); // Kết nối thủ công để dùng escape_string cho nhanh
        $name = $conn->real_escape_string($name);
        $desc = $conn->real_escape_string($desc);

        $sql = "INSERT INTO products (name, price, image, description, category_id, stock) 
                VALUES ('$name', $price, '$image', '$desc', $cat_id, $stock)";

        if ($conn->query($sql)) {
            $success = "Thêm sản phẩm thành công!";
            $name = $price = $stock = $desc = '';
        } else {
            $error = "Lỗi Database: " . $conn->error;
        }
    }
}
?>

<?php require_once 'sidebar.php'; ?>

<div class="content">
    <div class="section-header">
        <div class="section-title">Thêm Sản Phẩm Mới</div>
        <a href="index.php?page=products" class="btn-action" style="background: #ddd; color: #333;">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if ($error): ?>
        <div style="padding: 15px; background: #FFEBEE; color: #D32F2F; border-radius: 10px; margin-bottom: 20px;">
            <i class="fa fa-exclamation-circle"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="padding: 15px; background: #E8F5E9; color: #2E7D32; border-radius: 10px; margin-bottom: 20px;">
            <i class="fa fa-check-circle"></i> <?= $success ?>
        </div>
    <?php endif; ?>

    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <form action="" method="POST" enctype="multipart/form-data">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Tên sản phẩm:</label>
                    <input type="text" name="name" required class="input-field" placeholder="Ví dụ: Lego City..."
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>

                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Danh mục:</label>
                    <select name="category_id" class="input-field"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="1">Lego Lắp Ráp</option>
                        <option value="2">Xe Mô Hình</option>
                        <option value="3">Búp Bê</option>
                        <option value="4">Gấu Bông</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Giá bán (VNĐ):</label>
                    <input type="number" name="price" required min="0" placeholder="100000"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Số lượng kho:</label>
                    <input type="number" name="stock" required min="1" value="10"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
            </div>

            <div style="margin-top: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">Ảnh sản phẩm:</label>
                <input type="file" name="image" required style="padding: 10px;">
            </div>

            <div style="margin-top: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">Mô tả chi tiết:</label>
                <textarea name="description" rows="5"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                    placeholder="Nhập thông tin chi tiết sản phẩm..."></textarea>
            </div>

            <div style="margin-top: 30px; text-align: right;">
                <button type="submit" class="btn-add"
                    style="border: none; cursor: pointer; padding: 12px 30px; font-size: 16px;">
                    <i class="fa fa-save"></i> Lưu Sản Phẩm
                </button>
            </div>

        </form>
    </div>
</div>