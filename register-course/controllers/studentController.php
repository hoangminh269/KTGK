<?php
require_once '../../config/database.php';

class StudentController {
    private static $conn;

    // Kết nối cơ sở dữ liệu
    public static function init() {
        if (!self::$conn) {
            self::$conn = Database::connect();
        }
    }

    // Lấy danh sách sinh viên
    public static function getAllStudents() {
        self::init();
        $sql = "SELECT MaSV AS id, HoTen AS name, GioiTinh, NgaySinh, Hinh, MaNganh AS major FROM SinhVien";
        $result = self::$conn->query($sql);

        if (!$result) {
            die("Lỗi truy vấn: " . self::$conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Thêm sinh viên mới với ảnh upload
    public static function create($maSV, $hoTen, $gioiTinh, $ngaySinh, $maNganh) {
        self::init();
        
        // Xử lý upload ảnh
        $hinh = self::uploadImage($_FILES['Hinh']);

        $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("ssssss", $maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh);
        return $stmt->execute();
    }

    // Lấy thông tin chi tiết sinh viên
    public static function getById($maSV) {
        self::init();
        $sql = "SELECT MaSV AS id, HoTen AS name, GioiTinh, NgaySinh, Hinh, MaNganh AS major FROM SinhVien WHERE MaSV = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Cập nhật thông tin sinh viên với ảnh mới
    public static function update($maSV, $hoTen, $gioiTinh, $ngaySinh, $maNganh) {
        self::init();
        
        // Xử lý upload ảnh (nếu có)
        $hinh = !empty($_FILES['Hinh']['name']) ? self::uploadImage($_FILES['Hinh']) : self::getById($maSV)['Hinh'];

        $sql = "UPDATE SinhVien SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, Hinh = ?, MaNganh = ? WHERE MaSV = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("ssssss", $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $maSV);
        return $stmt->execute();
    }

    // Xóa sinh viên
    public static function delete($maSV) {
        self::init();
        
        // Xóa ảnh khỏi thư mục
        $student = self::getById($maSV);
        if ($student && !empty($student['Hinh']) && file_exists("../../uploads/" . $student['Hinh'])) {
            unlink("../../uploads/" . $student['Hinh']);
        }

        $sql = "DELETE FROM SinhVien WHERE MaSV = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        return $stmt->execute();
    }

    // Hàm xử lý upload ảnh
    private static function uploadImage($file) {
        $targetDir = "../../uploads/";
        $fileName = time() . "_" . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Kiểm tra và lưu file
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $fileName;
        }
        return "default.png"; // Nếu upload thất bại, dùng ảnh mặc định
    }
}

// Xử lý các hành động CRUD từ request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["create"])) {
        StudentController::create($_POST["MaSV"], $_POST["HoTen"], $_POST["GioiTinh"], $_POST["NgaySinh"], $_POST["MaNganh"]);
        header("Location: ../../views/students/index.php");
        exit();
    } elseif (isset($_POST["update"])) {
        StudentController::update($_POST["MaSV"], $_POST["HoTen"], $_POST["GioiTinh"], $_POST["NgaySinh"], $_POST["MaNganh"]);
        header("Location: ../../views/students/index.php");
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    StudentController::delete($_GET["delete"]);
    header("Location: ../../views/students/index.php");
    exit();
}

// Khởi tạo kết nối CSDL khi tải trang
StudentController::init();
?>
