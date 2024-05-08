<?php
// Assuming you have established the database connection
require_once "config.php";
if (isset($_GET['option'])) {
  $selectedOption = $_GET['option'];
  
  // Perform your database query here based on the selected option
  $sql = "SELECT doctor.docid, doctor.docemail, doctor.docname, doctor.docpassword, doctor.doctel, specialties.sname
  FROM doctor
  JOIN specialties ON doctor.specialties = specialties.id WHERE docid = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $selectedOption);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    
    // Create an associative array with the data you want to send back
    $data = array(
      'docid' => $row['docid'],
      'docname' => $row['docname'],
      'docemail' => $row['docemail'],
      'doctel' => $row['doctel'],
      'specialties' => $row['sname']
    );
    
    // Return the data as a JSON response
    echo json_encode($data);
  } else {
    // Handle the case when no matching row is found
    // Return an empty JSON response or an error message
    echo json_encode(array());
  }
}
?>