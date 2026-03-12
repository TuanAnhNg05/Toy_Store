<?php
session_start();
require_once '../config/db.php';

// 1. CHECK ADMIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); // Kết nối tay cho nhanh gọn

// 2. LẤY DỮ LIỆU CŨ
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $res->fetch_assoc();

if (!$product) {
    echo "<script>alert('Sản phẩm không tồn tại!'); location.href='index.php?page=products';</script>";
    exit;
}

// 3. XỬ LÝ LƯU 
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $cat_id = intval($_POST['category_id']);
    $desc = $_POST['description'];

    $image = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . rand(100, 999) . '.' . $file_extension; // Đổi tên file tránh trùng
        $target_file = $target_dir . $file_name;

        $allow = ['jpg', 'png', 'jpeg', 'webp'];
        if (in_array(strtolower($file_extension), $allow)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                if ($product['image'] != 'no-image.png' && file_exists("../assets/images/" . $product['image'])) {
                    unlink("../assets/images/" . $product['image']);
                }
                $image = $file_name;
            } else {
                $error = "Không thể tải ảnh lên!";
            }
        } else {
            $error = "Định dạng ảnh không hợp lệ!";
        }
    }

    if (empty($error)) {
        $name = $conn->real_escape_string($name);
        $desc = $conn->real_escape_string($desc);

        $sql = "UPDATE products SET 
                name='$name', price=$price, stock=$stock, 
                category_id=$cat_id, description='$desc', image='$image' 
                WHERE id=$id";

        if ($conn->query($sql)) {
            $success = "Cập nhật thành công!";
            $product['name'] = $name;
            $product['price'] = $price;
            $product['stock'] = $stock;
            $product['description'] = $desc;
            $product['image'] = $image;
            $product['category_id'] = $cat_id;
        } else {
            $error = "Lỗi Database: " . $conn->error;
        }
    }
}
?>

<?php require_once 'sidebar.php'; ?>

<div class="content">
    <div class="section-header">
        <div class="section-title">Chỉnh sửa sản phẩm: #<?= $id ?></div>
        <a href="index.php?page=products" class="btn-action" style="background: #ddd; color: #333;"><i
                class="fa fa-arrow-left"></i> Quay lại</a>
    </div>

    <?php if ($error): ?>
        <div style="padding:15px;background:#FFEBEE;color:#D32F2F;border-radius:10px;margin-bottom:20px;"><?= $error ?>
        </div><?php endif; ?>
    <?php if ($success): ?>
        <div style="padding:15px;background:#E8F5E9;color:#2E7D32;border-radius:10px;margin-bottom:20px;"><?= $success ?>
        </div><?php endif; ?>

    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <form action="" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:8px;">Tên sản phẩm:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:8px;">Danh mục:</label>
                    <select name="category_id"
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                        <option value="1" <?= $product['category_id'] == 1 ? 'selected' : '' ?>>Lego Lắp Ráp</option>
                        <option value="2" <?= $product['category_id'] == 2 ? 'selected' : '' ?>>Xe Mô Hình</option>
                        <option value="3" <?= $product['category_id'] == 3 ? 'selected' : '' ?>>Búp Bê</option>
                        <option value="4" <?= $product['category_id'] == 4 ? 'selected' : '' ?>>Gấu Bông</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:8px;">Giá bán:</label>
                    <input type="number" name="price" value="<?= $product['price'] ?>" required
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:8px;">Tồn kho:</label>
                    <input type="number" name="stock" value="<?= $product['stock'] ?>" required
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 20px; align-items: center;">
                <div>
                    <label style="font-weight:bold; display:block; margin-bottom:8px;">Ảnh hiện tại:</label>
                    <img src="../assets/images/<?= $product['image'] ?>" width="100"
                        style="border-radius:10px; border:1px solid #ddd;">
                </div>
                <div style="flex:1;">
                    <label style="font-weight:bold; display:block; margin-bottom:8px;">Thay đổi ảnh (nếu cần):</label>
                    <input type="file" name="image" style="padding:10px;">
                </div>
            </div>

            <div style="margin-top: 20px;">
                <label style="font-weight:bold; display:block; margin-bottom:8px;">Mô tả:</label>
                <textarea name="description" rows="5"
                    style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div style="margin-top: 30px; text-align: right;">
                <button type="submit" class="btn-add"
                    style="border:none; cursor:pointer; padding:12px 30px; font-size:16px;">
                    <i class="fa fa-save"></i> Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>