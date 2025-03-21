<?php
session_start();
require_once '../../config/database.php';

class AuthController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Xử lý đăng nhập
    public function login($username, $password) {
        $sql = "SELECT * FROM Users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../../views/dashboard.php");
            exit();
        } else {
            return "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    }

    // Xử lý đăng xuất
    public function logout() {
        session_destroy();
        header("Location: ../../views/auth/login.php");
        exit();
    }
}

// Kiểm tra request từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new AuthController($conn);

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $error = $auth->login($username, $password);
    }
}

if (isset($_GET['logout'])) {
    $auth = new AuthController($conn);
    $auth->logout();
}
?>
