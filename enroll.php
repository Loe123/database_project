<?php
session_start();
include "db.php";  // 確保你已經包含了資料庫連接的檔案

// 檢查是否登入
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

// 檢查是否有提交課程資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $student_id = $_SESSION["student_id"];

    // 檢查該學生是否已經選擇此課程
    $sql_check = "SELECT * FROM enrollment WHERE student_id = ? AND course_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $student_id, $course_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // 如果學生已經選擇過此課程
        $error = "您已經選擇了此課程！";
    } else {
        // 插入選課資料
        $sql_enroll = "INSERT INTO enrollment (student_id, course_id) VALUES (?, ?)";
        $stmt_enroll = $conn->prepare($sql_enroll);

        if ($stmt_enroll) {
            $stmt_enroll->bind_param("ss", $student_id, $course_id);

            if ($stmt_enroll->execute()) {
                // 成功選課
                $success = "成功選擇課程！";
            } else {
                // 插入失敗
                $error = "選課失敗，請稍後再試。";
            }
            $stmt_enroll->close(); // 確保stmt_enroll已經初始化並執行後再close
        } else {
            $error = "選課過程中出現錯誤，請稍後再試。";
        }
    }

    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>選課結果</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>選課結果</h2>

<?php
// 顯示錯誤或成功訊息
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}

if (isset($success)) {
    echo "<p style='color: green;'>$success</p>";
}
?>

<a href="select_course.php" class="back-btn">返回選課頁面</a>

</body>
</html>
