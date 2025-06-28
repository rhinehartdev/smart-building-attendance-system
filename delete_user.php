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

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $sql = "DELETE FROM info WHERE id=$id";

  if ($conn->query($sql) === TRUE) {
    echo "User deleted successfully";
  } else {
    echo "Error deleting user: " . $conn->error;
  }
}

$conn->close();
?>
