<?php
require_once "config.php";
$appointment_ID = $_POST['appointmentID'];

$sql = "SELECT * FROM pdf_files WHERE appointment_ID = '". $appointment_ID. "'";


$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table'>";
    echo "<thead><tr><th>File Name</th><th>Upload Date</th><th>Action</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". $row['file_name']. "</td>";
        $formatted_date = date('Y-m-d', strtotime($row['upload_date']));
        echo "<td>". $formatted_date. "</td>";
        echo "<td><a href='uploads/". $row['file_name']. "' target='_blank'>View</a></td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "No PDF, PNG, JPG files uploaded yet.";
}

$mysqli->close();
?>