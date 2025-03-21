<?php
require_once '../../config/database.php';
require_once '../../controllers/courses/CourseController.php';

$courseController = new CourseController($conn);
$courses = $courseController->index();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Học Phần</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h2>Danh Sách Học Phần</h2>
    <a href="create.php">Thêm học phần</a>
    <table border="1">
        <tr>
            <th>Mã HP</th>
            <th>Tên HP</th>
            <th>Số tín chỉ</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course['MaHP'] ?></td>
            <td><?= $course['TenHP'] ?></td>
            <td><?= $course['SoTinChi'] ?></td>
            <td>
                <a href="edit.php?MaHP=<?= $course['MaHP'] ?>">Sửa</a> |
                <a href="../../controllers/courses/CourseController.php?delete=<?= $course['MaHP'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
