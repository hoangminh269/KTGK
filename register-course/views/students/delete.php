<?php
require_once '../../config/database.php';

if (isset($_GET['MaSV'])) {
    $database = new Database();
    $conn = $database->connect();
    
    $maSV = $_GET['MaSV'];

    // Kiểm tra xem sinh viên có tồn tại không
    $sqlCheck = "SELECT Hinh FROM SinhVien WHERE MaSV = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $maSV);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $student = $result->fetch_assoc();
    
    if (!$student) {
        echo "<script>alert('Không tìm thấy sinh viên!'); window.location='index.php';</script>";
        exit();
    }

    // Kiểm tra ràng buộc khóa ngoại trước khi xóa
    $sqlCheckFK = "SELECT COUNT(*) as count FROM DangKy WHERE MaSV = ?";
    $stmtCheckFK = $conn->prepare($sqlCheckFK);
    $stmtCheckFK->bind_param("s", $maSV);
    $stmtCheckFK->execute();
    $resultFK = $stmtCheckFK->get_result()->fetch_assoc();
    
    if ($resultFK['count'] > 0) {
        echo "<script>alert('Không thể xóa sinh viên vì có dữ liệu liên quan!'); window.location='index.php';</script>";
        exit();
    }

    // Xóa ảnh nếu có
    if (!empty($student['Hinh'])) {
        $imagePath = "../../uploads/" . $student['Hinh'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Xoá sinh viên khỏi CSDL
    $sqlDelete = "DELETE FROM SinhVien WHERE MaSV = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("s", $maSV);
    
    if ($stmtDelete->execute()) {
        echo "<script>alert('Xoá sinh viên thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xoá sinh viên!'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Mã sinh viên không hợp lệ!'); window.location='index.php';</script>";
}
?>
