<?php
session_start();
include "db.php";  // 確保你已經包含了資料庫連接的檔案

// 檢查是否登入
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

// 處理搜尋條件
$search_course = isset($_GET['search_course']) ? $_GET['search_course'] : '';
$search_dept = isset($_GET['search_dept']) ? $_GET['search_dept'] : '';

// 查詢課程
$sql = "SELECT course.*, teacher.teacher_name, department.dept_name, classroom.capacity,
        (SELECT COUNT(*) FROM enrollment WHERE enrollment.course_id = course.course_id) AS enrolled_students 
        FROM course 
        JOIN teacher ON course.teacher_id = teacher.teacher_id
        JOIN department ON course.dept_id = department.dept_id
        JOIN classroom ON course.classroom_id = classroom.classroom_id
        WHERE course.is_open = 1";

// 根據搜尋條件加上篩選條件
if (!empty($search_course)) {
    $sql .= " AND course.course_id LIKE '%$search_course%'";
}

if (!empty($search_dept)) {
    $sql .= " AND department.dept_name = '$search_dept'";
}

$result = $conn->query($sql);

// 確保查詢成功
if (!$result) {
    die("查詢錯誤: " . $conn->error);
}

// 查詢課程的上課時段
function getCourseTimeslot($course_id, $conn) {
    $sql_timeslot = "SELECT timeslot.timeslot_id 
    FROM course_timeslot 
    JOIN timeslot ON course_timeslot.timeslot_id = timeslot.timeslot_id 
    WHERE course_timeslot.course_id = ?";

    $stmt = $conn->prepare($sql_timeslot);
    $stmt->bind_param("s", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $timeslots = [];
    while ($row = $result->fetch_assoc()) {
        $timeslots[] = $row;
    }

    return $timeslots;
}
// 檢查是否有衝堂
function checkConflict($new_timeslots, $student_schedule) {
    foreach ($new_timeslots as $new_timeslot) {
        // 解析新課程的 timeslot_id，例如 "Mon_10~11"
        list($new_weekday, $new_time_range) = explode('_', $new_timeslot['timeslot_id']);
        list($new_start, $new_end) = explode('~', $new_time_range);
        $new_start = intval($new_start); // 轉換為整數
        $new_end = intval($new_end);

        foreach ($student_schedule as $existing) {
            // 解析學生已選課的 timeslot_id，例如 "Mon_9~10"
            list($existing_weekday, $existing_time_range) = explode('_', $existing['timeslot_id']);
            list($existing_start, $existing_end) = explode('~', $existing_time_range);
            $existing_start = intval($existing_start);
            $existing_end = intval($existing_end);

            // 檢查是否同一天
            if ($new_weekday == $existing_weekday) {
                // 檢查時間是否重疊
                if (($new_start < $existing_end) && ($new_end > $existing_start)) {
                    return true; // 有衝堂
                }
            }
        }
    }
    return false; // 無衝堂
}

// 查詢學生已選課程的上課時段
function getStudentSchedule($student_id, $conn) {
    $sql_schedule = "SELECT course.course_id, timeslot.timeslot_id
                     FROM enrollment 
                     JOIN course ON enrollment.course_id = course.course_id
                     JOIN course_timeslot ON course.course_id = course_timeslot.course_id
                     JOIN timeslot ON course_timeslot.timeslot_id = timeslot.timeslot_id
                     WHERE enrollment.student_id = ?";

    $stmt = $conn->prepare($sql_schedule);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedule = [];
    while ($row = $result->fetch_assoc()) {
        $schedule[] = $row;
    }

    return $schedule;
}

// 查詢學生已選課程
$student_schedule = getStudentSchedule($_SESSION['student_id'], $conn);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>選課系統</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>選課系統</h2>

    <!-- 搜尋表單 -->
    <form method="GET" action="">
        <label for="search_course">課程代碼：</label>
        <input type="text" id="search_course" name="search_course" value="<?= isset($_GET['search_course']) ? $_GET['search_course'] : '' ?>">

        <label for="search_dept">開課科系：</label>
        <select id="search_dept" name="search_dept">
            <option value="">全部</option>
            <?php
            // 查詢 department 表內的 dept_name
            $dept_sql = "SELECT DISTINCT dept_name FROM department";
            $dept_result = $conn->query($dept_sql);
            while ($dept_row = $dept_result->fetch_assoc()) {
                $selected = (isset($_GET['search_dept']) && $_GET['search_dept'] == $dept_row['dept_name']) ? 'selected' : '';
                echo "<option value='{$dept_row['dept_name']}' $selected>{$dept_row['dept_name']}</option>";
            }
            ?>
        </select>

        <button type="submit">搜尋</button>
    </form>

    <table>
        <tr>
            <th>課程代碼</th>
            <th>課程名稱</th>
            <th>學分</th>
            <th>課程類別</th>
            <th>開課科系</th>
            <th>教師</th>
            <th>教室</th>
            <th>上課時段</th>
            <th>選課人數</th> <!-- 新增 -->
            <th>操作</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { 
            // 查詢此課程的上課時段
            $timeslots = getCourseTimeslot($row['course_id'], $conn);
            
            // 檢查此課程是否有衝堂
            $conflict = checkConflict($timeslots, $student_schedule);

            // 檢查課程是否已經選擇
            $course_enrolled = false;
            foreach ($student_schedule as $existing) {
                if ($existing['course_id'] == $row['course_id']) {
                    $course_enrolled = true;
                    break;
                }
            }

            // 檢查課程是否額滿
            $is_full = $row['enrolled_students'] >= $row['capacity'];
        ?>
        <tr>
            <td><?= $row['course_id'] ?></td>
            <td><?= $row['course_name'] ?></td>
            <td><?= $row['credits'] ?></td>
            <td><?= $row['course_type'] ?></td>
            <td><?= $row['dept_name'] ?></td>
            <td><?= $row['teacher_name'] ?></td>
            <td><?= $row['classroom_id'] ?></td>
            <td>
                <?php 
                if (!empty($timeslots)) {
                    foreach ($timeslots as $timeslot) {
                        echo $timeslot['timeslot_id'] . "<br>"; // 直接顯示 timeslot_id
                    }
                } else {
                    echo "無時段";
                }
                ?>
            </td>
            <td><?= $row['enrolled_students'] . " / " . $row['capacity'] ?></td> <!-- 顯示選課人數與教室容量 -->
            <td>
                <?php if ($course_enrolled) { ?>
                    <span style="color: black;">已選課程</span>
                <?php } else if ($conflict) { ?>
                    <span style="color: red;">衝堂，無法加選</span>
                <?php } else if ($is_full) { ?>
                    <span style="color: gray;">課程已滿</span>
                <?php } else { ?>
                    <form method="POST" action="enroll.php">
                        <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">
                        <button type="submit" class="btn btn-add">加選</button>
                    </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="home.php" class="back-btn">返回</a>
</div>

</body>
</html>
