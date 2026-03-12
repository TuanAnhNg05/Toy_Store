<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['user'])) {
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($email) && !empty($password)) {
        $db = new Database();
        $conn = $db->conn;

        $email = $conn->real_escape_string($email);

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $check = false;

            if (password_verify($password, $user['password'])) {
                $check = true;
            } elseif ($user['password'] == $password) {
                $check = true;

                $new_hash = password_hash($password, PASSWORD_DEFAULT);

                $uid = $user['id'];

                $conn->query("UPDATE users SET password = '$new_hash' WHERE id = $uid");
            }

            if ($check) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'phone' => $user['phone'] ?? '',
                    'address' => $user['address'] ?? ''
                ];

                if ($user['role'] == 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = "Mật khẩu không đúng!";
            }
        } else {
            $error = "Tài khoản không tồn tại!";
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - ToyStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: #FFF3E0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: #FF6F00;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #FF6F00;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #E65100;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .back-home {
            margin-top: 15px;
            display: block;
            color: #777;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>🚀 ĐĂNG NHẬP</h2>
        <?php if ($error)
            echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="email" placeholder="Email hoặc Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">VÀO HỆ THỐNG</button>
        </form>
        <div style="margin-top: 20px; font-size: 14px; color: #666;">
            Bạn chưa có tài khoản?
            <a href="register.php" style="color: #FF6F00; font-weight: bold; text-decoration: none;">Đăng ký ngay</a>
        </div>
        <a href="index.php" class="back-home">← Quay về trang chủ</a>
    </div>
</body>

</html>
