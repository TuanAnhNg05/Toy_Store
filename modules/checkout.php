<?php
$total_money = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_money += $item['price'] * $item['qty'];
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700;800&display=swap');

    .checkout-container {
        font-family: 'Quicksand', sans-serif;
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .page-title {
        text-align: center;
        color: #FF6F00;
        font-weight: 800;
        font-size: 32px;
        margin-bottom: 40px;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
    }

    .checkout-layout {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }

    .box-card {
        background: white;
        border-radius: 25px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        border: 1px solid white;
        transition: 0.3s;
    }

    .box-card:hover {
        box-shadow: 0 15px 50px rgba(255, 111, 0, 0.1);
    }

    .col-left {
        flex: 1.8;
    }

    .col-right {
        flex: 1;
        position: sticky;
        top: 20px;
    }

    .box-header {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px dashed #eee;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .box-header i {
        color: #FF6F00;
        font-size: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: #555;
        font-size: 14px;
    }

    .custom-input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #f5f5f5;
        background: #fcfcfc;
        border-radius: 15px;
        font-family: 'Quicksand', sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #333;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .custom-input:focus {
        border-color: #FF6F00;
        background: white;
        outline: none;
        box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.1);
    }

    textarea.custom-input {
        resize: vertical;
        min-height: 100px;
    }

    .cart-item-mini {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f9f9f9;
    }

    .item-thumb {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin-bottom: 4px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .item-meta {
        font-size: 12px;
        color: #888;
    }

    .item-price {
        font-size: 14px;
        font-weight: 800;
        color: #FF6F00;
    }

    .total-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px dashed #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-size: 16px;
        font-weight: 700;
        color: #555;
    }

    .total-value {
        font-size: 26px;
        font-weight: 800;
        color: #D32F2F;
    }

    .btn-confirm {
        width: 100%;
        margin-top: 25px;
        padding: 16px;
        background: linear-gradient(45deg, #FF6F00, #FF8F00);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(255, 111, 0, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-confirm:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 111, 0, 0.4);
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #888;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }

    .back-link:hover {
        color: #FF6F00;
    }

    @media (max-width: 768px) {
        .checkout-layout {
            flex-direction: column-reverse;
        }

        .col-right {
            width: 100%;
            position: static;
        }
    }
</style>

<div class="checkout-container">
    <h1 class="page-title">Thanh Toán & Giao Hàng</h1>

    <div class="checkout-layout">

        <div class="col-left box-card">
            <div class="box-header">
                <i class="fa fa-map-marker-alt"></i> Thông tin người nhận
            </div>

            <form action="index.php?mod=checkout&act=submit" method="POST">
                <div class="form-group">
                    <label class="form-label">Họ và tên người nhận:</label>
                    <input type="text" name="fullname" class="custom-input"
                        value="<?= isset($data['user_info']['fullname']) ? $data['user_info']['fullname'] : '' ?>"
                        placeholder="Ví dụ: Nguyễn Văn A" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại liên lạc:</label>
                    <input type="text" name="phone" class="custom-input"
                        value="<?= isset($data['user_info']['phone']) ? $data['user_info']['phone'] : '' ?>"
                        placeholder="Ví dụ: 0987..." required>
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ giao hàng chi tiết:</label>
                    <textarea name="address" class="custom-input" rows="3"
                        placeholder="Số nhà, tên đường, phường/xã, quận/huyện..."
                        required><?= isset($data['user_info']['address']) ? $data['user_info']['address'] : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Ghi chú thêm (Nếu có):</label>
                    <textarea name="note" class="custom-input" rows="2"
                        placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                </div>

                <button type="submit" class="btn-confirm">
                    Xác nhận đặt hàng <i class="fa fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <div class="col-right box-card">
            <div class="box-header">
                <i class="fa fa-shopping-bag"></i> Đơn hàng của bạn
            </div>

            <div class="cart-summary">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item-mini">
                        <img src="assets/images/<?= $item['image'] ?>" class="item-thumb" alt="sp">

                        <div class="item-details">
                            <div class="item-name"><?= $item['name'] ?></div>
                            <div class="item-meta">
                                Số lượng: <strong>x<?= $item['qty'] ?></strong>
                            </div>
                        </div>
                        <div class="item-price">
                            <?= number_format($item['price'] * $item['qty']) ?>đ
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="total-section">
                <div class="total-label">Tổng cộng:</div>
                <div class="total-value"><?= number_format($total_money) ?> ₫</div>
            </div>

            <a href="index.php?mod=cart" class="back-link">
                <i class="fa fa-arrow-left"></i> Quay lại sửa giỏ hàng
            </a>
        </div>

    </div>
</div>
