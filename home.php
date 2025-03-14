<?php
session_start();
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>選課系統 - 主頁</title>
    <link rel="stylesheet" href="home.css?v=1.0">  <!-- 引用專用的CSS檔案 -->
</head>
<body>

<div class="student-container">
    <div class="header">
        <h1>歡迎來到選課系統</h1>
        <p>學生 ID: <?= $_SESSION['student_id']; ?></p>
    </div>

    <div class="menu">
        <h2>功能選單</h2>
        <ul>
            <li><a href="select_course.php" class="btn-menu">選課系統</a></li>
            <li><a href="schedule.php" class="btn-menu">個人課表</a></li>
            <li><a href="enrolled_courses.php" class="btn-menu">選課結果</a></li>
        </ul>
    </div>

    <a href="logout.php" class="btn-logout">登出</a>
</div>

</body>
</html>
