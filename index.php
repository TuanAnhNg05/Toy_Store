<?php
session_start();

// 1. LOAD CẤU HÌNH & TẤT CẢ MODELS
require_once 'config/db.php';
require_once 'models/ProductModel.php';
require_once 'models/UserModel.php';
require_once 'models/OrderModel.php';

// 2. KHỞI TẠO CÁC ĐỐI TƯỢNG (OBJECTS)
$db = new Database();
$productModel = new ProductModel();
$userModel = new UserModel();
$orderModel = new OrderModel();

// 3. ĐIỀU HƯỚNG (ROUTER)
$mod = isset($_GET['mod']) ? $_GET['mod'] : 'home';
$act = isset($_GET['act']) ? $_GET['act'] : 'view';
$data = []; // Biến chứa dữ liệu gửi sang View

// 4. XỬ LÝ LOGIC (CONTROLLER)
switch ($mod) {

    // --- TRANG CHỦ (CÓ PHÂN TRANG) ---
    case 'home':
        $limit = 12;
        $page = isset($_GET['p']) ? intval($_GET['p']) : 1;
        if ($page < 1)
            $page = 1;
        $offset = ($page - 1) * $limit;

        $total_records = $productModel->countAll();
        $total_pages = ceil($total_records / $limit);

        $products = $productModel->getProductsPagination($offset, $limit);

        $data['products'] = $products;
        $data['current_page'] = $page;
        $data['total_pages'] = $total_pages;
        break;

    // --- DANH MỤC SẢN PHẨM (ĐƠN GIẢN) ---
    case 'category':
        $cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $cat_names = [
            1 => 'LEGO LẮP RÁP',
            2 => 'XE MÔ HÌNH',
            3 => 'BÚP BÊ',
            4 => 'GẤU BÔNG'
        ];

        $cat_name = isset($cat_names[$cat_id]) ? $cat_names[$cat_id] : 'SẢN PHẨM';

        $data['products'] = $productModel->getProductsByCategory($cat_id);
        $data['category_name'] = $cat_name;
        break;

    // --- CHI TIẾT SẢN PHẨM ---
    case 'detail':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $data['product'] = $productModel->getProductById($id);
        $mod = 'detail';
        break;

    // --- TÌM KIẾM ---
    case 'search':
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $data['products'] = $productModel->searchProducts($keyword);
        break;

    // --- GIỎ HÀNG ---
    case 'cart':
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if ($act == 'add') {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] += 1;
            } else {
                $product = $productModel->getProductById($id);
                if ($product) {
                    $_SESSION['cart'][$id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'image' => $product['image'],
                        'price' => $product['price'],
                        'qty' => 1
                    ];
                }
            }
            header("Location: index.php?mod=cart");
            exit;
        }

        if ($act == 'delete') {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            unset($_SESSION['cart'][$id]);
            header("Location: index.php?mod=cart");
            exit;
        }

        if ($act == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($_POST['qty'] as $pid => $qty) {
                if (isset($_SESSION['cart'][$pid])) {
                    $_SESSION['cart'][$pid]['qty'] = max(1, intval($qty));
                }
            }
            header("Location: index.php?mod=cart");
            exit;
        }

        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item)
            $total_amount += $item['price'] * $item['qty'];
        $data['cart'] = $_SESSION['cart'];
        $data['total_amount'] = $total_amount;
        break;

    // --- THANH TOÁN ---
    // --- XỬ LÝ THANH TOÁN (CHECKOUT) ---
    case 'checkout':
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }

        if (empty($_SESSION['cart'])) {
            header("Location: index.php");
            exit;
        }

        $user = $_SESSION['user'];

        // XỬ LÝ KHI BẤM NÚT "ĐẶT HÀNG"
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $email = $user['email'];

            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $orderId = $orderModel->createOrder($user['id'], $fullname, $email, $phone, $address, $note, $total);

            if ($orderId) {

                foreach ($_SESSION['cart'] as $pId => $item) {
                    $orderModel->addOrderDetail($orderId, $pId, $item['price'], $item['qty']);
                }

                unset($_SESSION['cart']);
                echo "<script>alert('Đặt hàng thành công!'); window.location.href='index.php?mod=orders';</script>";
                exit;
            } else {
                echo "<script>alert('Lỗi đặt hàng!');</script>";
            }
        }

        require_once 'modules/checkout.php';
        break;

    // --- LỊCH SỬ ĐƠN HÀNG ---
    case 'orders':
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $msg = "";
        if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
            $order_id = intval($_GET['id']);

            if ($orderModel->cancelOrder($order_id, $user_id)) {
                $msg = '<div class="alert-success"><i class="fa fa-check-circle"></i> Đã hủy đơn hàng thành công!</div>';
            } else {
                $msg = '<div class="alert-error"><i class="fa fa-times-circle"></i> Không thể hủy đơn này (Đã vận chuyển hoặc lỗi)!</div>';
            }
        }

        $orders = $orderModel->getOrdersByUser($user_id);

        $data['orders'] = $orders;
        $data['msg'] = $msg;
        break;
    // --- CHI TIẾT ĐƠN HÀNG ---
    case 'order_detail':
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }

        $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $order = $orderModel->getOrderById($order_id);
        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Bạn không có quyền xem đơn hàng này!'); location.href='index.php?mod=orders';</script>";
            exit;
        }

        $items = $orderModel->getOrderDetails($order_id);

        $data['order_info'] = $order;
        $data['order_items'] = $items;
        break;

    // --- HỒ SƠ CÁ NHÂN ---
    case 'profile':
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }

        $user = $_SESSION['user'];
        $msg = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $new_pass = !empty($_POST['password']) ? $_POST['password'] : null;

            $result = $userModel->updateUser($user['id'], $fullname, $phone, $address, $new_pass);

            if ($result) {
                $_SESSION['user']['fullname'] = $fullname;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['address'] = $address;

                $msg = '<div class="alert-success"><i class="fa fa-check-circle"></i> Cập nhật thành công!</div>';
                $user = $_SESSION['user'];
            } else {
                $msg = '<div class="alert-error"><i class="fa fa-times-circle"></i> Có lỗi xảy ra, vui lòng thử lại.</div>';
            }
        }

        $data['user'] = $user;
        $data['msg'] = $msg;
        break;



    // --- ĐĂNG XUẤT ---
    case 'logout':
        session_destroy();
        header("Location: login.php");
        exit;
        break;

    default:
        header("Location: index.php?mod=home");
        break;
}

// 5. LOAD GIAO DIỆN (VIEW)
require_once 'includes/header.php';

$path = "modules/{$mod}.php";
if (file_exists($path)) {
    require_once $path;
} else {
    require_once "modules/home.php";
}

require_once 'includes/footer.php';
?>