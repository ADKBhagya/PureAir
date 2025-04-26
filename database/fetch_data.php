<?php
header('Content-Type: application/json');

$host = "mysql-pureair.alwaysdata.net";
$db = "pureair_db";
$user = "pureair_";
$pass = ".@3qQ!76ztajuQ#";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Make sure your table and columns are correct
$sql = "SELECT city, condition, aqi FROM status_data";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

echo json_encode($data);
