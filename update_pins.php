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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $room1 = $_POST['room1'];
  $room2 = $_POST['room2'];
  $master = $_POST['master'];

  $stmt = $conn->prepare("UPDATE room_pins SET pin = ? WHERE room = ?");
  $stmt->bind_param("ss", $pin, $room);

  $pin = $room1;
  $room = 'room1';
  $stmt->execute();

  $pin = $room2;
  $room = 'room2';
  $stmt->execute();

  $pin = $master;
  $room = 'master';
  $stmt->execute();

  $stmt->close();
}

$conn->close();

header("Location: manage_pins.html");
exit;
?>
