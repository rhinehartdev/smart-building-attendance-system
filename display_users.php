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
  <title>Registered Users</title>
  <style>
    .qr-container {
      display: none;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <h1>Registered Users</h1>
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
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<tr onclick='showQRCode(`" . $row["rfid"] . "`, `" . $row["id"] . "`)'>";
          echo "<td>" . $row["name"] . "</td>";
          echo "<td>" . $row["email"] . "</td>";
          echo "<td>" . $row["contact_no"] . "</td>";
          echo "<td>" . $row["address"] . "</td>";
          echo "<td>" . $row["room_loc"] . "</td>";
          echo "<td>" . $row["sex"] . "</td>";
          echo "<td>" . $row["plate_number"] . "</td>";
          echo "<td>" . $row["vehicle"] . "</td>";
          echo "<td>" . $row["rfid"] . "</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='9'>No users found</td></tr>";
      }
      $conn->close();
      ?>
    </tbody>
  </table>
  <div class="qr-container" id="qr-container">
    <h2 id="qr-text"></h2>
    <img id="qrcode" alt="QR Code">
  </div>
  <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
  <script src="qrcode_script.js"></script>
</body>
</html>
