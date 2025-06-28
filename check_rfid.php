<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "thesis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Validate inputs
if (!isset($_GET['rfid']) || !isset($_GET['room_loc'])) {
    die(json_encode(["error" => "RFID or room_loc not provided"]));
}

$rfid = $_GET['rfid'];
$room_loc = $_GET['room_loc']; // The room location being accessed

// Prepare and execute query
$stmt = $conn->prepare("SELECT room_loc FROM info WHERE rfid = ?");
$stmt->bind_param("s", $rfid);
$stmt->execute();
$stmt->store_result();

$response = ["access" => "denied"]; // Default response

if ($stmt->num_rows > 0) {
    $stmt->bind_result($stored_room_loc);
    $stmt->fetch();

    // Check if the stored room_loc matches the provided room_loc
    if ($stored_room_loc === $room_loc) {
        $response["access"] = "granted";
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
