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

$sql = "SELECT * FROM scanned_rfid ORDER BY scan_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Scanned RFID Records</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <script>
    function fetchRFIDData() {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'scanned_rfid_data.php', true);
      xhr.onload = function() {
        if (this.status === 200) {
          const data = JSON.parse(this.responseText);
          const tableBody = document.getElementById('rfid-table-body');
          tableBody.innerHTML = '';
          data.forEach(function(row) {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
              <td>${row.id}</td>
              <td>${row.rfid}</td>
              <td>${row.scan_time}</td>
            `;
            tableBody.appendChild(newRow);
          });
        }
      };
      xhr.send();
    }

    window.onload = function() {
      fetchRFIDData();
      setInterval(fetchRFIDData, 1000); // Polling interval of 5 seconds
    };
  </script>
</head>
<body>
  <div class="header">
    <h1 class="page-title">Scanned RFID Records</h1>
  </div>
  <div class="content">
    <table id="rfid-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>RFID</th>
          <th>Scan Time</th>
        </tr>
      </thead>
      <tbody id="rfid-table-body">
        <?php
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["rfid"] . "</td>";
            echo "<td>" . $row["scan_time"] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='3'>No records found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>

<?php
$conn->close();
?>
