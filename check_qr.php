<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "Thesis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$qrData = $_POST['qrData'];

// Prepare and bind
$stmt = $conn->prepare("SELECT rfid FROM info WHERE rfid = ?");
$stmt->bind_param("s", $qrData);
$stmt->execute();
$stmt->store_result();

$response = array();
$response['qr_valid'] = false;

if ($stmt->num_rows > 0) {
    $response['qr_valid'] = true;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
