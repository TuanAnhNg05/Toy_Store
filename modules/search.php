<style>
    .search-header {
        text-align: center;
        margin: 30px 0;
        border-bottom: 2px solid #FF6F00;
        line-height: 0.1em;
    }

    .search-header h2 {
        background: #FFF3E0;
        padding: 0 20px;
        color: #333;
        font-family: 'Quicksand', sans-serif;
        text-transform: uppercase;
        font-weight: 800;
        display: inline;
        font-size: 24px;
    }

    .search-header span {
        color: #FF6F00;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        padding-bottom: 40px;
    }

    .product-card {
        background: #fff;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        text-align: center;
        border: 1px solid #eee;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(255, 111, 0, 0.15);
        border-color: #FFB74D;
    }

    .img-wrapper {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .product-img {
        max-width: 100%;
        max-height: 100%;
        transition: 0.3s;
    }

    .product-name a {
        color: #333;
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 40px;
    }

    .product-price {
        color: #D32F2F;
        font-weight: 800;
        font-size: 16px;
        margin: 10px 0;
        display: block;
    }

    .btn-buy {
        background: #FF6F00;
        color: white;
        padding: 8px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: bold;
        display: block;
        transition: 0.2s;
        font-size: 14px;
    }

    .btn-buy:hover {
        background: #E65100;
        box-shadow: 0 4px 10px rgba(255, 111, 0, 0.3);
    }

    /* Empty State */
    .no-result {
        text-align: center;
        padding: 50px 20px;
        color: #777;
    }

    .no-result i {
        font-size: 60px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .no-result p {
        font-size: 18px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }
</style>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">

    <?php
    $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
    ?>

    <div class="search-header">
        <h2>Kết quả tìm kiếm: <span>"<?= $keyword ?>"</span></h2>
    </div>

    <div class="product-grid">
        <?php if (!empty($data['products'])): ?>
            <?php foreach ($data['products'] as $row): ?>
                <div class="product-card">
                    <a href="index.php?mod=detail&id=<?= $row['id'] ?>" class="img-wrapper">
                        <img src="assets/images/<?= $row['image'] ?>" class="product-img" alt="<?= $row['name'] ?>">
                    </a>

                    <div class="product-info">
                        <div class="product-name">
                            <a href="index.php?mod=detail&id=<?= $row['id'] ?>"><?= $row['name'] ?></a>
                        </div>
                        <span class="product-price"><?= number_format($row['price']) ?> đ</span>

                        <a href="index.php?mod=cart&act=add&id=<?= $row['id'] ?>" class="btn-buy">
                            <i class="fa fa-cart-plus"></i> Chọn mua
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
        <?php endif; ?>
    </div>

    <?php if (empty($data['products'])): ?>
        <div class="no-result">
            <i class="fa fa-search"></i>
            <p>Rất tiếc, không tìm thấy sản phẩm nào phù hợp với từ khóa <strong>"<?= $keyword ?>"</strong></p>
            <a href="index.php" style="color: #FF6F00; font-weight: bold; text-decoration: underline;">Xem tất cả sản
                phẩm</a>
        </div>
    <?php endif; ?>

</div>