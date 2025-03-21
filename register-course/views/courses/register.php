<?php
require_once '../../config/database.php';
session_start();

// Kiểm tra sinh viên đã đăng nhập chưa
if (!isset($_SESSION['MaSV'])) {
    die("Bạn cần đăng nhập để đăng ký học phần! <a href='../auth/login.php'>Đăng nhập</a>");
}

$maSV = $_SESSION['MaSV']; // Lấy mã sinh viên từ session
$maHP = isset($_GET['MaHP']) ? $_GET['MaHP'] : ''; // Lấy mã học phần từ URL

if (!$maHP) {
    die("Không có mã học phần hợp lệ!");
}

// Kiểm tra xem sinh viên đã đăng ký học phần này chưa
$sqlCheck = "SELECT * FROM ChiTietDangKy 
             INNER JOIN DangKy ON ChiTietDangKy.MaDK = DangKy.MaDK
             WHERE DangKy.MaSV = ? AND ChiTietDangKy.MaHP = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("ss", $maSV, $maHP);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("Bạn đã đăng ký học phần này trước đó! <a href='index.php'>Quay lại</a>");
}

// Thêm vào bảng DangKy nếu sinh viên chưa có bản ghi
$sqlInsertDK = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), ?)";
$stmt = $conn->prepare($sqlInsertDK);
$stmt->bind_param("s", $maSV);
$stmt->execute();
$maDK = $conn->insert_id; // Lấy ID của bản ghi vừa chèn vào

// Thêm vào bảng ChiTietDangKy
$sqlInsertCTDK = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
$stmt = $conn->prepare($sqlInsertCTDK);
$stmt->bind_param("is", $maDK, $maHP);
$stmt->execute();

echo "Đăng ký thành công! <a href='index.php'>Quay lại danh sách học phần</a>";
?>