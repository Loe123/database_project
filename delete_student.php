<?php
// 連接資料庫
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 刪除學生資料處理
if (isset($_GET['delete_student_id'])) {
    $student_id = $_GET['delete_student_id'];

    // 開始事務處理
    $conn->begin_transaction();

    try {
        // 刪除學生的註冊資料（選課資料）
        $sql_delete_enrollment = "DELETE FROM enrollment WHERE student_id = '$student_id'";
        if ($conn->query($sql_delete_enrollment) === FALSE) {
            throw new Exception("刪除註冊資料失敗: " . $conn->error);
        }

        // 刪除學生資料
        $sql_delete_student = "DELETE FROM student WHERE student_id = '$student_id'";
        if ($conn->query($sql_delete_student) === TRUE) {
            // 提交事務
            $conn->commit();
            echo "學生資料已成功刪除";
        } else {
            throw new Exception("刪除學生資料失敗: " . $conn->error);
        }
    } catch (Exception $e) {
        // 如果發生錯誤，回滾事務
        $conn->rollback();
        echo "錯誤: " . $e->getMessage();
    }
}

// 顯示學生資料，並聯接系所資料
$sql = "
    SELECT s.student_id, s.student_name, s.email, s.password, d.dept_name 
    FROM student s
    LEFT JOIN department d ON s.dept_id = d.dept_id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>刪除學生資料</title>
</head>
<body>
    <h1>刪除學生資料</h1>

    <table border="1">
        <thead>
            <tr>
                <th>學生編號</th>
                <th>學生名稱</th>
                <th>系所名稱</th>
                <th>電子郵件</th>
                <th>密碼</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['student_id'] . "</td>";
                    echo "<td>" . $row['student_name'] . "</td>";
                    echo "<td>" . $row['dept_name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['password'] . "</td>";
                    echo "<td><a href='delete_student.php?delete_student_id=" . $row['student_id'] . "' onclick='return confirm(\"確定要刪除此學生資料嗎?\");' class='delete-btn'>刪除</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>無學生資料</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
<a href="admin_dashboard.php" class="back-btn">返回</a>
</html>

<?php
$conn->close();
?>
