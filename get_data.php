<?php

require_once "config.php";
if (isset($_GET['option'])) {
  $selectedOption = $_GET['option'];
  

  $sql = "SELECT doctor.docid, doctor.docemail, doctor.docname, doctor.docpassword, doctor.doctel, specialties.sname
  FROM doctor
  JOIN specialties ON doctor.specialties = specialties.id WHERE docid = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $selectedOption);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    
    $data = array(
      'docid' => $row['docid'],
      'docname' => $row['docname'],
      'docemail' => $row['docemail'],
      'doctel' => $row['doctel'],
      'specialties' => $row['sname']
    );
    

    echo json_encode($data);
  } else {

    echo json_encode(array());
  }
}
?>