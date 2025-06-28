<?php
$servername = "localhost"; // Change to your database host
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "thesis"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the RFID from the request
$rfid = $_GET['rfid'];

// Query the database for the room_loc and plate_number
$sql = "SELECT room_loc, plate_number FROM info WHERE rfid = '$rfid'";
$result = $conn->query($sql);

// If the RFID is found, return the room_loc and plate_number
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $room_loc = $row['room_loc'];
    $plate_number = $row['plate_number'];

    // Log the access time in the access_logs table
    $timestamp = date("Y-m-d H:i:s");
    $log_sql = "INSERT INTO access_logs (rfid, plate_number, room_loc, access_time) VALUES ('$rfid', '$plate_number', '$room_loc', '$timestamp')";
    $conn->query($log_sql);

    // Return the room location and plate number
    echo $room_loc . "," . $plate_number;
  }
} else {
  echo "Not found"; // Return a message if RFID is not found
}

$conn->close();
?>
