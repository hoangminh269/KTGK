<?php
// Lấy tham số từ URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Điều hướng đến trang phù hợp
switch ($page) {
    case 'login':
        require_once 'register-course/views/auth/login.php';
        break;
    
    case 'students':
        require_once 'register-course/views/students/index.php';
        break;
    
    case 'create-student':
        require_once 'register-course/views/students/create.php';
        break;
    
    case 'edit-student':
        require_once 'register-course/views/students/edit.php';
        break;
    
    case 'delete-student':
        require_once 'register-course/views/students/delete.php';
        break;

    case 'detail-student':
        require_once 'register-course/views/students/detail.php';
        break;

    case 'courses':
        require_once 'register-course/views/courses/index.php';
        break;

    case 'register-course':
        require_once 'register-course/views/courses/register.php';
        break;

    default:
        echo "Trang không tồn tại!";
        break;
}
?>
