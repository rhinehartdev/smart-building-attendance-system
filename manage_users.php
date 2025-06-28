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

$sql = "SELECT * FROM info";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="user_management.css">
</head>
<body>
  <div class="header">
    <h1 class="page-title">Manage Users</h1>
    <button class="action-btn" onclick="window.location.href='dashboard.html'">Back to Dashboard</button>
  </div>
  <div class="content">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Contact Number</th>
          <th>Address</th>
          <th>Room Location</th>
          <th>Sex</th>
          <th>Plate Number</th>
          <th>Vehicle</th>
          <th>RFID</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["contact_no"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["room_loc"] . "</td>";
            echo "<td>" . $row["sex"] . "</td>";
            echo "<td>" . $row["plate_number"] . "</td>";
            echo "<td>" . $row["vehicle"] . "</td>";
            echo "<td>" . $row["rfid"] . "</td>";
            echo "<td><a class='action-btn' href='edit_user.php?id=" . $row["id"] . "'>Edit</a></td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='10'>No users found</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
