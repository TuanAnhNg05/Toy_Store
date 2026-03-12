<?php
class OrderModel
{
    public $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // 1. Tạo đơn hàng mới
    public function createOrder($user_id, $fullname, $email, $phone, $address, $note, $total_money)
    {
        $conn = $this->db->conn;
        $fullname = $conn->real_escape_string($fullname);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        $address = $conn->real_escape_string($address);
        $note = $conn->real_escape_string($note);
        $total_money = intval($total_money);
        $created_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO orders (user_id, fullname, email, phone, address, note, total_money, status, created_at) 
                VALUES ($user_id, '$fullname', '$email', '$phone', '$address', '$note', $total_money, 'Chờ xử lý', '$created_at')";

        if ($this->db->execute($sql)) {
            return $conn->insert_id;
        }
        return false;
    }

    // 2. Thêm sản phẩm vào chi tiết đơn hàng
    public function addOrderDetail($order_id, $product_id, $price, $quantity)
    {
        $order_id = intval($order_id);
        $product_id = intval($product_id);
        $price = intval($price);
        $quantity = intval($quantity);

        $sql = "INSERT INTO order_details (order_id, product_id, price, quantity) 
                VALUES ($order_id, $product_id, $price, $quantity)";
        return $this->db->execute($sql);
    }

    // 3. Lấy danh sách đơn hàng của 1 User
    public function getOrdersByUser($user_id)
    {
        $user_id = intval($user_id);
        $sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC";
        return $this->db->getAll($sql);
    }

    // 4. Lấy thông tin 1 đơn hàng cụ thể
    public function getOrderById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM orders WHERE id = $id";
        return $this->db->getOne($sql);
    }

    // 5. Hủy đơn hàng
    public function cancelOrder($id, $user_id)
    {
        $id = intval($id);
        $user_id = intval($user_id);
        $sql = "SELECT status FROM orders WHERE id = $id AND user_id = $user_id";
        $order = $this->db->getOne($sql);

        if ($order) {
            $st = $order['status'];
            if ($st == '0' || $st == 'Chờ xử lý') {
                $this->db->execute("UPDATE orders SET status = 'Đã hủy' WHERE id = $id");
                return true;
            }
        }
        return false;
    }
}
?>