<?php
// add_sensor.php - Script to add a new sensor to the database

// Database Connection
$servername = "mysql-pureair.alwaysdata.net";
$username = "pureair_";
$password = ".@3qQ!76ztajuQ#";
$dbname = "pureair_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get data from POST request
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required fields are present
    if (isset($data['city']) && isset($data['aqiValue'])) {
        $city = $conn->real_escape_string($data['city']);
        $aqiValue = $conn->real_escape_string($data['aqiValue']);

        // Generate random coordinates near Colombo for demonstration
        // In a real application, you would get actual coordinates
        $baseLat = 6.914;
        $baseLng = 79.8778;
        $lat = $baseLat + (rand(-100, 100) / 1000); // Random offset within ~1km
        $lng = $baseLng + (rand(-100, 100) / 1000);

        // Default status is Active
        $status = "Active";

        // Insert new sensor into database
        $sql = "INSERT INTO sensors (city, lat, lng, status, aqi_value)
                VALUES ('$city', $lat, $lng, '$status', $aqiValue)";

        if ($conn->query($sql) === TRUE) {
            $newId = $conn->insert_id;
            echo json_encode([
                "success" => true,
                "message" => "Sensor added successfully",
                "sensor" => [
                    "id" => $newId,
                    "city" => $city,
                    "lat" => $lat,
                    "lng" => $lng,
                    "status" => $status,
                    "aqi_value" => $aqiValue
                ]
            ]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "City and AQI value are required"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>
