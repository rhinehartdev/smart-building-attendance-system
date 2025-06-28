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

require_once('tcpdf/tcpdf.php');

// Handle QR data from ESP32
if (isset($_POST['qrData'])) {
  $qrData = strtolower(trim($_POST['qrData']));  // Convert to lowercase and trim for consistency

  // Verify QR data against RFID column in the info table
  $sql = "SELECT id, name FROM info WHERE rfid='$qrData'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $name = $row['name'];

    // Check if the user is already checked in
    $sql_check = "SELECT * FROM attendance WHERE user_id='$userId' AND check_out_time IS NULL";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
      // User is checking out
      $sql_update = "UPDATE attendance SET check_out_time=NOW() WHERE user_id='$userId' AND check_out_time IS NULL";
      $conn->query($sql_update);
      echo "Checked out: $name";
    } else {
      // User is checking in
      $sql_insert = "INSERT INTO attendance (user_id, name, check_in_time, check_out_time) VALUES ('$userId', '$name', NOW(), NULL)";
      $conn->query($sql_insert);
      echo "Checked in: $name";
    }
  } else {
    echo "RFID not registered.";
  }

  exit;
}

// Generate PDF
if (isset($_POST['download_pdf'])) {
  $sql = "SELECT attendance.user_id, info.name, attendance.check_in_time, attendance.check_out_time
          FROM attendance
          INNER JOIN info ON attendance.user_id = info.id
          ORDER BY attendance.check_in_time DESC";
  $result = $conn->query($sql);

  // Create new PDF document
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Your Name');
  $pdf->SetTitle('Attendance Logs');
  $pdf->SetHeaderData('', 0, 'Attendance Logs', '');
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetFont('dejavusans', '', 12);
  $pdf->AddPage();

  $html = '<h2>Attendance Logs</h2><table border="1" cellpadding="4"><thead><tr><th>User ID</th><th>Name</th><th>Check-In Time</th><th>Check-Out Time</th></tr></thead><tbody>';

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $html .= '<tr>';
      $html .= '<td>' . $row["user_id"] . '</td>';
      $html .= '<td>' . $row["name"] . '</td>';
      $html .= '<td>' . $row["check_in_time"] . '</td>';
      $html .= '<td>' . $row["check_out_time"] . '</td>';
      $html .= '</tr>';
    }
  } else {
    $html .= '<tr><td colspan="4">No records found</td></tr>';
  }

  $html .= '</tbody></table>';
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->Output('attendance_logs.pdf', 'D');
  exit;
}

$sql = "SELECT attendance.user_id, info.name, attendance.check_in_time, attendance.check_out_time
        FROM attendance
        INNER JOIN info ON attendance.user_id = info.id
        ORDER BY attendance.check_in_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <title>Attendance Records</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid black;
    }
    th, td {
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1 class="page-title">Attendance Records</h1>
  </div>
  <div class="content">
    <form method="post" action="attendance.php">
      <button type="submit" name="download_pdf">Download Logs as PDF</button>
    </form>
    <table>
      <thead>
        <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Check-In Time</th>
          <th>Check-Out Time</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["check_in_time"] . "</td>";
            echo "<td>" . $row["check_out_time"] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='4'>No records found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
