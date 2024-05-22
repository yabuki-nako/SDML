<?php
session_start();
require_once "config.php";
$appointment_ID = $_POST['appointmentID'];

$p_ID = $_SESSION['id'];
$sql = "SELECT * from patient_ehr  WHERE pId = '". $p_ID. "' AND appointment_ID = '". $appointment_ID."'";



$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Record ID</th>";
    // echo "<th>Patient ID</th>";
    echo "<th>Height (cm)</th>";
    echo "<th>Weight (kg)</th>";
    echo "<th>BMI</th>";
    echo "<th>Blood Type</th>";
    echo "<th>Blood Pressure</th>";
    echo "<th>Respiratory Rate (per minute)</th>";
    echo "<th>Temperature (celsius)</th>";
    echo "<th>Drug Name</th>";
    echo "<th>Prescribed Date</th>";
    echo "<th>Note</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". $row['ehr_id']. "</td>";
        echo "<td>". $row['height_cm']. "</td>";
        echo "<td>". $row['weight_kg']. "</td>";
        echo "<td>". $row['BMI']. "</td>";
        echo "<td>". $row['blood_type']. "</td>";
        echo "<td>". $row['blood_pressure']. "</td>";
        echo "<td>". $row['respiratory_rate_per_min']. "</td>";
        echo "<td>". $row['temperature_celsius']. "</td>";
        echo "<td>". $row['drug_name']. "</td>";
        $formatted_date = date('Y-m-d', strtotime($row['prescribed_date']));
        echo "<td>". $formatted_date. "</td>";
        // echo "<td>". $row['prescribed_date']. "</td>";
        echo "<td>". $row['note']. "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "No records to show yet.";
}

$mysqli->close();
?>