<?php
session_start();
include "db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

// 取得學生所屬系別
$sql_student = "SELECT dept_id FROM student WHERE student_id = ?";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->bind_param("s", $student_id);
$stmt_student->execute();
$result_student = $stmt_student->get_result();
$student_data = $result_student->fetch_assoc();
$student_dept_id = $student_data['dept_id']; // 學生的系別

// 退選功能
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST["course_id"];
    $sql = "DELETE FROM enrollment WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $student_id, $course_id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>退選成功</p>";
    } else {
        echo "<p style='color: red;'>退選失敗</p>";
    }
}

// 取得選課資料，包含開課系別和課程類型（必修/選修）
$sql = "SELECT course.course_id, course.course_name, course.credits, course.dept_id, course.course_type, department.dept_name
        FROM enrollment 
        JOIN course ON enrollment.course_id = course.course_id 
        JOIN department ON course.dept_id = department.dept_id
        WHERE enrollment.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// 計算總學分 & 系外修課學分
$total_credits = 0;
$external_credits = 0; // 系外學分
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>選課結果</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .external-course {
            background-color: #d0ebff; /* 淺藍色背景 */
        }
    </style>
</head>
<body>

<h2>選課結果</h2>

<table>
    <tr>
        <th>課程代碼</th>
        <th>課程名稱</th>
        <th>學分</th>
        <th>開課系別</th>
        <th>課程類型</th>
        <th>操作</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { 
        $total_credits += $row['credits'];

        // 檢查是否為系外修課
        $is_external = ($row['dept_id'] !== $student_dept_id);
        if ($is_external) {
            $external_credits += $row['credits'];
        }
    ?>
    <tr class="<?= $is_external ? 'external-course' : '' ?>"> <!-- 如果是系外課，套用淺藍色背景 -->
        <td><?= $row['course_id'] ?></td>
        <td><?= $row['course_name'] ?></td>
        <td><?= $row['credits'] ?></td>
        <td><?= $row['dept_name'] ?></td>
        <td><?= $row['course_type'] ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">
                <button type="submit" class="btn">退選</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

<p><strong>總學分: <?= $total_credits ?> 學分</strong></p>
<p><strong>系外修課學分: <?= $external_credits ?> 學分</strong></p>

<a href="home.php" class="back-btn">返回</a>

</body>
</html>
