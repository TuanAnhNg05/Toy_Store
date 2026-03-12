<?php
session_start();
require_once '../config/db.php';

//1. KHỞI TẠO KẾT NỐI DATABASE 
$db = new Database();
$conn = $db->conn;

// 2. KIỂM TRA ĐĂNG NHẬP 
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

//3. XỬ LÝ HÀNH ĐỘNG (Đăng xuất, Xóa)
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    $img_query = $conn->query("SELECT image FROM products WHERE id=$id");
    if ($img_query && $img = $img_query->fetch_assoc()) {
        $file_path = "../assets/images/" . $img['image'];
        if (file_exists($file_path))
            @unlink($file_path);
    }

    $conn->query("DELETE FROM products WHERE id=$id");
    echo "<script>alert('Đã xóa sản phẩm thành công!'); window.location.href='index.php?page=products';</script>";
    exit();
}

//4. LẤY DỮ LIỆU
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$data = [];

if ($page == 'dashboard') {
    $data['revenue'] = $conn->query("SELECT SUM(total_money) as t FROM orders WHERE status != 'Đã hủy'")->fetch_assoc()['t'] ?? 0;
    $data['orders'] = $conn->query("SELECT COUNT(*) as t FROM orders")->fetch_assoc()['t'];
    $data['products'] = $conn->query("SELECT COUNT(*) as t FROM products")->fetch_assoc()['t'];
    $data['low_stock'] = $conn->query("SELECT COUNT(*) as t FROM products WHERE stock < 10")->fetch_assoc()['t'];
    $data['recent_orders'] = $conn->query("SELECT * FROM orders ORDER BY id DESC LIMIT 5");
}

$limit = 10;
$p = max(1, intval($_GET['p'] ?? 1));
$offset = ($p - 1) * $limit;

if ($page == 'products') {
    $data['total'] = $conn->query("SELECT COUNT(*) as t FROM products")->fetch_assoc()['t'];
    $data['pages'] = ceil($data['total'] / $limit);
    $data['list'] = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT $offset, $limit");
}

if ($page == 'orders') {
    $data['total'] = $conn->query("SELECT COUNT(*) as t FROM orders")->fetch_assoc()['t'];
    $data['pages'] = ceil($data['total'] / $limit);
    $data['list'] = $conn->query("SELECT * FROM orders ORDER BY id DESC LIMIT $offset, $limit");
}
?>

<?php require_once 'sidebar.php'; ?>

<div class="content">
    <h1 style="color: var(--text); font-weight: 800;">Xin chào, Admin! 👋</h1>

    <?php if ($page == 'dashboard'): ?>
        <p style="color: #78909C; margin-bottom: 30px;">Báo cáo nhanh tình hình kinh doanh.</p>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" style="color: var(--c-green);"><?php echo number_format($data['revenue']); ?> đ
                </div>
                <div style="font-size:13px; color:#999;">Doanh Thu</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: var(--c-blue);"><?php echo $data['orders']; ?></div>
                <div style="font-size:13px; color:#999;">Tổng Đơn Hàng</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: var(--c-orange);"><?php echo $data['products']; ?></div>
                <div style="font-size:13px; color:#999;">Sản Phẩm</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: var(--c-red);"><?php echo $data['low_stock']; ?></div>
                <div style="font-size:13px; color:#999;">Sắp hết hàng</div>
            </div>
        </div>

        <div class="section-header">
            <div class="section-title">5 Đơn hàng mới nhất</div>
            <a href="index.php?page=orders" style="color: var(--c-blue); text-decoration: none;">Xem tất cả <i
                    class="fa fa-arrow-right"></i></a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data['recent_orders']->num_rows > 0): ?>
                    <?php while ($row = $data['recent_orders']->fetch_assoc()):
                        $badge = 'status-wait';
                        if ($row['status'] == 'Đang giao hàng')
                            $badge = 'status-ship';
                        if ($row['status'] == 'Hoàn thành')
                            $badge = 'status-done';
                        if ($row['status'] == 'Đã hủy')
                            $badge = 'status-cancel';
                        ?>
                        <tr>
                            <td><b style="color:#333;">#<?php echo $row['id']; ?></b></td>
                            <td><b><?php echo $row['fullname'] ?? 'Khách lẻ'; ?></b></td>
                            <td style="color: var(--c-green); font-weight:bold;"><?php echo number_format($row['total_money']); ?> đ
                            </td>
                            <td><span class="status-badge <?php echo $badge; ?>"><?php echo $row['status']; ?></span></td>
                            <td><a href="order_detail.php?id=<?php echo $row['id']; ?>" class="btn-action btn-view"><i
                                        class="fa fa-eye"></i> Chi tiết</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px;">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($page == 'products'): ?>
        <div class="section-header">
            <div class="section-title">Danh sách sản phẩm (<?php echo $data['total']; ?>)</div>
            <a href="product_add.php" class="btn-add"><i class="fa fa-plus"></i> Thêm mới</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $data['list']->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../assets/images/<?php echo $row['image']; ?>" width="50" style="border-radius:5px;"></td>
                        <td><b><?php echo $row['name']; ?></b><br><span style="color:#999; font-size:12px;">ID:
                                #<?php echo $row['id']; ?></span></td>
                        <td style="color:#d32f2f; font-weight:bold;"><?php echo number_format($row['price']); ?> đ</td>
                        <td><?php echo $row['stock']; ?></td>
                        <td>
                            <a href="product_edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit"><i
                                    class="fa fa-edit"></i></a>
                            <a href="index.php?page=products&delete_id=<?php echo $row['id']; ?>"
                                onclick="return confirm('Xóa sản phẩm này?')" class="btn-action btn-delete"><i
                                    class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($data['pages'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $data['pages']; $i++): ?>
                    <a href="index.php?page=products&p=<?php echo $i; ?>"
                        class="page-link <?php echo ($i == $p) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    <?php elseif ($page == 'orders'): ?>
        <div class="section-header">
            <div class="section-title">Danh sách đơn hàng (<?php echo $data['total']; ?>)</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng / Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $data['list']->fetch_assoc()):
                    $st_code = $row['status'];

                    $st_text = $st_code;
                    $badge = 'status-wait';

                    if ($st_code == '0') {
                        $st_text = 'Chờ xử lý';
                        $badge = 'status-wait';
                    } elseif ($st_code == '1') {
                        $st_text = 'Đang giao hàng';
                        $badge = 'status-ship';
                    } elseif ($st_code == '2') {
                        $st_text = 'Hoàn thành';
                        $badge = 'status-done';
                    } elseif ($st_code == '3') {
                        $st_text = 'Đã hủy';
                        $badge = 'status-cancel';
                    } elseif ($st_code == 'Đang giao hàng') {
                        $st_text = 'Đang giao hàng';
                        $badge = 'status-ship';
                    } elseif ($st_code == 'Hoàn thành') {
                        $st_text = 'Hoàn thành';
                        $badge = 'status-done';
                    } elseif ($st_code == 'Đã hủy') {
                        $st_text = 'Đã hủy';
                        $badge = 'status-cancel';
                    }

                    $date = date('d/m/Y H:i', strtotime($row['created_at']));
                    ?>
                    <tr>
                        <td><b style="color:#333;">#<?php echo $row['id']; ?></b></td>
                        <td>
                            <b><?php echo $row['fullname'] ?? 'Khách lẻ'; ?></b><br>
                            <span style="font-size: 12px; color: #888;"><i class="fa fa-clock"></i> <?php echo $date; ?></span>
                        </td>
                        <td style="color: var(--c-green); font-weight: bold;">
                            <?php echo number_format($row['total_money']); ?> đ
                        </td>

                        <td>
                            <span class="status-badge <?php echo $badge; ?>"><?php echo $st_text; ?></span>
                        </td>

                        <td>
                            <a href="order_detail.php?id=<?php echo $row['id']; ?>" class="btn-action btn-view">
                                <i class="fa fa-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($data['pages'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $data['pages']; $i++): ?>
                    <a href="index.php?page=orders&p=<?php echo $i; ?>"
                        class="page-link <?php echo ($i == $p) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>
</body>

</html>