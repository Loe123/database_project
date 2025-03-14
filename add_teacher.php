<?php
session_start();
include "db.php";  // 確保你已經包含了資料庫連接的檔案

// 檢查是否已登入
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");  // 未登入則跳轉到登入頁面
    exit();
}

// 查詢所有系所
$sql = "SELECT * FROM department";  // 查詢所有系所資料
$result = $conn->query($sql);

// 確保查詢成功
if (!$result) {
    die("查詢錯誤: " . $conn->error);
}

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;  // 儲存所有系所資料
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 取得教師資料
    $teacher_id = $_POST['teacher_id'];
    $teacher_name = $_POST['teacher_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // 取得密碼
    $dept_id = $_POST['dept_id'];  // 系所ID

    // 不加密密碼，直接儲存
    $plain_password = $password;

    // 新增教師資料
    $sql = "INSERT INTO teacher (teacher_id, teacher_name, dept_id, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $teacher_id, $teacher_name, $dept_id, $email, $plain_password);
    
    if ($stmt->execute()) {
        $message = "教師資料新增成功！";
    } else {
        $message = "新增教師資料失敗！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?v=1.0">
    <title>新增教師資料</title>
</head>
<body>

<h2>管理員儀表板</h2>

<h3>新增教師資料</h3>
<form method="POST">
    <label for="teacher_id">教師編號：</label>
    <input type="text" name="teacher_id" required><br><br>

    <label for="teacher_name">教師名稱：</label>
    <input type="text" name="teacher_name" required><br><br>

    <label for="dept_id">系所：</label>
    <select name="dept_id" required>
        <option value="" disabled selected>選擇系所</option>
        <?php foreach ($departments as $department) { ?>
            <option value="<?= $department['dept_id'] ?>"><?= $department['dept_name'] ?></option>
        <?php } ?>
    </select><br><br>

    <label for="email">電子郵件：</label>
    <input type="email" name="email" required><br><br>

    <label for="password">密碼：</label>
    <input type="password" name="password" required><br><br>

    <button type="submit" class="add">新增教師</button>
</form>

<?php if (isset($message)) echo "<p>$message</p>"; ?>

<a href="admin_dashboard.php" class="back-btn">返回</a>

</body>
</html>
