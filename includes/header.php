<?php
// 1. CHUẨN BỊ DỮ LIỆU (Tách biệt Logic PHP lên đầu file)
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$current_mod = isset($_GET['mod']) ? $_GET['mod'] : 'home';
$current_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Xử lý tên hiển thị
$show_name = 'Khách';
if ($user) {
    // Ưu tiên hiển thị Fullname, nếu không có thì lấy Email, hoặc Username
    $show_name = !empty($user['fullname']) ? $user['fullname'] : $user['email'];
}

// Đếm giỏ hàng
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item)
        $cart_count += $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToyStore - Thế giới bé yêu</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /*CSS HEADER*/

        /* 1. Cấu trúc chung */
        .main-header {
            position: relative;
            z-index: 1000;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* 2. Logo & Menu */
        .logo {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .logo span {
            color: #FF6F00;
        }

        .nav-pills a {
            text-decoration: none;
            color: #555;
            font-weight: 600;
            padding: 8px 15px;
            border-radius: 20px;
            transition: 0.3s;
        }

        .nav-pills a:hover,
        .nav-pills a.active {
            color: #FF6F00;
            background: #FFF3E0;
        }

        /* 3. Cụm Icon bên phải */
        .header-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            transition: 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .btn-style-white {
            background: #fff;
            border: 1px solid #ddd;
            color: #d32f2f;
        }

        .btn-style-white:hover {
            background: #FFEBEE;
            border-color: #d32f2f;
        }

        .btn-style-orange {
            background: #FF6F00;
            border: 1px solid #FF6F00;
            color: #fff;
        }

        .btn-style-orange:hover {
            background: #E65100;
        }

        /* Badge số lượng giỏ hàng */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #d32f2f;
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            font-weight: bold;
        }

        /* 4. Dropdown User */
        .user-menu {
            position: relative;
            z-index: 9999;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            padding-top: 15px;
            min-width: 260px;
            z-index: 100000;
        }

        .user-menu:hover .dropdown-content {
            display: block;
            animation: fadeIn 0.3s;
        }

        .dropdown-inner {
            background-color: white;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            border: 1px solid #eee;
            overflow: hidden;
        }

        .dropdown-header {
            padding: 15px;
            background: #FF6F00;
            color: white;
            font-weight: bold;
            font-size: 14px;
            white-space: nowrap;
        }

        .dropdown-inner a {
            color: #444;
            padding: 12px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
            font-weight: 600;
            background: white;
            width: 100%;
            box-sizing: border-box;
            transition: 0.2s;
        }

        .dropdown-inner a i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
            color: #999;
        }

        .dropdown-inner a:hover {
            background-color: #FFF8E1;
            color: #FF6F00;
            padding-left: 20px;
        }

        .dropdown-inner a:hover i {
            color: #FF6F00;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-form {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 5px 10px;
        }

        .search-form input {
            border: none;
            outline: none;
            padding: 5px;
        }

        .search-form button {
            background: none;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <header class="main-header">
        <div class="header-flex"> <a href="index.php?mod=home" class="logo">Toy<span>Store</span> 🚀</a>

            <nav class="nav-pills">
                <a href="index.php?mod=home" class="<?= $current_mod == 'home' ? 'active' : '' ?>">Trang chủ</a>
                <a href="index.php?mod=category&id=1"
                    class="<?= ($current_mod == 'category' && $current_id == 1) ? 'active' : '' ?>">Lego</a>
                <a href="index.php?mod=category&id=2"
                    class="<?= ($current_mod == 'category' && $current_id == 2) ? 'active' : '' ?>">Xe Mô Hình</a>
                <a href="index.php?mod=category&id=3"
                    class="<?= ($current_mod == 'category' && $current_id == 3) ? 'active' : '' ?>">Búp Bê</a>
                <a href="index.php?mod=category&id=4"
                    class="<?= ($current_mod == 'category' && $current_id == 4) ? 'active' : '' ?>">Gấu Bông</a>
            </nav>

            <div class="header-icons">

                <form action="index.php" method="GET" class="search-form">
                    <input type="hidden" name="mod" value="search">
                    <input type="text" name="keyword" placeholder="Tìm..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>

                <a href="index.php?mod=cart" class="btn-circle btn-style-white" title="Giỏ hàng">
                    <i class="fa fa-shopping-basket"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>

                <div class="user-menu">
                    <?php if ($user): ?>
                        <a href="#" class="btn-circle btn-style-orange">
                            <i class="fa fa-user"></i>
                        </a>

                        <div class="dropdown-content">
                            <div class="dropdown-inner">
                                <div class="dropdown-header">
                                    Xin chào, <?= htmlspecialchars($show_name) ?>!
                                </div>

                                <?php if (isset($user['role']) && $user['role'] == 'admin'): ?>
                                    <a href="admin/index.php"><i class="fa fa-tachometer-alt"></i> Quản trị Shop</a>
                                <?php endif; ?>

                                <a href="index.php?mod=profile"><i class="fa fa-id-card"></i> Hồ sơ của tôi</a>
                                <a href="index.php?mod=orders"><i class="fa fa-box-open"></i> Đơn hàng đã mua</a>
                                <a href="index.php?mod=logout" style="color: #d32f2f;"><i class="fa fa-sign-out-alt"></i>
                                    Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn-circle btn-style-white" title="Đăng nhập / Đăng ký">
                            <i class="fa fa-user"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <main class="container" style="max-width: 1200px; margin: 20px auto;">