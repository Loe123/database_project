<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

// 關閉錯誤顯示，避免干擾 JSON
error_reporting(0);
ini_set('display_errors', 0);

// 連接資料庫
$servername = "localhost";
$username = "root";
$password = "123456";  // 密碼設為 123456
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查資料庫連接是否成功
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// 查詢資料
$sql = "SELECT * FROM student";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// 返回 JSON 格式資料
echo json_encode($data, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
