<?php
require_once '../../config/database.php';
require_once '../../controllers/studentController.php';

// Kết nối database
$database = new Database();
$conn = $database->connect();
$studentController = new StudentController($conn);

// Lấy danh sách ngành học từ database
$query = "SELECT MaNganh, TenNganh FROM NganhHoc";
$result = $conn->query($query);
$majors = $result->fetch_all(MYSQLI_ASSOC);

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maSV = $_POST["MaSV"];
    $hoTen = $_POST["HoTen"];
    $gioiTinh = $_POST["GioiTinh"];
    $ngaySinh = $_POST["NgaySinh"];
    $maNganh = $_POST["MaNganh"];
    $hinh = "";

    // Xử lý upload hình ảnh
    if (!empty($_FILES["Hinh"]["name"])) {
        $targetDir = "../../uploads/";
        $hinh = basename($_FILES["Hinh"]["name"]);
        $targetFilePath = $targetDir . $hinh;
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFilePath);
    }

    // Thêm sinh viên vào database
    if ($studentController->create($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh)) {
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Thêm sinh viên thất bại.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Thêm Sinh Viên</h2>
    <form method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow bg-light">
        <div class="mb-3">
            <label class="form-label">Mã Sinh Viên:</label>
            <input type="text" name="MaSV" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Họ Tên:</label>
            <input type="text" name="HoTen" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giới Tính:</label>
            <select name="GioiTinh" class="form-select" required>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày Sinh:</label>
            <input type="date" name="NgaySinh" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình Ảnh:</label>
            <input type="file" name="Hinh" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Ngành Học:</label>
            <select name="MaNganh" class="form-select" required>
                <option value="">-- Chọn ngành học --</option>
                <?php foreach ($majors as $major): ?>
                    <option value="<?= htmlspecialchars($major['MaNganh']) ?>">
                        <?= htmlspecialchars($major['TenNganh']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Thêm Sinh Viên</button>
            <a href="index.php" class="btn btn-secondary">Quay Lại</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
