<?php
require_once "config.php";

if(isset($_POST['year'])) {
    $selectedYear = $_POST['year'];
    $sql = "SELECT MONTH(appDate) AS month_number, COUNT(*) AS total_appointments
            FROM appointments
            WHERE YEAR(appDate) = $selectedYear
            GROUP BY MONTH(appDate)";
    
    $result = $mysqli->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

$mysqli->close();
?>