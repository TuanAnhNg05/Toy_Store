<?php
// Lấy dữ liệu từ biến $data mà Controller (index.php) đã chuẩn bị
$product = isset($data['product']) ? $data['product'] : null;
?>

<style>
    /* CSS đơn giản cho trang chi tiết */
    .detail-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 20px;
        font-family: 'Quicksand', sans-serif;
    }

    .detail-wrapper {
        display: flex;
        gap: 40px;
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .detail-left {
        flex: 1;
        text-align: center;
    }

    .detail-left img {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 10px;
        border: 1px solid #eee;
    }

    .detail-right {
        flex: 1.2;
    }

    .detail-name {
        font-size: 28px;
        font-weight: 800;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.3;
    }

    .detail-price {
        font-size: 26px;
        color: #D32F2F;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .detail-desc {
        font-size: 16px;
        color: #555;
        line-height: 1.6;
        margin-bottom: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .btn-add-cart {
        background: linear-gradient(45deg, #FF6F00, #FF8F00);
        color: white;
        padding: 15px 40px;
        text-decoration: none;
        font-weight: bold;
        font-size: 18px;
        border-radius: 50px;
        display: inline-block;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
    }

    .btn-add-cart:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 111, 0, 0.5);
    }

    .btn-back {
        display: inline-block;
        margin-bottom: 20px;
        color: #777;
        text-decoration: none;
    }

    .btn-back:hover {
        color: #FF6F00;
    }

    @media (max-width: 768px) {
        .detail-wrapper {
            flex-direction: column;
        }
    }
</style>

<div class="detail-container">

    <a href="index.php?mod=home" class="btn-back">
        <i class="fa fa-arrow-left"></i> Quay lại trang chủ
    </a>

    <?php if ($product): ?>
        <div class="detail-wrapper">
            <div class="detail-left">
                <img src="assets/images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            </div>

            <div class="detail-right">
                <h1 class="detail-name"><?= $product['name'] ?></h1>

                <div class="detail-price">
                    <?= number_format($product['price'], 0, ',', '.') ?> đ
                </div>

                <div class="detail-desc">
                    <strong>Mô tả sản phẩm:</strong><br>
                    <?= nl2br($product['description']) ?>
                </div>

                <a href="index.php?mod=cart&act=add&id=<?= $product['id'] ?>" class="btn-add-cart">
                    <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                </a>
            </div>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <i class="fa fa-exclamation-circle" style="font-size: 50px; color: #ccc;"></i>
            <h3>Sản phẩm không tồn tại hoặc đã bị xóa!</h3>
            <a href="index.php?mod=home">Về trang chủ</a>
        </div>
    <?php endif; ?>

</div>