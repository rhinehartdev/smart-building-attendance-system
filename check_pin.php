<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "thesis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$room = $_GET['room'];
$entered_pin = $_GET['pin'];

// Verify the entered PIN
$sql = "SELECT pin FROM room_pins WHERE room = ? OR room = 'master'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $room);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($stored_pin);
$stmt->fetch();

$response = array();
$response['pin_valid'] = false;

if ($stmt->num_rows > 0) {
    if ($entered_pin == $stored_pin) {
        $response['pin_valid'] = true;
    } else {
        // Check master PIN
        $sql_master = "SELECT pin FROM room_pins WHERE room = 'master'";
        $stmt_master = $conn->prepare($sql_master);
        $stmt_master->execute();
        $stmt_master->store_result();
        $stmt_master->bind_result($master_pin);
        $stmt_master->fetch();

        if ($entered_pin == $master_pin) {
            $response['pin_valid'] = true;
        }
    }
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
