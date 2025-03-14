<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dept_id = $_GET['dept_id'];

$sql = "SELECT teacher_id, teacher_name FROM teacher WHERE dept_id = '$dept_id' ORDER BY teacher_id";
$result = $conn->query($sql);

$teachers = [];

while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

// 傳回 JSON 格式的教師資料
echo json_encode($teachers);

$conn->close();
?>
