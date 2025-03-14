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

// 刪除課程資料處理
if (isset($_GET['delete_course_id'])) {
    $course_id = $_GET['delete_course_id'];

    // 開始事務處理
    $conn->begin_transaction();

    try {
        // 刪除課程時間表資料
        $sql_delete_timeslot = "DELETE FROM course_timeslot WHERE course_id = '$course_id'";
        if ($conn->query($sql_delete_timeslot) === FALSE) {
            throw new Exception("刪除課程時間表資料失敗: " . $conn->error);
        }

        // 刪除選課資料（如果有的話）
        $sql_delete_enrollment = "DELETE FROM enrollment WHERE course_id = '$course_id'";
        if ($conn->query($sql_delete_enrollment) === FALSE) {
            throw new Exception("刪除選課資料失敗: " . $conn->error);
        }

        // 刪除課程資料
        $sql_delete_course = "DELETE FROM course WHERE course_id = '$course_id'";
        if ($conn->query($sql_delete_course) === TRUE) {
            // 提交事務
            $conn->commit();
            echo "課程資料已成功刪除";
            // 跳轉回課程管理頁面
            header("Location: delete_course.php");
            exit();
        } else {
            throw new Exception("刪除課程資料失敗: " . $conn->error);
        }
    } catch (Exception $e) {
        // 如果發生錯誤，回滾事務
        $conn->rollback();
        echo "錯誤: " . $e->getMessage();
    }
}

// 取得所有科系資料
$sql_dept = "SELECT * FROM department";
$dept_result = $conn->query($sql_dept);

// 顯示課程資料，並聯合查詢教師姓名
$sql = "SELECT c.*, t.teacher_name FROM course c
        LEFT JOIN teacher t ON c.teacher_id = t.teacher_id";
if (isset($_GET['dept_id']) && !empty($_GET['dept_id'])) {
    $dept_id = $_GET['dept_id'];
    $sql .= " WHERE c.dept_id = '$dept_id'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>刪除課程資料</title>
</head>
<body>
    <h1>刪除課程資料</h1>

    <!-- 科系篩選下拉選單 -->
    <form action="delete_course.php" method="get">
        <label for="dept_id">選擇科系：</label>
        <select name="dept_id" id="dept_id">
            <option value="">所有科系</option>
            <?php
            if ($dept_result->num_rows > 0) {
                while ($row = $dept_result->fetch_assoc()) {
                    echo "<option value='" . $row['dept_id'] . "'>" . $row['dept_name'] . "</option>";
                }
            }
            ?>
        </select>
        <button type="submit">篩選</button>
    </form>

    <!-- 顯示課程資料 -->
    <table border="1">
        <thead>
            <tr>
                <th>課程代碼</th>
                <th>課程名稱</th>
                <th>學分數</th>
                <th>課程類型</th>
                <th>開課系所</th>
                <th>教室</th>
                <th>授課教師</th>
                <th>最大選課人數</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['course_id'] . "</td>"; 
                    echo "<td>" . $row['course_name'] . "</td>";
                    echo "<td>" . $row['credits'] . "</td>";
                    echo "<td>" . $row['course_type'] . "</td>";

                    // 顯示開課系所名稱
                    $dept_sql = "SELECT dept_name FROM department WHERE dept_id = " . $row['dept_id'];
                    $dept_result = $conn->query($dept_sql);
                    $dept_name = ($dept_result->num_rows > 0) ? $dept_result->fetch_assoc()['dept_name'] : "未知";
                    echo "<td>" . $dept_name . "</td>";

                    echo "<td>" . $row['classroom_id'] . "</td>";
                    // 顯示授課教師名稱
                    echo "<td>" . $row['teacher_name'] . "</td>";
                    echo "<td>" . $row['max_students'] . "</td>";
                    echo "<td><a href='delete_course.php?delete_course_id=" . $row['course_id'] . "' onclick='return confirm(\"確定要刪除此課程資料嗎?\");' class='delete-btn'>刪除</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>無課程資料</td></tr>";
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
