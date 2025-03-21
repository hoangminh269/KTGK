<?php
require_once '../../config/database.php';

$database = new Database();
$conn = $database->connect();

// Kiểm tra nếu có mã sinh viên được truyền vào
if (!isset($_GET['MaSV']) || empty($_GET['MaSV'])) {
    die("Mã sinh viên không hợp lệ!");
}

$maSV = $_GET['MaSV'];

// Lấy thông tin sinh viên từ CSDL
$sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $maSV);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Không tìm thấy sinh viên!");
}

// Lấy danh sách ngành học
$sqlNganh = "SELECT * FROM NganhHoc";
$resultNganh = $conn->query($sqlNganh);

// Xử lý khi người dùng cập nhật thông tin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $maNganh = $_POST['MaNganh'];
    $hinh = $student['Hinh'];

    // Xử lý upload ảnh nếu có
    if (!empty($_FILES['Hinh']['name'])) {
        $targetDir = "../../uploads/";
        $imageName = time() . "_" . basename($_FILES["Hinh"]["name"]); // Đổi tên file tránh trùng
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (!empty($student['Hinh']) && file_exists($targetDir . $student['Hinh'])) {
                unlink($targetDir . $student['Hinh']); // Xóa ảnh cũ
            }
            move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFile);
            $hinh = $imageName;
        } else {
            echo "<script>alert('Chỉ chấp nhận ảnh JPG, JPEG, PNG, GIF!');</script>";
        }
    }

    // Cập nhật thông tin sinh viên
    $sqlUpdate = "UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, MaNganh=?, Hinh=? WHERE MaSV=?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("ssssss", $hoTen, $gioiTinh, $ngaySinh, $maNganh, $hinh, $maSV);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật dữ liệu!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Chỉnh Sửa Thông Tin Sinh Viên</h4>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Mã SV:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($student['MaSV']) ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Họ Tên:</label>
                            <input type="text" name="HoTen" class="form-control" value="<?= htmlspecialchars($student['HoTen']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giới Tính:</label>
                            <select name="GioiTinh" class="form-select">
                                <option value="Nam" <?= $student['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                <option value="Nữ" <?= $student['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày Sinh:</label>
                            <input type="date" name="NgaySinh" class="form-control" value="<?= htmlspecialchars($student['NgaySinh']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngành Học:</label>
                            <select name="MaNganh" class="form-select">
                                <?php while ($row = $resultNganh->fetch_assoc()): ?>
                                    <option value="<?= $row['MaNganh'] ?>" <?= $row['MaNganh'] == $student['MaNganh'] ? 'selected' : '' ?>>
                                        <?= $row['TenNganh'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình Ảnh:</label>
                            <input type="file" name="Hinh" class="form-control">
                            <?php if (!empty($student['Hinh'])): ?>
                                <div class="mt-2">
                                    <img src="../../uploads/<?= htmlspecialchars($student['Hinh']) ?>" width="100" height="100" class="img-thumbnail">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Lưu Thay Đổi</button>
                            <a href="index.php" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
