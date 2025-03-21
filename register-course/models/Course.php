<?php
class Course {
    private $conn;
    private $table = "HocPhan";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách tất cả học phần
    public function getAll() {
        $sql = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy thông tin chi tiết học phần
    public function getById($maHP) {
        $sql = "SELECT * FROM " . $this->table . " WHERE MaHP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thêm học phần mới
    public function create($maHP, $tenHP, $soTinChi) {
        $sql = "INSERT INTO " . $this->table . " (MaHP, TenHP, SoTinChi) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $maHP, $tenHP, $soTinChi);
        return $stmt->execute();
    }

    // Cập nhật thông tin học phần
    public function update($maHP, $tenHP, $soTinChi) {
        $sql = "UPDATE " . $this->table . " SET TenHP = ?, SoTinChi = ? WHERE MaHP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $tenHP, $soTinChi, $maHP);
        return $stmt->execute();
    }

    // Xóa học phần
    public function delete($maHP) {
        $sql = "DELETE FROM " . $this->table . " WHERE MaHP = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);
        return $stmt->execute();
    }
}
?>
