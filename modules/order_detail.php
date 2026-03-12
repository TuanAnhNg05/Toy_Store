<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap');

    .od-container {
        font-family: 'Quicksand', sans-serif;
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .od-header {
        text-align: center;
        border-bottom: 2px dashed #eee;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .od-title {
        color: #FF6F00;
        font-weight: 800;
        font-size: 24px;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .od-meta {
        color: #777;
        font-size: 14px;
    }

    .item-row {
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #f5f5f5;
        padding: 15px 0;
    }

    .item-img {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }

    .item-price {
        color: #888;
        font-size: 13px;
    }

    .item-total {
        font-weight: 800;
        color: #333;
        font-size: 15px;
    }

    .od-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px dashed #eee;
        font-size: 18px;
        font-weight: 700;
        color: #333;
    }

    .od-total span:last-child {
        color: #D32F2F;
        font-size: 22px;
    }

    .btn-back {
        display: block;
        width: 100%;
        text-align: center;
        padding: 15px;
        margin-top: 30px;
        background: #f5f5f5;
        color: #555;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 700;
        transition: 0.3s;
    }

    .btn-back:hover {
        background: #FF6F00;
        color: white;
    }
</style>

<div class="od-container">
    <div class="od-header">
        <h1 class="od-title">Chi tiết đơn hàng #
            <?= $data['order_info']['id'] ?>
        </h1>
        <div class="od-meta">
            Ngày đặt:
            <?= date('d/m/Y H:i', strtotime($data['order_info']['created_at'])) ?>
            <br>
            Trạng thái:
            <?php
            $st = $data['order_info']['status'];
            if ($st == 0)
                echo '<span style="color:#f39c12">Đang xử lý</span>';
            if ($st == 1)
                echo '<span style="color:#2980b9">Đang giao hàng</span>';
            if ($st == 2)
                echo '<span style="color:#27ae60">Hoàn thành</span>';
            if ($st == 3)
                echo '<span style="color:#c0392b">Đã hủy</span>';
            ?>
        </div>
    </div>

    <div class="od-list">
        <?php foreach ($data['order_items'] as $item): ?>
            <div class="item-row">
                <img src="assets/images/<?= $item['image'] ?>" class="item-img" alt="sp">
                <div class="item-info">
                    <div class="item-name">
                        <?= $item['name'] ?>
                    </div>
                    <div class="item-price">
                        <?= number_format($item['price']) ?> đ x
                        <?= $item['quantity'] ?>
                    </div>
                </div>
                <div class="item-total">
                    <?= number_format($item['price'] * $item['quantity']) ?> đ
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="od-total">
        <span>Tổng tiền:</span>
        <span>
            <?= number_format($data['order_info']['total_money']) ?> ₫
        </span>
    </div>

    <a href="index.php?mod=orders" class="btn-back">
        <i class="fa fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>
