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

// Validate input
if (!isset($_GET['rfid'])) {
    die(json_encode(["error" => "RFID not provided"]));
}

$rfid = $_GET['rfid'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT rfid FROM info WHERE rfid = ?");
$stmt->bind_param("s", $rfid);
$stmt->execute();
$stmt->store_result();

$response = ["access" => "denied"]; // Default response

if ($stmt->num_rows > 0) {
    $response["access"] = "granted";
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>