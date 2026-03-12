<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap');

    .home-container {
        font-family: 'Quicksand', sans-serif;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
        margin-top: 20px;
    }

    .section-title {
        color: #FF6F00;
        font-size: 32px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 1px;
        margin: 0;
        display: inline-block;
        position: relative;
    }

    .section-title::after {
        content: '';
        display: block;
        width: 60%;
        height: 4px;
        background: #FF6F00;
        margin: 10px auto 0;
        border-radius: 2px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 40px;
    }

    .product-card {
        background: #fff;
        border-radius: 20px;
        padding: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid transparent;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(255, 111, 0, 0.2);
        border-color: #ffe0b2;
    }

    .img-wrapper {
        width: 100%;
        height: 220px;
        overflow: hidden;
        border-radius: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-img {
        transform: scale(1.1);
    }

    .product-info {
        text-align: center;
    }

    .product-name {
        font-weight: 700;
        font-size: 17px;
        margin-bottom: 8px;
        color: #333;
        height: 46px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.4;
    }

    .product-name a {
        color: #333;
        text-decoration: none;
        transition: 0.2s;
    }

    .product-name a:hover {
        color: #FF6F00;
    }

    .product-price {
        color: #D32F2F;
        font-weight: 800;
        font-size: 20px;
        margin-bottom: 15px;
        display: block;
    }

    .btn-buy {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background: linear-gradient(45deg, #FF6F00, #FF8F00);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(255, 111, 0, 0.2);
    }

    .btn-buy:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(255, 111, 0, 0.4);
    }

    .badge-new {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #D32F2F;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 40px;
    }

    .page-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #eee;
        color: #555;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }

    .page-link:hover,
    .page-link.active {
        background: #FF6F00;
        color: white;
        border-color: #FF6F00;
        box-shadow: 0 4px 10px rgba(255, 111, 0, 0.3);
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .img-wrapper {
            height: 160px;
        }

        .section-title {
            font-size: 24px;
        }

        .product-name {
            font-size: 14px;
            height: 40px;
        }

        .product-price {
            font-size: 16px;
        }

        .btn-buy {
            font-size: 13px;
            padding: 10px;
        }
    }
</style>

<div class="home-container">

    <div class="section-header">
        <h2 class="section-title">
            <i class="fa fa-rocket"></i> ĐỒ CHƠI MỚI VỀ
        </h2>
    </div>

    <div class="product-grid">
        <?php if (!empty($data['products'])): ?>
            <?php foreach ($data['products'] as $row): ?>
                <div class="product-card">
                    <span class="badge-new">NEW</span>

                    <a href="index.php?mod=detail&id=<?php echo $row['id']; ?>" class="img-wrapper">
                        <img src="assets/images/<?php echo $row['image']; ?>" class="product-img"
                            alt="<?php echo $row['name']; ?>">
                    </a>

                    <div class="product-info">
                        <div class="product-name">
                            <a href="index.php?mod=detail&id=<?php echo $row['id']; ?>">
                                <?php echo $row['name']; ?>
                            </a>
                        </div>
                        <span class="product-price"><?php echo number_format($row['price']); ?> đ</span>

                        <a href="index.php?mod=cart&act=add&id=<?php echo $row['id']; ?>" class="btn-buy"
                            onclick="return confirm('Đã thêm sản phẩm vào giỏ hàng! Đi đến giỏ hàng ngay?');">
                            <i class="fa fa-cart-plus"></i> Chọn mua
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 80px; color: #999;">
                <i class="fa fa-box-open" style="font-size: 60px; margin-bottom: 20px; opacity: 0.5;"></i>
                <p>Cửa hàng đang cập nhật sản phẩm...</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($data['total_pages'] > 1): ?>
        <div class="pagination">
            <?php if ($data['current_page'] > 1): ?>
                <a href="index.php?mod=home&p=<?php echo $data['current_page'] - 1; ?>" class="page-link"><i
                        class="fa fa-angle-left"></i></a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                <a href="index.php?mod=home&p=<?php echo $i; ?>"
                    class="page-link <?php echo ($i == $data['current_page']) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($data['current_page'] < $data['total_pages']): ?>
                <a href="index.php?mod=home&p=<?php echo $data['current_page'] + 1; ?>" class="page-link"><i
                        class="fa fa-angle-right"></i></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>