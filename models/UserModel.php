<?php
require_once 'config/db.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // 1. Hàm kiểm tra đăng nhập
    public function login($username, $password)
    {
        $username = $this->db->conn->real_escape_string($username);
        $sql = "SELECT * FROM users WHERE email = '$username'";
        $user = $this->db->getOne($sql);

        if ($user) {
            if ($password == $user['password']) {
                return $user;
            }
        }
        return false;
    }

    // 2. Lấy thông tin user theo ID
    public function getUserById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM users WHERE id = $id";
        return $this->db->getOne($sql);
    }

    // 3. Đăng ký tài khoản mới
    public function register($email, $password, $fullname)
    {
        $email = $this->db->conn->real_escape_string($email);
        $password = $this->db->conn->real_escape_string($password);
        $fullname = $this->db->conn->real_escape_string($fullname);

        $sql = "INSERT INTO users (email, password, fullname, phone, address) 
                VALUES ('$email', '$password', '$fullname', '', '')";

        return $this->db->execute($sql);
    }
    // 4. Cập nhật thông tin cá nhân
    public function updateUser($id, $fullname, $phone, $address, $password = null)
    {
        $id = intval($id);
        $fullname = $this->db->conn->real_escape_string($fullname);
        $phone = $this->db->conn->real_escape_string($phone);
        $address = $this->db->conn->real_escape_string($address);

        if ($password) {
            $password = $this->db->conn->real_escape_string($password);
            $sql = "UPDATE users SET fullname='$fullname', phone='$phone', address='$address', password='$password' WHERE id=$id";
        } else {
            $sql = "UPDATE users SET fullname='$fullname', phone='$phone', address='$address' WHERE id=$id";
        }

        return $this->db->execute($sql);
    }
}
?>