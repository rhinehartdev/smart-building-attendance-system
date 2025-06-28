<?php
date_default_timezone_set('Asia/Manila'); // or your local timezone


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thesis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);



// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If this is an AJAX request, return the table rows only
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $sql = "SELECT rfid, plate_number, room_loc, access_time FROM access_logs ORDER BY access_time DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['rfid']) . "</td>";
            echo "<td>" . htmlspecialchars($row['plate_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['room_loc']) . "</td>";
            echo "<td>" . htmlspecialchars($row['access_time']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No access logs found</td></tr>";
    }
    exit; // Stop further execution for AJAX requests
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Logs</title>
    <link rel="stylesheet" href="access_logs.css"> <!-- Link to the CSS file -->
    <script>
        // Function to fetch updated table data
        function fetchTableData() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "access_logs.php?ajax=1", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.querySelector("tbody").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Auto-update the table every 5 seconds
        setInterval(fetchTableData, 5000);
    </script>
</head>
<body>
    <header>
        <h1>Access Logs</h1>
        <button onclick="window.location.href='dashboard.html';" class="redirect-btn">Go to Dashboard</button>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>RFID</th>
                    <th>Plate Number</th>
                    <th>Room Location</th>
                    <th>Access Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT rfid, plate_number, room_loc, access_time FROM access_logs ORDER BY access_time DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['rfid']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['plate_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['room_loc']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['access_time']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No access logs found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <h1>Access Logs</h1>
    </footer>
</body>
</html>
<?php
$conn->close();
?>