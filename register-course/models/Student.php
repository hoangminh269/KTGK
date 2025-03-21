<?php
class Student {
    private $conn;
    private $table = "SinhVien";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách tất cả sinh viên
    public function getAll() {
        $sql = "SELECT SV.*, NH.TenNganh 
                FROM " . $this->table . " SV
                LEFT JOIN NganhHoc NH ON SV.MaNganh = NH.MaNganh";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy thông tin chi tiết sinh viên
    public function getById($maSV) {
        $sql = "SELECT SV.*, NH.TenNganh 
                FROM " . $this->table . " SV
                LEFT JOIN NganhHoc NH ON SV.MaNganh = NH.MaNganh
                WHERE MaSV = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thêm sinh viên mới
    public function create($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh) {
        $sql = "INSERT INTO " . $this->table . " (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh);
        return $stmt->execute();
    }

    // Cập nhật thông tin sinh viên
    public function update($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh) {
        $sql = "UPDATE " . $this->table . " 
                SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, Hinh = ?, MaNganh = ? 
                WHERE MaSV = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $maSV);
        return $stmt->execute();
    }

    // Xóa sinh viên
    public function delete($maSV) {
        $sql = "DELETE FROM " . $this->table . " WHERE MaSV = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        return $stmt->execute();
    }
}
?>
