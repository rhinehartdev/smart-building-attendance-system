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

if (isset($_GET['rfid'])) {
  $rfid = $_GET['rfid'];

  // Insert the RFID into the scanned_rfid table
  $sql = "INSERT INTO scanned_rfid (rfid) VALUES ('$rfid')";
  if ($conn->query($sql) === TRUE) {
    echo "RFID saved successfully";
  } else {
    echo "Error saving RFID: " . $conn->error;
  }
}

$conn->close();
?>
