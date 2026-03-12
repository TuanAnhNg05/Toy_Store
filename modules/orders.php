<style>
    .page-title {
        text-align: center;
        margin-bottom: 30px;
        color: #FF6F00;
        font-weight: 800;
        text-transform: uppercase;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .order-table th {
        background: #FF6F00;
        color: white;
        padding: 15px;
        text-align: left;
    }

    .order-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #444;
    }

    .order-table tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        display: inline-block;
    }

    .bg-wait {
        background: #FFF9C4;
        color: #FBC02D;
    }

    .bg-ship {
        background: #E3F2FD;
        color: #1976D2;
    }

    .bg-done {
        background: #E8F5E9;
        color: #388E3C;
    }

    .bg-cancel {
        background: #FFEBEE;
        color: #D32F2F;
    }

    .btn-view {
        background: #E3F2FD;
        color: #1976D2;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 13px;
        margin-right: 5px;
    }

    .btn-cancel {
        background: #FFEBEE;
        color: #D32F2F;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 13px;
    }

    .btn-cancel:hover {
        background: #ffcdd2;
    }

    /* Alert Styles */
    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #C8E6C9;
    }

    .alert-error {
        background: #FFEBEE;
        color: #D32F2F;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #FFCDD2;
    }
</style>

<div class="container" style="max-width: 1000px;">
    <h2 class="page-title">📦 Lịch sử đơn hàng</h2>

    <?php if (!empty($data['msg']))
        echo $data['msg']; ?>

    <?php if (empty($data['orders'])): ?>
        <div style="text-align: center; padding: 50px; color: #777;">
            <i class="fa fa-box-open" style="font-size: 60px; margin-bottom: 20px; color: #eee;"></i>
            <p>Bạn chưa mua đơn hàng nào!</p>
            <a href="index.php" style="color: #FF6F00; font-weight: bold;">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['orders'] as $order):
                    $st_code = $order['status'];
                    $st_text = $st_code;
                    $bg = 'bg-wait';

                    if ($st_code == '0' || $st_code == 'Chờ xử lý') {
                        $st_text = 'Chờ xử lý';
                        $bg = 'bg-wait';
                    } elseif ($st_code == '1' || $st_code == 'Đang giao hàng') {
                        $st_text = 'Đang giao hàng';
                        $bg = 'bg-ship';
                    } elseif ($st_code == '2' || $st_code == 'Hoàn thành') {
                        $st_text = 'Hoàn thành';
                        $bg = 'bg-done';
                    } elseif ($st_code == '3' || $st_code == 'Đã hủy') {
                        $st_text = 'Đã hủy';
                        $bg = 'bg-cancel';
                    }
                    ?>
                    <tr>
                        <td><b>#<?= $order['id'] ?></b></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td style="color: #D32F2F; font-weight: bold;"><?= number_format($order['total_money']) ?> đ</td>
                        <td><span class="badge <?= $bg ?>"><?= $st_text ?></span></td>
                        <td>
                            <?php if ($st_code == '0' || $st_code == 'Chờ xử lý'): ?>
                                <a href="index.php?mod=orders&action=cancel&id=<?= $order['id'] ?>" class="btn-cancel"
                                    onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">
                                    <i class="fa fa-times"></i> Hủy đơn
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
