<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// XỬ LÝ CẬP NHẬT TRẠNG THÁI
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE orders SET status='$status' WHERE id=$id");
    echo "<script>alert('Đã cập nhật trạng thái đơn hàng!');</script>";
}

// LẤY THÔNG TIN ĐƠN HÀNG
$order_query = $conn->query("SELECT * FROM orders WHERE id=$id");
$order = $order_query->fetch_assoc();

if (!$order) {
    echo "<script>alert('Đơn hàng không tồn tại!'); location.href='index.php?page=orders';</script>";
    exit;
}

// LẤY CHI TIẾT SẢN PHẨM TRONG ĐƠN
$items = $conn->query("
    SELECT od.*, p.name, p.image 
    FROM order_details od 
    JOIN products p ON od.product_id = p.id 
    WHERE od.order_id = $id
");
?>

<?php require_once 'sidebar.php'; ?>

<div class="content">
    <div class="section-header">
        <div class="section-title">Chi tiết đơn hàng: #<?= $id ?></div>
        <a href="index.php?page=orders" class="btn-action" style="background: #ddd; color: #333;"><i
                class="fa fa-arrow-left"></i> Quay lại</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

        <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; color: var(--c-orange);">📦 Sản phẩm đặt mua</h3>
            <table style="box-shadow: none;">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>SL</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $items->fetch_assoc()): ?>
                        <tr>
                            <td style="display: flex; align-items: center; gap: 10px; border: none;">
                                <img src="../assets/images/<?= $row['image'] ?>" width="50" style="border-radius: 5px;">
                                <b><?= $row['name'] ?></b>
                            </td>
                            <td style="border: none;"><?= number_format($row['price']) ?> đ</td>
                            <td style="border: none;">x<?= $row['quantity'] ?></td>
                            <td style="border: none; font-weight: bold; color: var(--c-red);">
                                <?= number_format($row['price'] * $row['quantity']) ?> đ
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div style="text-align: right; margin-top: 20px; font-size: 18px;">
                Tổng tiền thanh toán: <b
                    style="color: var(--c-red); font-size: 24px;"><?= number_format($order['total_money']) ?> đ</b>
            </div>
        </div>

        <div
            style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); height: fit-content;">
            <h3 style="margin-top: 0; color: var(--c-blue);">👤 Thông tin khách hàng</h3>
            <p><i class="fa fa-user" style="width: 20px;"></i> <b><?= $order['fullname'] ?></b></p>
            <p><i class="fa fa-phone" style="width: 20px;"></i> <?= $order['phone'] ?></p>
            <p><i class="fa fa-map-marker-alt" style="width: 20px;"></i> <?= $order['address'] ?></p>
            <p><i class="fa fa-clock" style="width: 20px;"></i>
                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>

            <hr style="border: 0; border-top: 1px dashed #ddd; margin: 20px 0;">

            <h3 style="margin-top: 0; color: var(--c-green);">⚙️ Cập nhật trạng thái</h3>
            <form action="" method="POST">
                <?php
                $cur_st = $order['status'];
                ?>
                <select name="status"
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 15px; font-weight: bold;">
                    <option value="0" <?= ($cur_st == '0' || $cur_st == 'Chờ xử lý') ? 'selected' : '' ?>>🕒 Chờ xử lý
                    </option>
                    <option value="1" <?= ($cur_st == '1' || $cur_st == 'Đang giao hàng') ? 'selected' : '' ?>>🚚 Đang giao
                        hàng</option>
                    <option value="2" <?= ($cur_st == '2' || $cur_st == 'Hoàn thành') ? 'selected' : '' ?>>✅ Hoàn thành
                    </option>
                    <option value="3" <?= ($cur_st == '3' || $cur_st == 'Đã hủy') ? 'selected' : '' ?>>❌ Đã hủy</option>
                </select>
                <button type="submit" class="btn-add" style="width: 100%; border: none; cursor: pointer;">Lưu trạng
                    thái</button>
            </form>
        </div>
    </div>
</div>