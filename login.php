<?php
session_start();
include "db.php";

// 登入功能
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT student_id, password FROM student WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($student_id, $stored_password);

    // 直接比對明文密碼
    if ($stmt->fetch() && $password === $stored_password) {
        $_SESSION["student_id"] = $student_id;
        header("Location: home.php");
    } else {
        $error = "登入失敗，請檢查帳號密碼";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>學生登入</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>學生登入</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="密碼" required>
            <button type="submit" class="student-btn-submit">登入</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <a href="admin_login.php" class="btn-switch">管理員登入</a>
        <!-- <a href="forgot_password.php" class="btn-forgot-password">忘記密碼？</a>  -->
        <a href="change_password.php" class="btn-forgot-password">修改密碼</a>
    </div>
</div>

</body>
</html>
