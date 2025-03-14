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

// 取得所有系所、教室、時間段的資料並按時間排序
$departments = $conn->query("SELECT dept_id, dept_name FROM department");
$classrooms = $conn->query("SELECT classroom_id, building FROM classroom");
$timeslots = $conn->query("SELECT timeslot_id, weekday, start_time, end_time FROM timeslot ORDER BY FIELD(weekday, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), start_time");

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $credits = $_POST['credits'];
    $course_type = $_POST['course_type'];
    $dept_id = $_POST['dept_id'];
    $classroom_id = $_POST['classroom_id'];
    $teacher_id = $_POST['teacher_id'];
    $max_students = $_POST['max_students'];
    $is_open = isset($_POST['is_open']) ? 1 : 0;
    $timeslot_ids = $_POST['timeslot_ids']; // 取得選擇的時間段 (陣列)

    // 1. 先檢查 course_id 是否已經存在
    $sql_check = "SELECT * FROM course WHERE course_id = '$course_id'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        echo "錯誤：課程代碼已經存在，請使用不同的課程代碼。";
    } else {
        // 檢查是否有時間衝突
        $conflict = false;
        foreach ($timeslot_ids as $timeslot_id) {
            $sql_check_conflict = "
                SELECT c.course_id 
                FROM course c
                JOIN course_timeslot ct ON c.course_id = ct.course_id
                WHERE c.classroom_id = '$classroom_id' 
                AND timeslot_id = '$timeslot_id'
            ";
            $result_check_conflict = $conn->query($sql_check_conflict);

            if ($result_check_conflict->num_rows > 0) {
                $conflict = true;
                break;
            }
        }

        if ($conflict) {
            echo "錯誤：選擇的時間段已被其他課程佔用，請選擇其他教室或時間段。";
        } else {
            // 插入課程
            $sql_insert_course = "
                INSERT INTO course (course_id, course_name, credits, course_type, dept_id, classroom_id, teacher_id, max_students, is_open)
                VALUES ('$course_id', '$course_name', '$credits', '$course_type', '$dept_id', '$classroom_id', '$teacher_id', '$max_students', '$is_open')";

            if ($conn->query($sql_insert_course) === TRUE) {
                // 插入時間段
                foreach ($timeslot_ids as $timeslot_id) {
                    $sql_insert_timeslot = "INSERT INTO course_timeslot (course_id, timeslot_id) VALUES ('$course_id', '$timeslot_id')";
                    if ($conn->query($sql_insert_timeslot) !== TRUE) {
                        echo "錯誤: 無法將課程與時間段關聯 - " . $conn->error;
                        exit;
                    }
                }
                echo "新課程已成功新增！";
            } else {
                echo "錯誤: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <link rel="stylesheet" href="style.css?v=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增課程</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('dept_id').addEventListener('change', function() {
                var dept_id = this.value;
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_teachers.php?dept_id=' + dept_id, true);
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        var teachers = JSON.parse(xhr.responseText);
                        var teacherSelect = document.getElementById('teacher_id');
                        teacherSelect.innerHTML = '';
                        if (teachers.length > 0) {
                            teachers.forEach(function(teacher) {
                                var option = document.createElement('option');
                                option.value = teacher.teacher_id;
                                option.textContent = teacher.teacher_name;
                                teacherSelect.appendChild(option);
                            });
                        } else {
                            var option = document.createElement('option');
                            option.value = '';
                            option.textContent = '沒有可選擇的教師';
                            teacherSelect.appendChild(option);
                        }
                    }
                };
                xhr.send();
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="header">新增課程</h1>

        <?php if (isset($error_message)) { ?>
            <div class="error"><?= $error_message; ?></div>
        <?php } elseif (isset($success_message)) { ?>
            <div class="success"><?= $success_message; ?></div>
        <?php } ?>

        <form action="add_course.php" method="POST">
            <div class="form-group">
                <label for="course_id">課程代碼:</label>
                <input type="text" id="course_id" name="course_id" required>
            </div>

            <div class="form-group">
                <label for="course_name">課程名稱:</label>
                <input type="text" id="course_name" name="course_name" required>
            </div>

            <div class="form-group">
                <label for="credits">學分數:</label>
                <input type="number" id="credits" name="credits" required>
            </div>

            <div class="form-group">
                <label for="course_type">課程類型:</label>
                <select id="course_type" name="course_type">
                    <option value="必修">必修</option>
                    <option value="選修">選修</option>
                </select>
            </div>

            <div class="form-group">
                <label for="dept_id">開課系所:</label>
                <select id="dept_id" name="dept_id" required>
                    <option value="">請選擇開課系所</option>
                    <?php while ($row = $departments->fetch_assoc()) {
                        echo "<option value='" . $row['dept_id'] . "'>" . $row['dept_name'] . "</option>";
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="teacher_id">授課教師:</label>
                <select id="teacher_id" name="teacher_id" required>
                    <option value="">請先選擇開課系所</option>
                </select>
            </div>

            <div class="form-group">
                <label for="classroom_id">教室:</label>
                <select id="classroom_id" name="classroom_id" required>
                    <?php while ($row = $classrooms->fetch_assoc()) {
                        echo "<option value='" . $row['classroom_id'] . "'>" . $row['building'] . "棟" . $row['classroom_id'] . "</option>";
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="max_students">最大選課人數:</label>
                <input type="number" id="max_students" name="max_students" required>
            </div>

            <div class="form-group">
                <label for="is_open">是否開放選課:</label>
                <input type="checkbox" id="is_open" name="is_open" checked>
            </div>

            <div class="form-group">
                <label for="timeslot_ids">選擇時間段:</label><br>
                <?php while ($row = $timeslots->fetch_assoc()) {
                    echo "<input type='checkbox' name='timeslot_ids[]' value='" . $row['timeslot_id'] . "'> " . $row['weekday'] . " " . $row['start_time'] . " - " . $row['end_time'] . "<br>";
                } ?>
            </div>

            <input type="submit" class="add" value="新增課程">
        </form>

        <a href="admin_dashboard.php" class="back-btn">返回</a>
    </div>
</body>
</html>
