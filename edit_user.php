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

if (isset($_GET['id'])) {
  $user_id = $_GET['id'];
  $sql = "SELECT * FROM info WHERE id='$user_id'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
  } else {
    // Redirect to manage_users.php if no user found
    header("Location: manage_users.php");
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $address = $_POST['address'];
    $room_loc = $_POST['room_loc'];
    $sex = $_POST['sex'];
    $plate_number = $_POST['plate_number'];
    $vehicle = $_POST['vehicle'];
    $rfid = $_POST['rfid'];

    $sql = "UPDATE info SET name='$name', email='$email', contact_no='$contact_no', address='$address', room_loc='$room_loc', sex='$sex', plate_number='$plate_number', vehicle='$vehicle', rfid='$rfid' WHERE id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
      echo "Record updated successfully";
    } else {
      echo "Error updating record: " . $conn->error;
    }
  } elseif (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM info WHERE id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
      // Redirect to manage_users.php after deletion
      header("Location: manage_users.php");
      exit;
    } else {
      echo "Error deleting record: " . $conn->error;
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="user_management.css">
</head>
<body>
  <div class="header">
    <h1 class="page-title">Edit User</h1>
    <button class="action-btn" onclick="window.location.href='manage_users.php'">Back to Manage Users</button>
  </div>
  <div class="content">
    <form method="post" action="edit_user.php">
      <input type="hidden" name="user_id" value="<?php echo isset($user['id']) ? $user['id'] : ''; ?>">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" required>
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
      <label for="contact_no">Contact Number:</label>
      <input type="text" id="contact_no" name="contact_no" value="<?php echo isset($user['contact_no']) ? $user['contact_no'] : ''; ?>" required>
      <label for="address">Address:</label>
      <input type="text" id="address" name="address" value="<?php echo isset($user['address']) ? $user['address'] : ''; ?>" required>
      <label for="room_loc">Room Location:</label>
      <input type="text" id="room_loc" name="room_loc" value="<?php echo isset($user['room_loc']) ? $user['room_loc'] : ''; ?>" required>
      <label for="sex">Sex:</label>
      <input type="text" id="sex" name="sex" value="<?php echo isset($user['sex']) ? $user['sex'] : ''; ?>" required>
      <label for="plate_number">Plate Number:</label>
      <input type="text" id="plate_number" name="plate_number" value="<?php echo isset($user['plate_number']) ? $user['plate_number'] : ''; ?>" required>
      <label for="vehicle">Vehicle:</label>
      <input type="text" id="vehicle" name="vehicle" value="<?php echo isset($user['vehicle']) ? $user['vehicle'] : ''; ?>" required>
      <label for="rfid">RFID:</label>
      <input type="text" id="rfid" name="rfid" value="<?php echo isset($user['rfid']) ? $user['rfid'] : ''; ?>" required>
      <div>
        <input type="submit" name="update_user" value="Update" class="action-btn">
        <input type="submit" name="delete_user" value="Delete" class="action-btn">
      </div>
    </form>
  </div>
</body>
</html>
