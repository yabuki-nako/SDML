<?php
session_start();
require_once "config.php";
$appointment_ID = $_POST['appointmentID'];

$p_ID = $_SESSION['id'];
$sql = "SELECT pdf_files.*, patient_detail.pId
FROM pdf_files
INNER JOIN appointments ON pdf_files.appointment_ID = appointments.appointment_ID
INNER JOIN patient_detail ON appointments.pId = patient_detail.pId WHERE patient_detail.pId = '". $p_ID. "' AND appointments.appointment_ID = '". $appointment_ID."'";



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
    echo "No PDF files uploaded yet.";
}

$mysqli->close();
?>