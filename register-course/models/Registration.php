<?php
class Registration {
    private $conn;
    private $table_dangky = "DangKy";
    private $table_chitietdangky = "ChiTietDangKy";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đăng ký học phần cho sinh viên
    public function registerCourse($maSV, $maHP) {
        // Bước 1: Thêm bản ghi vào bảng DangKy nếu chưa có
        $sqlCheck = "SELECT MaDK FROM " . $this->table_dangky . " WHERE MaSV = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $maSV);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $maDK = $row['MaDK'];
        } else {
            $sqlInsertDangKy = "INSERT INTO " . $this->table_dangky . " (NgayDK, MaSV) VALUES (CURDATE(), ?)";
            $stmtInsert = $this->conn->prepare($sqlInsertDangKy);
            $stmtInsert->bind_param("s", $maSV);
            $stmtInsert->execute();
            $maDK = $this->conn->insert_id;
        }

        // Bước 2: Thêm bản ghi vào bảng ChiTietDangKy
        $sqlInsertChiTiet = "INSERT INTO " . $this->table_chitietdangky . " (MaDK, MaHP) VALUES (?, ?)";
        $stmtChiTiet = $this->conn->prepare($sqlInsertChiTiet);
        $stmtChiTiet->bind_param("is", $maDK, $maHP);
        return $stmtChiTiet->execute();
    }

    // Lấy danh sách học phần đã đăng ký của sinh viên
    public function getRegisteredCourses($maSV) {
        $sql = "SELECT HP.MaHP, HP.TenHP, HP.SoTinChi 
                FROM ChiTietDangKy CTDK
                JOIN DangKy DK ON CTDK.MaDK = DK.MaDK
                JOIN HocPhan HP ON CTDK.MaHP = HP.MaHP
                WHERE DK.MaSV = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Hủy đăng ký học phần
    public function unregisterCourse($maSV, $maHP) {
        $sql = "DELETE CTDK FROM ChiTietDangKy CTDK
                JOIN DangKy DK ON CTDK.MaDK = DK.MaDK
                WHERE DK.MaSV = ? AND CTDK.MaHP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $maSV, $maHP);
        return $stmt->execute();
    }
}
?>
