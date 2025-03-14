<?php
session_start();
include "db.php";  // 確保你已經包含了資料庫連接的檔案

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    // 查詢管理員資料
    $sql = "SELECT * FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // 驗證管理員密碼（明文比對）
    if ($admin && $password == $admin['password']) {
        $_SESSION['admin_id'] = $admin['admin_id'];  // 設置 session 以便登錄狀態
        header("Location: admin_dashboard.php");  // 登入成功，跳轉至管理員主頁面
        exit();
    } else {
        $error = "帳號或密碼錯誤！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>管理員登入</title>
    <link rel="stylesheet" href="login.css?v=1.0">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>管理員登入</h2>
        <form method="POST">
            <input type="text" name="admin_id" placeholder="管理員編號" required>
            <input type="password" name="password" placeholder="密碼" required>
            <button type="submit" class="btn-submit">登入</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <a href="login.php" class="btn-switch">學生登入</a>
    </div>
</div>

</body>
</html>
