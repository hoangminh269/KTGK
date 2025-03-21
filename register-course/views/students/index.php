<?php
require_once '../../config/database.php';
require_once '../../controllers/studentController.php';

$database = new Database();
$conn = $database->connect();
$studentController = new StudentController($conn);
$students = $studentController->getAllStudents();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4"><i class="fas fa-user-graduate"></i> Danh sách sinh viên</h2>
        <a href="create.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Thêm Sinh Viên</a>

        <table class="table table-bordered table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Hình Ảnh</th>
                    <th>Tên</th>
                    <th>Giới Tính</th>
                    <th>Ngày Sinh</th>
                    <th>Ngành học</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)) : ?>
                    <?php foreach ($students as $student) : ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <td>
                                <?php 
                                    $imagePath = "../../uploads/" . $student['Hinh'];
                                    if (empty($student['Hinh']) || !file_exists($imagePath)) {
                                        $imagePath = "../../uploads/002.jpg";
                                    }
                                ?>
                                <img src="<?= $imagePath ?>" alt="Hình sinh viên" width="50" height="50" class="rounded-circle">
                            </td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['GioiTinh']) ?></td>
                            <td><?= date("d/m/Y", strtotime($student['NgaySinh'])) ?></td>
                            <td><?= htmlspecialchars($student['major']) ?></td>
                            <td>
                                <a href="edit.php?MaSV=<?= $student['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="delete.php?MaSV=<?= $student['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center text-danger"><strong>Không có dữ liệu sinh viên</strong></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>