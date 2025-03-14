<?php
session_start();
include "db.php";  // 確保你已經包含了資料庫連接的檔案

// 檢查是否已登入
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");  // 未登入則跳轉到登入頁面
    exit();
}

// 刪除教師資料處理
if (isset($_GET['delete_teacher_id'])) {
    $teacher_id = $_GET['delete_teacher_id'];

    // 刪除教師資料
    $sql = "DELETE FROM teacher WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $teacher_id);
    
    if ($stmt->execute()) {
        $message = "教師資料已成功刪除！";
    } else {
        $message = "刪除教師資料失敗！";
    }
}

// 查詢所有教師資料
$sql = "SELECT t.teacher_id, t.teacher_name, t.email, d.dept_name 
        FROM teacher t
        JOIN department d ON t.dept_id = d.dept_id";
$result = $conn->query($sql);

// 確保查詢成功
if (!$result) {
    die("查詢錯誤: " . $conn->error);
}

$teachers = [];
while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;  // 儲存所有教師資料
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>刪除教師資料</title>
</head>
<body>

<h2>管理員儀表板</h2>

<h3>刪除教師資料</h3>

<?php if (isset($message)) echo "<p>$message</p>"; ?>

<!-- 顯示教師資料 -->
<table border="1">
    <thead>
        <tr>
            <th>教師編號</th>
            <th>教師名稱</th>
            <th>電子郵件</th>
            <th>所屬系所</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($teachers) > 0) {
            foreach ($teachers as $teacher) {
                echo "<tr>";
                echo "<td>" . $teacher['teacher_id'] . "</td>";
                echo "<td>" . $teacher['teacher_name'] . "</td>";
                echo "<td>" . $teacher['email'] . "</td>";
                echo "<td>" . $teacher['dept_name'] . "</td>";
                echo "<td><a href='delete_teacher.php?delete_teacher_id=" . $teacher['teacher_id'] . "' onclick='return confirm(\"確定要刪除此教師資料嗎?\");' class='delete-btn'>刪除</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>無教師資料</td></tr>";
        }
        ?>
    </tbody>
</table>

<a href="admin_dashboard.php" class="back-btn">返回</a>

</body>
</html>

<?php
$conn->close();
?>
