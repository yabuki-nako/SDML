<?php
// Initialize the session
session_start();
require_once "config.php";
// time and date
$today = date("F j, Y ");


$sql = "SELECT DISTINCT specialties.sname
FROM doctor
JOIN specialties ON doctor.specialties = specialties.id;";
$result = $mysqli->query($sql);


//

// Assuming $mysqli is your database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the file was uploaded without errors
  if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
      // Retrieve appointment ID from the form
      $appointmentID = $_POST["appointmentID"];

      // Get file details
      $filename = basename($_FILES["fileToUpload"]["name"]);
      $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get the file extension
      
      // Check if the file type is allowed
      if (in_array($filetype, ['pdf', 'png', 'jpg', 'jpeg'])) {
          // Prepare SQL statement
          $sql = "INSERT INTO pdf_files (appointment_ID, file_name, file_path) VALUES (?, ?, ?)";
          
          // Prepare the statement
          $stmt = $mysqli->prepare($sql);

          if ($stmt) {
              // Bind parameters
              $stmt->bind_param("iss", $appointmentID, $filename, $filepath);

              // Get file details
              $filedata = file_get_contents($_FILES["fileToUpload"]["tmp_name"]); // Read the file data
              $target_dir = "uploads/"; // Directory where the file will be saved
              $filepath = $target_dir . $filename; // File path
              
              // Move uploaded file to the target directory
              if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filepath)) {
                  // Execute the statement
                  if ($stmt->execute()) {
                    echo "<script type='text/javascript'>alert('File successfully uploaded');</script>";
                    echo '<META HTTP-EQUIV="Refresh" Content="0;">';
        
                  } else {
                      echo "<script type='text/javascript'>alert('Error inserting file data into database.');</script>";
                  }
              } else {
                  echo "<script type='text/javascript'>alert('Error moving uploaded file.');</script>";
              }
          } else {
              echo "<script type='text/javascript'>alert('Error preparing SQL statement.');</script>";
          }
      } else {
          echo "<script type='text/javascript'>alert('Only PDF, PNG, and JPG files are allowed.');</script>";
      }
  } else {
      echo "<script type='text/javascript'>alert('No file uploaded or an error occurred during upload.');</script>";
  }
} 
//insert data

// // Assuming you have already sanitized your inputs
// $appointment_ID = $_POST['app_idModal'];
// $pID = $_POST['patient_idModal'];
// $patient_name = $_POST['patient_nameModal'];
// $height_cm = $_POST['pHeight_Modal'];
// $weight_kg = $_POST['pWeight_Modal'];
// $BMI = $_POST['bmi_Modal'];
// $blood_type = $_POST['pBloodType'];
// $blood_pressure = $_POST['bp_Modal'];
// $respiratory_rate_per_min = $_POST['respiratoryRate_Modal'];
// $temperature_celsius = $_POST['temp_Modal'];
// $drug_name = $_POST['drug_Modal'];
// $prescribed_date = date("Y-m-d");
// $note = $_POST['w3review'];

// // Get pId from patient_nameModal
// $pId_query = "SELECT pID FROM patient_detail WHERE pID = '$pID'";
// $result = $mysqli->query($pId_query);
// if ($result->num_rows > 0) {
//     $row = $result->fetch_assoc();
//     $pId = $row["pId"];
// } else {
//     // Handle the case where the patient name doesn't exist in the database
//     // You might want to insert the patient details first
// }

// // Prepare and execute the SQL statement
// $sql = "INSERT INTO ehr (pId, appointment_ID, height_cm, weight_kg, BMI, blood_type, blood_pressure, respiratory_rate_per_min, temperature_celsius, drug_name, prescribed_date, note)
// VALUES ('$pId', '$appointment_ID', '$height_cm', '$weight_kg', '$BMI', '$blood_type', '$blood_pressure', '$respiratory_rate_per_min', '$temperature_celsius', '$drug_name', '$prescribed_date', '$note')";

// if ($mysqli->query($sql) === TRUE) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . $mysqli->error;
// }

?>
 <!DOCTYPE html>
<html lang="en">

<head>
<link rel = "icon" href = 
"assets/img/icon.png" 
        type = "image/x-icon">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Makiling Clinic</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

 
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/patient.css" rel="stylesheet">
  <!-- TIMER FUNCTION -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function(){
      updateTime(); 
      setInterval(updateTime, 1000); 
    });

    function updateTime() {
      $.ajax({
        url: 'get_time.php', 
        type: 'GET',
        success: function(data) {
          $('#time').text(data); 
        }
      });
    }
  </script>
</head>
<body>
</script>
<?php include 'doctorheader.php';?>
    <div class="row d-flex justify-content-center align-items-center h-50">

      <div class="col col-xl-10">

        <div class="mt-5 mb-3">
              <h4><b>Today's Date and Time:</b>
              <div class="d-flex"> <?php echo $today?>&nbsp;||&nbsp;<div id="time"></h4>
            </div>

         
        </div>
      </div>
    </div>
 
    <div class="row d-flex justify-content-center align-items-center h-50">

      <div class="col col-xl-10">

      <h2 class ="mb-3">Hi, Welcome Doctor <B><?php  echo htmlspecialchars($_SESSION["docname"]); ?></b>!</h2>
              
      </div>
    </div>

  <div class ="mb-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; background-color: white;">
        <div class="card-body p-4 p-lg-12">
        <h3>Your Appointments</h3>
          <hr class="app"></hr>
          <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Appointment ID</th>
      <th scope="col">Patient Name</th>
      <th scope="col">Date</th>
      <th scope="col">Time</th>
      <th scope="col">Appointment Status</th>
      <th scope="col"> Update Status</th>
      <th scope="col"> Medical history</th>
      <th scope="col"> Additional Documents</th>
      <th scope="col"> View Uploaded Documents</th>
    </tr>
  </thead>
    
  <?php
$docid = $_SESSION['docid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check which button is clicked
  if (isset($_POST["accept"])) {
    // Handle accept button click
    $appointmentID = $_POST["appointmentID"];
    $updateQuery = "UPDATE appointments SET App_status = 'Accepted' WHERE appointment_ID = $appointmentID";
    $mysqli->query($updateQuery);
  } elseif (isset($_POST["done"])) {
    // Handle done button click
    $appointmentID = $_POST["appointmentID"];
    $updateQuery = "UPDATE appointments SET App_status = 'Done' WHERE appointment_ID = $appointmentID";
    $mysqli->query($updateQuery);
  } elseif (isset($_POST["cancel"])) {
    // Handle cancel button click
    $appointmentID = $_POST["appointmentID"];
    $updateQuery = "UPDATE appointments SET App_status = 'Cancelled' WHERE appointment_ID = $appointmentID";
    $mysqli->query($updateQuery);
  }
}

// Loop through the result set and generate table rows
$sql1 = "SELECT appointments.appointment_ID, patient_detail.pname, patient_detail.pID,
          appointments.appDate, docsched.Time_schedule, appointments.App_status
          FROM appointments
          INNER JOIN doctor ON appointments.docid = doctor.docid
          INNER JOIN patient_detail ON appointments.pId = patient_detail.pId
          INNER JOIN docsched ON appointments.appTime = docsched.appTime
          WHERE doctor.docid = $docid";
$result1 = $mysqli->query($sql1);

if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['appointment_ID'] . "</td>";
    echo "<td>" . $row['pname'] . "</td>";
    echo "<td>" . $row['appDate'] . "</td>";
    echo "<td>" . $row['Time_schedule'] . "</td>";
    echo "<td>" . $row['App_status'] . "</td>";
    echo "<td></td>";
    echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal('" . $row['appointment_ID'] . "', '" . $row['pID'] . "','" . $row['pname'] . "', '".$row['App_status']."','".$row['appDate']."')\" data-toggle='modal' data-target='#exampleModal'>Electronic Health Records</button>";
    echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' enctype='multipart/form-data'>";
    echo "<input type='hidden' name='appointmentID' value='" . $row['appointment_ID'] . "'>"; // Add hidden input for appointment ID
    echo "<td>";
    echo "<input type='file' name='fileToUpload' id='fileToUpload'>";
    echo "<input type='submit' value='Upload PDF' name='submit' class='btn btn-success btn-accept'>";
    echo "</td>";
    echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal1('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#fileupload'>Electronic Health Records</button>";

    echo "</form>";

    echo "<td>";
    echo "<td>";

 // Check appointment status to determine button visibility
 if ($row['App_status'] == 'Pending') {
  echo "<form method='post'>";
  echo "<input type='hidden' name='appointmentID' value='" . $row['appointment_ID'] . "'>";
  echo "<button type='submit' name='accept' class='btn btn-success btn-accept'>Accept</button>";
  echo "<button type='submit' name='cancel' class='btn btn-danger btn-cancel'>Cancel</button>";
  echo "</form>";
} elseif ($row['App_status'] == 'Accepted') {
  echo "<form method='post'>";
  echo "<input type='hidden' name='appointmentID' value='" . $row['appointment_ID'] . "'>";
  echo "<button type='submit' name='done' class='btn btn-primary btn-done'>Done</button>";
  echo "</form>";
} // No action for 'Done' status since you only need the Done button

echo "</td>";
echo "</tr>";
}
} else {
echo "<tr><td colspan='6'>No data available</td></tr>";
}
?>


<!-- Modal for EHR-->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <h5 class="modal-title" id="userdetailTitle"></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div> -->
<div class="modal fade modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Electronic Health Records</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action='ehrupload.php' method='post'>
        
          <div class="form-group">
            <label for="app_idModal" class="col-form-label">Appointment ID</label>
            <input type="text" class="form-control" id="app_idModal" name="app_idModal"  readonly>
          </div>
          <div class="form-group" style="display: none;">
            <label for="patient_idModal" class="col-form-label">Patient ID</label>
            <input type="text" class="form-control" id="patient_idModal"name="patient_idModal" readonly>
          </div>
          <div class="form-group">
            <label for="patient_nameModal" class="col-form-label">Patient Name</label>
            <input type="text" class="form-control" id="patient_nameModal"name="patient_nameModal" readonly>
          </div>
          <div class="form-group">
            <label for="appointmentstatus_Modal" class="col-form-label">Appointment Status</label>
            <input type="text" class="form-control" id="appointmentstatus_Modal" name="appointmentstatus_Modal" readonly>
          </div>
          <div class="form-group">
            <label for="appointDate_Modal" class="col-form-label">Appointment Date</label>
            <input type="text" class="form-control" id="appointmentDate_Modal" name="appointmentDate_Modal" readonly>
          </div>
          <div class="form-group">
            <label for="pHeight_Modal" class="col-form-label">Height (cm)</label>
            <input type="number" class="form-control" id="pHeight_Modal" name="pHeight_Modal">
          </div>
          <div class="form-group">
            <label for="pWeight_Modal" class="col-form-label">Weight (kg)</label>
            <input type="number" class="form-control" id="pWeight_Modal" name="pWeight_Modal">
          </div>
          <div class="form-group">
            <label for="bmi_Modal" class="col-form-label">BMI</label>
            <input type="number" class="form-control" id="bmi_Modal" name="bmi_Modal">
          </div>
          <div class="form-group">
            <label for="pBloodType_Modal" class="col-form-label">Blood type</label>
              <select name="pBloodType" id="pBloodType" name="pBloodType" class="form-control form-control-lg">
                  <option disabled selected>Select Bloodtype</option>
                  <option value="o_positive">O Positive</option>
                  <option value="o_negative">O Negative</option>
                  <option value="a_positive">A Positive</option>
                  <option value="a_negative">A Negative</option>
                  <option value="b_positive">B Positive</option>
                  <option value="b_negative">B Negative</option>
                  <option value="ab_positive">AB Positive</option>
                  <option value="ab_negative">AB Negative</option>
              </select>
          </div>
          <div class="form-group">
            <label for="bp_Modal" class="col-form-label">Blood Pressure</label>
            <input type="text" class="form-control" id="appointmentstatus_Modal" name="bp_Modal">
          </div>
          <div class="form-group">
            <label for="respiratoryRate_Modal" class="col-form-label">Respiratory rate (Per Minute)</label>
            <input type="text" class="form-control" id="respiratoryRate_Modal" name="respiratoryRate_Modal">
          </div>
        <div class="form-group">
            <label for="temp_Modal" class="col-form-label">Temperatory (Celcius)</label>
            <input type="text" class="form-control" id="temp_Modal" name="temp_Modal">
          </div>

        <div class="form-group">
            <label for="drug_Modal" class="col-form-label">Drug name</label>
            <input type="text" class="form-control" id="drug_Modal" name="drug_Modal">
          </div>
          <div class="form-group">
            <label for="prescribeDate_Modal" class="col-form-label">Prescribe Date</label>
            <input type="text" class="form-control" id="prescribeDate_Modal" name="prescribeDate_Modal" readonly>
          </div>
          <div class="form-group">
            <label for="note_Modal" class="col-form-label">Additional Note</label>
            <textarea id="w3review" class ="form-control" name="note_Modal"  rows="4" cols="50">s.</textarea>
          </div>
          <input type="submit" class="btn btn-dark btn-lg" value="Submit"><br><br>
        </form>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Upload patient Files modal-->
<div class="modal fade modal-lg" id="fileupload" tabindex="-1" role="dialog" aria-labelledby="fileupload" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileupload">List of uploaded documents</h5>
      </div>
      <div class="modal-body">
      <div class="form-group">
            <label for="app_idModal1" class="col-form-label">Appointment ID</label>
            <input type="text" class="form-control" id="app_idModal1" name="app_idModal1"  readonly>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- Patiend modal details-->
<!-- height (cm), weight (kg), BMI, Blood Type, Blood Pressure, Respiratory rate(/min), Temperature (Celsius)
Drug name, prescribed date -->
<!-- modal script-->
<!-- <script>
  function openModal1(appointmentID1) {
    $('#app_idModal1').val(appointmentID1); // Set the value of Appointment ID textbox
    $('#fileupload').modal('show'); // Open the modal
  }
</script> -->
<!-- <script>
  function openModal1(appointmentID1) {
  $('#app_idModal1').val(appointmentID1); // Set the value of Appointment ID textbox
  $.ajax({
    type: 'POST',
    url: 'fetch_files.php',
    data: { appointmentID: appointmentID1 },
    success: function(response) {
      $('#fileupload').modal('show'); // Open the modal
      $('#modal-body').html(response); // Update the modal body with the response from the server
    }
  });
} -->
<script>
  function openModal(appointmentID, patientID, patientName, appointmentstatus_Modal, appointmentDate) {
    $('#app_idModal').val(appointmentID); // Set the value of Appointment ID textbox
    $('#patient_idModal').val(patientID); // Set the value of Appointment ID textbox
    $('#patient_nameModal').val(patientName); // Set the value of Patient Name textbox
    $('#appointmentstatus_Modal').val(appointmentstatus_Modal );
    $('#appointmentDate_Modal').val(appointmentDate );
    $('#prescribeDate_Modal').val(appointmentDate );
    $('#exampleModal').modal('show'); // Open the modal
  }
</script>
<script>
  function openModal1(appointmentID1) {
    $('#app_idModal1').val(appointmentID1); // Set the value of Appointment ID textbox
    $.ajax({
      type: 'POST',
      url: 'fetch_files.php',
      data: { appointmentID: appointmentID1 },
      success: function(response) {
        $('#fileupload').modal('show'); // Open the modal
        $('#fileupload .modal-body').html(response); // Update the modal body with the response from the server
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
  }
</script>
  <!-- Modal patient detail-->
  <div class="modaltext">
<div class="modal fade" id="userdetail" tabindex="-1" aria-labelledby="userdetail" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="userdetail">Account Details</h1>
      </div>
      <div class="modal-body">
      <h5>Name</H5><h3 ><?php echo htmlspecialchars($_SESSION["docname"]); ?></h3>
      <h5 class ="mt-3">Email</H5><h3 ><?php echo htmlspecialchars($_SESSION["docemail"]); ?></h3>
      <h5 class ="mt-3">Telephone Number</H5><h3 ><?php echo htmlspecialchars($_SESSION["doctel"]); ?></h3>
      <h5 class ="mt-3">Specialties</H5><h3 ><?php echo htmlspecialchars($_SESSION["sname"]); ?></h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
      </div>
</body>
<!-- Modal -->

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>