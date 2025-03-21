<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/auth/AuthController.php';

$auth = new AuthController($conn);
$error = "";

// Kiểm tra nếu có lỗi từ AuthController
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $error = $auth->login($username, $password);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h2>Đăng Nhập</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="" method="post">
        <label>Tên đăng nhập:</label>
        <input type="text" name="username" required><br>

        <label>Mật khẩu:</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="login">Đăng Nhập</button>
    </form>
</body>
</html>
