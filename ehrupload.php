<?php
session_start();
require_once "config.php";



if ($_SERVER["REQUEST_METHOD"] == "POST") { 

// Assuming you have already sanitized your inputs
$appointment_ID = $_POST['app_idModal'];
$pID = $_POST['patient_idModal'];
$height_cm = $_POST['pHeight_Modal'];
$weight_kg = $_POST['pWeight_Modal'];
$BMI = $_POST['bmi_Modal'];
$blood_type = $_POST['pBloodType'];
$blood_pressure = $_POST['bp_Modal'];
$respiratory_rate_per_min = $_POST['respiratoryRate_Modal'];
$temperature_celsius = $_POST['temp_Modal'];
$drug_name = $_POST['drug_Modal'];
$prescribed_date = date("Y-m-d");
$note = $_POST['note_Modal'];

// Assuming $mysqli is your database connection

// Get pId from patient_idModal
$pId_query = "SELECT pID FROM patient_detail WHERE pID = '$pID'";
$result_pId = $mysqli->query($pId_query);
$pId = null;
if ($result_pId->num_rows > 0) {
    $row = $result_pId->fetch_assoc();
    $pId = $row["pID"];
} else {
    // Handle the case where the patient ID doesn't exist in the database
    // You might want to insert the patient details first
}

// Prepare and execute the SQL statement
$sql = "INSERT INTO patient_ehr (pId, appointment_ID, height_cm, weight_kg, BMI, blood_type, blood_pressure, respiratory_rate_per_min, temperature_celsius, drug_name, prescribed_date, note)
VALUES ('$pId', '$appointment_ID', '$height_cm', '$weight_kg', '$BMI', '$blood_type', '$blood_pressure', '$respiratory_rate_per_min', '$temperature_celsius', '$drug_name', '$prescribed_date', '$note')";

if ($mysqli->query($sql) === TRUE) {
    echo "<script type='text/javascript'>alert('New record created successfully');</script>";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}
} 

?>