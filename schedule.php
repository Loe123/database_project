<?php
session_start();
include "db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

// 修改查詢，查詢所有選修的課程，並連接教師資料
$sql = "SELECT course.course_name, teacher.teacher_name, timeslot.weekday, timeslot.start_time, timeslot.end_time
        FROM enrollment
        JOIN course ON enrollment.course_id = course.course_id
        JOIN course_timeslot ON course.course_id = course_timeslot.course_id
        JOIN timeslot ON course_timeslot.timeslot_id = timeslot.timeslot_id
        JOIN teacher ON course.teacher_id = teacher.teacher_id
        WHERE enrollment.student_id = ?
        ORDER BY FIELD(timeslot.weekday, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), timeslot.start_time";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[$row['weekday']][] = $row;
}

$weekdays = ["Mon" => "星期一", "Tue" => "星期二", "Wed" => "星期三", "Thu" => "星期四", "Fri" => "星期五", "Sat" => "星期六", "Sun" => "星期日"];

// 定義時間段，從早上8點到晚上8點
$timeslots = [
    '08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00',
    '16:00:00', '17:00:00', '18:00:00', '19:00:00', '20:00:00'
];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人課表</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<h2>個人課表</h2>

<table border="1">
    <tr>
        <th>時間</th>
        <?php foreach ($weekdays as $key => $day) { echo "<th>$day</th>"; } ?>
    </tr>
    <?php
    // 顯示從 8:00 到 20:00 的每一小時
    foreach ($timeslots as $timeslot) {
        echo "<tr>";
        echo "<td>$timeslot</td>"; // 顯示時間
        foreach ($weekdays as $dayKey => $day) {
            $hasClass = false;
            if (isset($schedule[$dayKey])) {
                foreach ($schedule[$dayKey] as $class) {
                    $course_start = new DateTime($class['start_time']);
                    $course_end = new DateTime($class['end_time']);
                    $current_time = new DateTime($timeslot);
                    
                    // 如果課程的開始時間小於等於當前時間，並且結束時間大於當前時間，則顯示課程
                    if ($course_start <= $current_time && $course_end > $current_time) {
                        echo "<td>" . $class['course_name'] . "<br><span class='teacher-name'>" . $class['teacher_name'] . "</span></td>";
                        $hasClass = true;
                        break; // 如果這一時間已經有課程了，就跳出內層循環
                    }
                }
            }
            if (!$hasClass) {
                echo "<td></td>"; // 沒有課程的時間段顯示空白
            }
        }
        echo "</tr>";
    }
    ?>
</table>

<a href="home.php" class="back-btn">返回</a>

</body>
</html>
