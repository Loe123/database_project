<?php
session_start();
include "db.php";

// 驗證 token 是否有效
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 查詢 token 是否存在且未過期
    $sql = "SELECT student_id, reset_expiry FROM student WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($student_id, $reset_expiry);

    if ($stmt->fetch()) {
        // 檢查 token 是否過期
        if (strtotime($reset_expiry) < time()) {
            $error = "您的重設密碼鏈接已過期";
        } else {
            // 顯示設置新密碼表單
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $new_password = $_POST["new_password"];

                // 更新新密碼並清除 token
                $update_sql = "UPDATE student SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE student_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $new_password, $student_id);
                $update_stmt->execute();

                // 完成後重定向到登入頁面
                header("Location: login.php");
            }
        }
    } else {
        $error = "無效的重設鏈接";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>重設密碼</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>重設密碼</h2>
        <form method="POST">
            <input type="password" name="new_password" placeholder="新密碼" required>
            <button type="submit" class="student-btn-submit">設置新密碼</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</div>

</body>
</html>
