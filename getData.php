<?php
// Include config file
require_once "config.php";

// Query to get data
$sql = "SELECT specialties.sname AS department, COUNT(*) AS bookings_count
        FROM appointments
        INNER JOIN doctor ON appointments.docid = doctor.docid
        INNER JOIN specialties ON doctor.specialties = specialties.id
        GROUP BY specialties.sname";

$result = $mysqli->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Convert data to JSON format and output
echo json_encode($data);

// Close connection
$mysqli->close();
?>
