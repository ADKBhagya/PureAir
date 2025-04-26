<?php
// Database Connection
$servername = "mysql-pureair.alwaysdata.net";
$username = "pureair_";
$password = ".@3qQ!76ztajuQ#";
$dbname = "pureair_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for the action parameter to determine which operation to perform
$action = isset($_GET['action']) ? $_GET['action'] : 'fetch'; // Default action is fetch

if ($action === 'fetch') {
    // Fetch Sensors Data
    $sql = "SELECT * FROM sensors";
    $result = $conn->query($sql);
    $sensors = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sensors[] = $row;
        }
    }

    echo json_encode($sensors);

} elseif ($action === 'toggle_status' && isset($_GET['id'])) {
    // Toggle Sensor Status
    $id = $_GET['id'];
    $sql = "UPDATE sensors SET status = CASE WHEN status = 'Active' THEN 'Inactive' ELSE 'Active' END WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    // Delete Sensor
    $id = $_GET['id'];
    $sql = "DELETE FROM sensors WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid action.";
}

$conn->close();
?>
