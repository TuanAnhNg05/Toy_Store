<?php
// Xử lý Cập nhật / Xóa / Thêm vào giỏ hàng
if (!isset($_SESSION['cart']))
    $_SESSION['cart'] = [];

// 1. Thêm vào giỏ
if (isset($_GET['act']) && $_GET['act'] == 'add' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $product = $productModel->getProductById($id);

    if ($product) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'qty' => 1
            ];
        }
    }
    header("Location: index.php?mod=cart");
    exit;
}

// 2. Xóa sản phẩm
if (isset($_GET['act']) && $_GET['act'] == 'del' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header("Location: index.php?mod=cart");
    exit;
}

// 3. Cập nhật giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $qty = intval($qty);
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['qty'] = $qty;
        }
    }
    header("Location: index.php?mod=cart");
    exit;
}
?>

<style>
    .cart-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-top: 20px;
    }

    .page-title {
        text-align: center;
        color: #333;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 30px;
        font-size: 24px;
    }

    .page-title i {
        color: #FF6F00;
        margin-right: 10px;
    }

    /* Table Styles */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .cart-table thead th {
        background: #FFF3E0;
        color: #FF6F00;
        padding: 15px;
        text-align: left;
        font-weight: 700;
        border-bottom: 2px solid #FFCC80;
    }

    .cart-table thead th:first-child {
        border-radius: 10px 0 0 10px;
    }

    .cart-table thead th:last-child {
        border-radius: 0 10px 10px 0;
        text-align: center;
    }

    .cart-table tbody td {
        padding: 20px 15px;
        border-bottom: 1px dashed #eee;
        vertical-align: middle;
        color: #555;
        font-size: 15px;
    }

    .cart-table tbody tr:last-child td {
        border-bottom: none;
    }

    .cart-img-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-img-wrapper img {
        max-width: 100%;
        max-height: 100%;
    }

    .qty-input {
        width: 60px;
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 50px;
        font-weight: bold;
        outline: none;
        transition: 0.3s;
    }

    .qty-input:focus {
        border-color: #FF6F00;
    }

    .btn-trash {
        color: #999;
        font-size: 18px;
        transition: 0.3s;
        cursor: pointer;
    }

    .btn-trash:hover {
        color: #D32F2F;
        transform: scale(1.2);
    }

    .cart-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 2px solid #f5f5f5;
        padding-top: 30px;
    }

    .total-price {
        font-size: 20px;
        font-weight: 700;
        color: #333;
    }

    .total-price span {
        color: #D32F2F;
        font-size: 28px;
        margin-left: 10px;
    }

    .cart-actions {
        display: flex;
        gap: 15px;
    }

    .btn-back {
        text-decoration: none;
        color: #777;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: 0.3s;
    }

    .btn-back:hover {
        color: #FF6F00;
    }

    .btn-update {
        background: white;
        border: 2px solid #FF6F00;
        color: #FF6F00;
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-update:hover {
        background: #FFF3E0;
    }

    .btn-checkout {
        background: linear-gradient(45deg, #FF6F00, #FF8F00);
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: bold;
        text-decoration: none;
        box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
        transition: 0.3s;
        border: none;
        display: inline-block;
    }

    .btn-checkout:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 111, 0, 0.4);
    }

    @media (max-width: 768px) {
        .cart-footer {
            flex-direction: column;
            gap: 20px;
            align-items: flex-end;
        }

        .cart-table th,
        .cart-table td {
            padding: 10px;
            font-size: 13px;
        }

        .cart-img-wrapper {
            width: 50px;
            height: 50px;
        }
    }
</style>

<div class="container" style="max-width: 1100px;">

    <div class="cart-container">
        <h2 class="page-title"><i class="fa fa-shopping-cart"></i> Giỏ hàng của bạn</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <form action="" method="POST">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="width: 15%;">Đơn giá</th>
                            <th style="width: 10%; text-align: center;">SL</th>
                            <th style="width: 15%;">Thành tiền</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $id => $item):
                            $subtotal = $item['price'] * $item['qty'];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <div class="cart-img-wrapper">
                                            <img src="assets/images/<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                                        </div>
                                        <div style="font-weight: 600;"><?= $item['name'] ?></div>
                                    </div>
                                </td>
                                <td style="color: #777;"><?= number_format($item['price']) ?> đ</td>
                                <td style="text-align: center;">
                                    <input type="number" name="qty[<?= $id ?>]" value="<?= $item['qty'] ?>" min="1"
                                        class="qty-input">
                                </td>
                                <td style="color: #FF6F00; font-weight: bold;"><?= number_format($subtotal) ?> đ</td>
                                <td style="text-align: center;">
                                    <a href="index.php?mod=cart&act=del&id=<?= $id ?>" class="btn-trash"
                                        onclick="return confirm('Xóa sản phẩm này?')">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-footer">
                    <a href="index.php" class="btn-back"><i class="fa fa-arrow-left"></i> Tiếp tục mua hàng</a>

                    <div style="text-align: right;">
                        <div class="total-price" style="margin-bottom: 20px;">
                            Tổng cộng: <span><?= number_format($total) ?> đ</span>
                        </div>

                        <div class="cart-actions">
                            <button type="submit" name="update_cart" class="btn-update">
                                <i class="fa fa-sync-alt"></i> Cập nhật
                            </button>

                            <?php if (isset($_SESSION['user'])): ?>
                                <a href="index.php?mod=checkout" class="btn-checkout">
                                    Thanh toán ngay <i class="fa fa-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn-checkout">
                                    Đăng nhập để thanh toán <i class="fa fa-user"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; color: #999;">
                <i class="fa fa-shopping-basket" style="font-size: 60px; color: #eee; margin-bottom: 20px;"></i>
                <p style="font-size: 18px; margin-bottom: 20px;">Giỏ hàng của bạn đang trống!</p>
                <a href="index.php" class="btn-checkout">Mua sắm ngay</a>
            </div>
        <?php endif; ?>
    </div>

</div>
