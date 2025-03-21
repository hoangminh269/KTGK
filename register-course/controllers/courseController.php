<?php
require_once '../../config/database.php';

class CourseController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách học phần
    public function index() {
        $sql = "SELECT * FROM HocPhan";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Thêm học phần mới
    public function create($maHP, $tenHP, $soTinChi) {
        $sql = "INSERT INTO HocPhan (MaHP, TenHP, SoTinChi) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $maHP, $tenHP, $soTinChi);
        return $stmt->execute();
    }

    // Lấy thông tin chi tiết học phần
    public function getById($maHP) {
        $sql = "SELECT * FROM HocPhan WHERE MaHP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Cập nhật thông tin học phần
    public function update($maHP, $tenHP, $soTinChi) {
        $sql = "UPDATE HocPhan SET TenHP = ?, SoTinChi = ? WHERE MaHP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $tenHP, $soTinChi, $maHP);
        return $stmt->execute();
    }

    // Xóa học phần
    public function delete($maHP) {
        $sql = "DELETE FROM HocPhan WHERE MaHP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);
        return $stmt->execute();
    }
}

// Khởi tạo controller để xử lý request
$courseController = new CourseController($conn);

// Xử lý các hành động CRUD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["create"])) {
        $courseController->create($_POST["MaHP"], $_POST["TenHP"], $_POST["SoTinChi"]);
        header("Location: ../../views/courses/index.php");
    } elseif (isset($_POST["update"])) {
        $courseController->update($_POST["MaHP"], $_POST["TenHP"], $_POST["SoTinChi"]);
        header("Location: ../../views/courses/index.php");
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    $courseController->delete($_GET["delete"]);
    header("Location: ../../views/courses/index.php");
}
?>
