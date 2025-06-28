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

if (isset($_GET['payload'])) {
  $rfid = $_GET['payload'];

  // Check if the user exists in the 'info' table
  $sql = "SELECT * FROM info WHERE rfid = '$rfid'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Check if the user is already checked in
    $user_id = $user['id'];
    $sql = "SELECT * FROM attendance WHERE user_id = '$user_id' AND check_out_time IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Check out the user
      $sql = "UPDATE attendance SET check_out_time = NOW() WHERE user_id = '$user_id' AND check_out_time IS NULL";
      if ($conn->query($sql) === TRUE) {
        echo "Checked out successfully";
      } else {
        echo "Error checking out: " . $conn->error;
      }
    } else {
      // Check in the user
      $sql = "INSERT INTO attendance (user_id, check_in_time) VALUES ('$user_id', NOW())";
      if ($conn->query($sql) === TRUE) {
        echo "Checked in successfully";
      } else {
        echo "Error checking in: " . $conn->error;
      }
    }
  } else {
    echo "User not in database";
  }
}

$conn->close();
?>
