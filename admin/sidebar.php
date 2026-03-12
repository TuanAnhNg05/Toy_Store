<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Admin - Quản trị hệ thống</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">

    <style>
        /* --- CSS CHUNG CHO ADMIN --- */
        :root {
            --c-orange: #FF6F00;
            --c-blue: #42A5F5;
            --c-green: #66BB6A;
            --c-red: #EF5350;
            --bg-body: #FFF8E1;
            --white: #ffffff;
            --text: #37474F;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            --radius: 15px;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            margin: 0;
            background: linear-gradient(180deg, #FFF3E0 0%, #FFF8E1 100%);
            color: var(--text);
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: var(--white);
            height: 100vh;
            position: fixed;
            padding: 30px 20px;
            box-sizing: border-box;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .sidebar h2 {
            color: var(--c-orange);
            text-align: center;
            margin-bottom: 40px;
            font-weight: 800;
            font-size: 24px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #78909C;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 50px;
            margin-bottom: 10px;
            font-weight: 700;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: var(--c-orange);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
            transform: translateX(5px);
        }

        .content {
            margin-left: 260px;
            padding: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            transition: 0.3s;
            border: 1px solid #fff;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--c-orange);
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            margin: 5px 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--c-orange);
            border-left: 5px solid var(--c-orange);
            padding-left: 15px;
        }

        table {
            width: 100%;
            background: var(--white);
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        th,
        td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            background: var(--c-blue);
            color: white;
            text-transform: uppercase;
            font-size: 13px;
            font-weight: bold;
        }

        tr:hover td {
            background: #fafafa;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-wait {
            background: #FFF9C4;
            color: #FBC02D;
        }

        .status-ship {
            background: #E3F2FD;
            color: #1976D2;
        }

        .status-done {
            background: #E8F5E9;
            color: #388E3C;
        }

        .status-cancel {
            background: #FFEBEE;
            color: #D32F2F;
        }

        .btn-add {
            background: linear-gradient(45deg, var(--c-green), #81C784);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            margin-right: 5px;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #FFF8E1;
            color: #FFA000;
        }

        .btn-delete {
            background: #FFEBEE;
            color: #D32F2F;
        }

        .btn-view {
            background: #E3F2FD;
            color: #1976D2;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .page-link {
            display: inline-block;
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            background: white;
            color: #333;
        }

        .page-link.active {
            background: var(--c-orange);
            color: white;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>TOY ADMIN 🚀</h2>

        <a href="../index.php"
            style="background: #E0F7FA; color: #006064; margin-bottom: 20px; border: 1px dashed #006064;">
            <i class="fa fa-store"></i> <b>Xem trang web</b>
        </a>

        <?php $p = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>

        <a href="index.php?page=dashboard" class="<?= ($p == 'dashboard') ? 'active' : '' ?>">
            <i class="fa fa-home"></i> Tổng quan
        </a>
        <a href="index.php?page=products" class="<?= ($p == 'products') ? 'active' : '' ?>">
            <i class="fa fa-box"></i> Quản lý sản phẩm
        </a>
        <a href="index.php?page=orders" class="<?= ($p == 'orders') ? 'active' : '' ?>">
            <i class="fa fa-file-invoice-dollar"></i> Quản lý đơn hàng
        </a>

        <div style="margin-top: 50px; border-top: 1px dashed #ddd; padding-top: 20px;">
            <a href="index.php?action=logout" style="color: var(--c-red);">
                <i class="fa fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </div>