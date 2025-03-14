<?php
session_start();
include "db.php";

// 修改密碼功能
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    
    // 驗證新密碼長度 (例如：至少8位)
    if (strlen($new_password) < 8) {
        $error = "新密碼必須至少8位數字和字母。";
    } else {
        // 檢查使用者是否存在，並且舊密碼正確
        $sql = "SELECT student_id, password FROM student WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($student_id, $stored_password);

        if ($stmt->fetch() && $old_password === $stored_password) {
            // 密碼正確，開始更新新密碼
            $update_sql = "UPDATE student SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password, $email);
            $update_stmt->execute();

            $update_stmt->close();
            $success_message = "密碼已成功更新！";
        } else {
            $error = "舊密碼錯誤或該電子郵件無效。";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>修改密碼</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>修改密碼</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="註冊的 Email" required>
            <input type="password" name="old_password" placeholder="舊密碼" required>
            <input type="password" name="new_password" placeholder="新密碼" required>
            <button type="submit" class="student-btn-submit">修改密碼</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>

        <a href="login.php" class="btn-switch">返回登入</a>
    </div>
</div>

</body>
</html>
