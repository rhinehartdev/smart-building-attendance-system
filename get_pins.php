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

$sql = "SELECT room, pin FROM room_pins";
$result = $conn->query($sql);

$pins = [];
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $pins[$row['room']] = $row['pin'];
  }
}

echo json_encode($pins);

$conn->close();
?>
