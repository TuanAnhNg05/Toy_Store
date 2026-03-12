<?php
session_start();
require_once 'config/db.php';

$db = new Database();
$conn = $db->conn;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password != $confirm) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $error = "Email này đã được sử dụng! Vui lòng chọn email khác.";
        } else {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT); // Mã hóa mật khẩu
            $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$fullname', '$email', '$hashed_pass', 'user')";

            if ($conn->query($sql)) {
                $success = "Đăng ký thành công! Đang chuyển hướng đăng nhập...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Lỗi hệ thống: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản | ToyStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #FFF3E0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #333;
            margin-bottom: 20px;
            display: block;
            text-decoration: none;
        }

        .logo span {
            color: #FF6F00;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: #FF6F00;
        }

        .btn-login {
            background: linear-gradient(45deg, #FF6F00, #FF8F00);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 111, 0, 0.4);
        }

        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #ffebee;
            color: #d32f2f;
            border: 1px solid #ffcdd2;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <a href="index.php" class="logo">Toy<span>Store</span> 🚀</a>
        <h3 style="color: #555; margin-top: 0;">Đăng ký thành viên</h3>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fa fa-exclamation-circle"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <input type="text" name="fullname" placeholder="Họ và tên của bạn" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email đăng nhập" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn-login">ĐĂNG KÝ NGAY</button>
        </form>

        <div style="margin-top: 20px; font-size: 14px; color: #666;">
            Đã có tài khoản?
            <a href="login.php" style="color: #FF6F00; font-weight: bold; text-decoration: none;">Đăng nhập</a>
        </div>

        <div style="margin-top: 15px;">
            <a href="index.php" style="color: #999; text-decoration: none; font-size: 13px;">← Quay về trang chủ</a>
        </div>
    </div>

</body>

</html>
