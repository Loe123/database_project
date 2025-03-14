<?php
$host = "localhost";
$user = "root"; // XAMPP 預設帳號
$password = "123456"; // XAMPP 預設密碼
$dbname = "test"; // 你的資料庫名稱

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}
?>
