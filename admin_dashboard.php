<?php 
session_start();

// 檢查是否已登入
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");  // 若未登入則跳轉至登入頁面
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>管理員主頁面</title>
    <link rel="stylesheet" href="admin_dashboard.css?v=1.0">  <!-- 引用新的CSS檔案 -->
</head>
<body>

<div class="container">
    <div class="header">
        <h1>歡迎來到管理員主頁面</h1>
        <p>管理員 ID: <?= $_SESSION['admin_id']; ?></p>
    </div>

    <div class="adminmenu">
        <h2>功能選單</h2>
        <ul>
            <li><a href="add_student.php" class="admin-btn">新增學生資料</a></li>
            <li><a href="delete_student.php" class="admin-btn-delete">刪除學生資料</a></li>

            <li><a href="add_course.php" class="admin-btn">新增課程</a></li>
            <li><a href="delete_course.php" class="admin-btn-delete">刪除課程</a></li>

            <li><a href="add_teacher.php" class="admin-btn">新增教師</a></li>
            <li><a href="delete_teacher.php" class="admin-btn-delete">刪除教師</a></li>
        </ul>
    </div>

    <a href="admin_login.php" class="btn-logout">登出</a>
</div>

</body>
</html>
