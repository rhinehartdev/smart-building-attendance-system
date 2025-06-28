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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $contact_no = $_POST['contact_no'];
  $address = $_POST['address'];
  $room_loc = $_POST['room_loc'];
  $sex = $_POST['sex'];
  $plate_number = $_POST['plate_number'];
  $vehicle = $_POST['vehicle'];
  $rfid = $_POST['rfid'];

  $sql = "INSERT INTO info (name, email, contact_no, address, room_loc, sex, plate_number, vehicle, rfid) VALUES ('$name', '$email', '$contact_no', '$address', '$room_loc', '$sex', '$plate_number', '$vehicle', '$rfid')";

  if ($conn->query($sql) === TRUE) {
    echo "Information saved to database!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>
