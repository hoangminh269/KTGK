<?php
require_once '../../config/database.php';

// Kiểm tra nếu có mã sinh viên được truyền vào
if (!isset($_GET['MaSV'])) {
    die("Mã sinh viên không hợp lệ.");
}

$maSV = $_GET['MaSV'];

// Lấy thông tin sinh viên từ cơ sở dữ liệu
$sql = "SELECT sv.*, nh.TenNganh 
        FROM SinhVien sv 
        LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh
        WHERE MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $maSV);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Không tìm thấy sinh viên.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sinh Viên</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h2>Chi Tiết Sinh Viên</h2>
    <table border="1">
        <tr>
            <th>Mã SV</th>
            <td><?= htmlspecialchars($student['MaSV']) ?></td>
        </tr>
        <tr>
            <th>Họ Tên</th>
            <td><?= htmlspecialchars($student['HoTen']) ?></td>
        </tr>
        <tr>
            <th>Giới Tính</th>
            <td><?= htmlspecialchars($student['GioiTinh']) ?></td>
        </tr>
        <tr>
            <th>Ngày Sinh</th>
            <td><?= htmlspecialchars($student['NgaySinh']) ?></td>
        </tr>
        <tr>
            <th>Ngành Học</th>
            <td><?= htmlspecialchars($student['TenNganh']) ?></td>
        </tr>
        <tr>
            <th>Hình Ảnh</th>
            <td><img src="<?= htmlspecialchars($student['Hinh']) ?>" alt="Hình Sinh Viên" width="150"></td>
        </tr>
    </table>
    <br>
    <a href="index.php">Quay lại danh sách</a>
</body>
</html>
