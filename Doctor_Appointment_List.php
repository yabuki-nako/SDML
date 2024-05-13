<?php
// Initialize the session
session_start();
require_once "config.php";
// time and date
$today = date("F j, Y ");
if(!isset($_SESSION["docloggedin"]) || $_SESSION["docloggedin"] !== true){
  header("location: doctor_login.php");
  exit;
}

$drug_Modal = "";
$drugModal_err = "";

$sql = "SELECT DISTINCT specialties.sname
FROM doctor
JOIN specialties ON doctor.specialties = specialties.id;";
$result = $mysqli->query($sql);

$action = isset($_POST['action']) ? $_POST['action'] : null;

switch ($action) {
  case 'other1':
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
                            echo '<META HTTP-EQUIV="Refresh" Content="0;">';
                              echo "<script type='text/javascript'>alert('File successfully uploaded');</script>";


                          } else {
                            echo '<META HTTP-EQUIV="Refresh" Content="0;">';  
                            echo "<script type='text/javascript'>alert('Error inserting file data into database.');</script>";
                          }
                      } else {
                        echo '<META HTTP-EQUIV="Refresh" Content="0;">';
                        echo "<script type='text/javascript'>alert('Error moving uploaded file.');</script>";
                      }
                  } else {
                      echo '<META HTTP-EQUIV="Refresh" Content="0;">';
                      echo "<script type='text/javascript'>alert('Error preparing SQL statement.');</script>";
                  }
              } else {
                  echo '<META HTTP-EQUIV="Refresh" Content="0;">';
                  echo "<script type='text/javascript'>alert('Only PDF, PNG, and JPG files are allowed.');</script>";
              }
          } else {
            echo "<script type='text/javascript'>alert('No file uploaded or an error occurred during upload.');</script>";
              echo '<META HTTP-EQUIV="Refresh" Content="0;">';

          }
      }
      break;

  case 'input1':
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 

      // Assuming you have already sanitized your inputs
      $appointment_ID = $_POST['app_idModal'];
      $pID = $_POST['patient_idModal'];
      $height_cm = floatval($_POST['pHeight_Modal']);
      $weight_kg = floatval($_POST['pWeight_Modal']);
      $BMI = $_POST['bmi_Modal'];
      $blood_type = $_POST['pBloodType'];
      $systolic_BP = $_POST['systolic_BP'];
      $diastolic_BP = $_POST['diastolic_BP'];
      $blood_pressure = $systolic_BP . '/' . $diastolic_BP;
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
          echo '<META HTTP-EQUIV="Refresh" Content="0;">';
      } else {
          echo "Error: " . $sql . "<br>" . $mysqli->error;
      }
      } 
      break;

  default:
      //action not found
      break;
}

//

// Assuming $mysqli is your database connection

// Check if the form was submitted

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
"assets/img/sdml.png" 
        type = "image/x-icon">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>St. Dominic Medical Laboratory</title>
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
  <link href="assets/css/header.css" rel="stylesheet">

<script src = "assets/js/doctor.js"></script>
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
<section class="vh-110">
    <div class="row d-flex justify-content-center align-items-center h-50">

      <div class="col col-xl-10">

        <div class="mt-5 mb-3" style="color: white">
              <h2><b>Today's Date and Time:</b>
              <div class="d-flex"> <?php echo $today?>&nbsp;||&nbsp;<div id="time"></h2>
            </div>

         
        </div>
      </div>
    </div>
 
    <div class="row d-flex justify-content-center align-items-center h-50">

      <div class="col col-xl-10">

      <h2 class ="mb-3" style="color: white">Hi, Welcome Doctor <B><?php  echo htmlspecialchars($_SESSION["docname"]); ?></b>!</h2>
              
      </div>
    </div>

  <div class ="mb-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; background-color: white;">
        <div class="card-body p-4 p-lg-12">
        <h3>Your Appointments</h3>
          <hr class="app"></hr>
          <div class="table-responsive">
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
WHERE (App_status ='Done' OR App_status ='Accepted' or App_status = 'Pending')
      AND doctor.docid = $docid";
$result1 = $mysqli->query($sql1);

if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['appointment_ID'] . "</td>";
    echo "<td>" . $row['pname'] . "</td>";
    echo "<td>" . $row['appDate'] . "</td>";
    echo "<td>" . $row['Time_schedule'] . "</td>";
    echo "<td>" . $row['App_status'] . "</td>";
    echo "<td>";
    if ($row['App_status'] == 'Pending') {
      echo "<form method='post'>";
      echo "<input type='hidden' name='appointmentID' value='" . $row['appointment_ID'] . "'>";
      echo "<div class='button-row'>"; // Start of the first line
      echo "<button type='submit' name='accept' class='btn btn-success btn-accept'>Accept</button>";
      echo "<button type='submit' name='cancel' class='btn btn-danger btn-cancel'>Cancel</button>";
      echo "</div>"; // End of the first line
      echo "</form>";
    } elseif ($row['App_status'] == 'Accepted') {
      echo "<form method='post'>";
      echo "<input type='hidden' name='appointmentID' value='" . $row['appointment_ID'] . "'>";
      echo "<div class='button-row'>"; // Start of the second line
      echo "<button type='submit' name='done' class='btn btn-primary btn-done'>Done</button>";
      echo "</div>"; // End of the second line
      echo "</form>";
    } 
    echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal('" . $row['appointment_ID'] . "', '" . $row['pID'] . "','" . $row['pname'] . "', '".$row['App_status']."','".$row['appDate']."')\" data-toggle='modal' data-target='#exampleModal'>Add Health Record</button>";
    echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' enctype='multipart/form-data'>";
    echo "<input type='hidden' name='action' value='other1'>";
    echo "<input type='hidden' name='appointmentID' value='" . $row['appointment_ID'] . "'>"; // Add hidden input for appointment ID
    echo "<td>";
    echo "<div class='button-row'>";
    echo "<input type='file' name='fileToUpload' id='fileToUpload' class='form-control'>";
    echo "<input type='submit' value='Upload File' name='submit' class='btn btn-success btn-accept'>";
    echo "</div>";  
    echo "</td>";
    echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal1('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#fileupload'>View Files</button>";
    echo "</form>";
}
} else {
echo "<tr><td colspan='6'>No data available</td></tr>";
}
?>

</div></div>
</div>
</div>
</div>
</div>

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
        <h5 class="modal-title" id="exampleModalLabel">Enter health record:</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action='<?php htmlspecialchars($_SERVER["PHP_SELF"])?>' method='post' onsubmit="return validateForm()">
        <input type='hidden' name='action' value='input1'>
        
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
            <input type="number" class="form-control" id="pHeight_Modal" name="pHeight_Modal" placeholder ="" min="0.01" max ="999" step="0.01" oninput="calculateBMI()">
            <small id="warning" style="color: red; display: none;">Please enter a value less than or equal to 999.</small>

          </div>
          <div class="form-group">
            <label for="pWeight_Modal" class="col-form-label">Weight (kg)</label>
            <input type="number" class="form-control" id="pWeight_Modal" name="pWeight_Modal" placeholder ="" min="0.01" max ="999" step="0.01" oninput="calculateBMI()">
          </div>
          <div class="form-group">
            <label for="bmi_Modal" class="col-form-label">BMI</label>
            <input type="number" class="form-control" id="bmi_Modal" name="bmi_Modal" placeholder ="" readonly minx="0.1" max="99">
            <i>Under 18.5	Underweight | 18.5 - 24.9	Normal | 25 - 29.9	Overweight | 30 and over Obese</i>
          </div>
          <div class="form-group">
            <label for="pBloodType_Modal" class="col-form-label">Blood type</label>
              <select name="pBloodType" id="pBloodType" class="form-control form-control-lg">
                  <option value = "0"disabled selected>Select blood type</option>
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
    <div class="input-group">
        <input type="text" class="form-control" id="systolic_BP" name="systolic_BP" placeholder="Systolic">
        <div class="input-group-append">
            <span class="input-group-text">/</span>
        </div>
        <input type="text" class="form-control" id="diastolic_BP" name="diastolic_BP" placeholder="Diastolic">
    </div>
</div>
          <div class="form-group">
            <label for="respiratoryRate_Modal" class="col-form-label">Respiratory rate (Per Minute)</label>
            <input type="number" class="form-control" id="respiratoryRate_Modal" name="respiratoryRate_Modal" min="1" max ="30" step="1" placeholder ="">
          </div>
        <div class="form-group">
            <label for="temp_Modal" class="col-form-label">Temperatory (Celcius)</label>
            <input type="number" class="form-control" id="temp_Modal" name="temp_Modal" min="1" max ="99" step="1" placeholder ="">
          </div>

        <div class="form-group">
            <label for="drug_Modal" class="col-form-label">Drug name</label>
            <input type="text" class="form-control" id="drug_Modal" name="drug_Modal" placeholder ="">
          </div>
          <div class="form-group">
            <label for="prescribeDate_Modal" class="col-form-label">Prescribe Date</label>
            <input type="text" class="form-control" id="prescribeDate_Modal" name="prescribeDate_Modal" readonly>
          </div>
          <div class="form-group">
    <label for="note_Modal" class="col-form-label">Additional Note (optional)</label>
    <textarea id="note_Modal" class="form-control" name="note_Modal" rows="4" cols="50" placeholder =""></textarea>
  </div>
<br>
          <input type="submit" class="btn btn-dark btn-lg" value="Submit"><br><br>
        </form>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>

function validateForm() {
  var pheight = parseFloat(document.getElementById('pHeight_Modal').value.trim());
  var pweight = parseFloat(document.getElementById('pWeight_Modal').value.trim());
  var pBmi = parseFloat(document.getElementById('bmi_Modal').value.trim());
  var systolic_BP = parseFloat(document.getElementById('systolic_BP').value.trim());
  var diastolic_BP = parseFloat(document.getElementById('diastolic_BP').value.trim());
  var pBp = systolic_BP + '/' + diastolic_BP;
  var pResp = parseFloat(document.getElementById('respiratoryRate_Modal').value.trim());
  var pTemp = parseFloat(document.getElementById('temp_Modal').value.trim());
  var pDrug = document.getElementById('drug_Modal').value.trim();
  var bloodType = document.getElementById('pBloodType').value;

  var errorMessage = '';

  if (bloodType === "0") {
    errorMessage += 'Please select a blood type.\n';
  }
  if (isNaN(pheight) || pheight === 0) {
    document.getElementById('pHeight_Modal').placeholder = 'Please enter height.';
    errorMessage += 'Please insert a valid height.\n';
  }
  if (isNaN(pweight) || pweight === 0) {
    document.getElementById('pWeight_Modal').placeholder = 'Please enter weight.';
    errorMessage += 'Please insert a valid weight.\n';
  }
  if (isNaN(pBmi) || pBmi === 0) {
    document.getElementById('bmi_Modal').placeholder = 'Please insert a valid BMI.';
    errorMessage += 'Please insert a valid BMI.\n';
  }
  if (isNaN(systolic_BP) || isNaN(diastolic_BP) || systolic_BP === 0 || diastolic_BP === 0) {
    document.getElementById('systolic_BP', 'diastolic_BP').placeholder = 'Systolic';
    errorMessage += 'Please insert valid values for blood pressure.\n';
    
  }
  if (isNaN(pResp) || pResp === 0) {
    document.getElementById('respiratoryRate_Modal').placeholder = 'Please insert a valid respiratory rate.';
    errorMessage += 'Please insert a valid respiratory rate.\n';
  }
  if (isNaN(pTemp) || pTemp === 0) {
    document.getElementById('temp_Modal').placeholder = 'Please insert a valid temperature.';
    errorMessage += 'Please insert a valid temperature.\n';
  }
  if (pDrug === '') {
    document.getElementById('drug_Modal').placeholder = 'Please insert a valid drug name.';
    errorMessage += 'Please insert a valid drug name.\n';
  }

  if (errorMessage !== '') {
    alert(errorMessage);
    return false;
  }

  return true;
}

</script>

<!-- Add information
Blood Type - user should not be able to input anything in the dropdown box.
Vitals (height - min.0 max. 999 user can input with decimal but only 2 decimal place only
, weight - min.0 max. 999 user can input with decimal but only 2 decimal place only
, BMI = BMI should be computed automatically from height and weight Since the height is given in centimeters, you’ll need to convert it to meters first by dividing the height value by 100. Here’s the step-by-step calculation:


Convert height from centimeters to meters:
height (m)=height (cm)÷100


Calculate BMI using the converted height:
BMI=(height (m))2weight (kg)​


For example, if someone weighs 70 kg and is 175 cm tall, the calculation would be:

Convert height to meters: ( 175 cm \div 100 = 1.75 m )
Calculate BMI: ( \frac{70}{(1.75)^2} \approx 22.86 )


, Blood pressure - Blood pressure readings are expressed in the following form:
[Systolic]/[Diastolic]: XXX/YY mmHg

example: 
100/180

 Temp- min.30 to max.99 celcius)
Medication (prescribed date, Drug name, notes) -->
<!-- <script>


var modal = document.getElementById("exampleModal");

// Get the form inside the modal
var form = document.getElementById("myform");

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

// Prevent the modal from closing when the form is submitted
form.addEventListener("submit", function(event) {
  event.preventDefault(); // Prevent default form submission behavior

  // Submit the form using JavaScript
  submitForm();
});

// Function to submit the form
function submitForm() {
  // Handle form submission here, for example, by sending data via AJAX
  // Once the submission is complete, you may choose to close the modal or perform other actions
  
  // For demonstration purposes, let's just log a message
  console.log("Form submitted");

  // Optionally, close the modal after submission
  // modal.style.display = "none";
}
</script> -->
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
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>

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
</section>
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