<?php
// Initialize the session
session_start();
require_once "config.php";
// time and date
$today = date("F j, Y ");
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$appTime = $appDate = $docid = $medtech = "";
$appTime_err = $appDate_err = $docid_err = $ser_err = "";


$medtech_js = json_encode($medtech);

if($_SERVER["REQUEST_METHOD"] == "POST"){
  // var_dump($_POST);
  
  // Validate Time
  // if(empty(trim($_POST["appTime"]))){
  //     $email_err = "Please Select Time.";
  // } elseif(!preg_match('/^[a-zA-Z0-9_@.]+$/', trim($_POST["email"]))){
  //     $email_err = "email can only contain letters, numbers, and underscores.";
  // } else{
  // Validate Time and Date
if (empty($_POST["appTime"])) {
  $appTime_err = "Please select application Time.";
} else {
  $appTime = trim($_POST["appTime"]);
}

 if (trim($_POST['medtech']) == '1' && trim($_POST['services1']) == "Select Service") {
        $ser_err = "Please select at least one service.";
        echo "<script type='text/javascript'>alert('Please select at least one service.');</script>";
    } else {
        $ser_err = ""; // Set $ser_err to an empty string when the condition is not met
    }

if (empty(trim($_POST["appDate"]))) {
  $appDate_err = "Please select application Date.";
} else {
  $appDate = $_POST["appDate"];
}

// Check for existing appointment with the same time and date
if (empty($appTime_err) && empty($appDate_err)) {
  $sql = "SELECT appointment_ID FROM appointments WHERE docid = ? AND appTime = ? AND appDate = ?";
    
  if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("sis", $param_docid, $param_appTime, $param_appDate);

      // Set parameters
      $param_docid = trim($_POST["docid"]);
      $param_appTime = $appTime;
      $param_appDate = $appDate;
      $param_medtech = ($_POST["medtech"]);
      // Execute the prepared statement
      if ($stmt->execute()) {
          // Store the result
          $stmt->store_result();

          if ($stmt->num_rows > 0 && $param_medtech != 1) {
              $appTime_err = "This appointment time and date are already taken.";
          }
      } else {
          echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      $stmt->close();
  }
}
  
//sdsd/ 
  if(empty($_POST["docid"])){
    $docid_err = "Please select doctor.";     
} else{
    $docid = trim($_POST["docid"]);
}

$medtech = $_POST['medtech'];
  // Check input errors before inserting in database
  if(empty($appDate_err) && empty($appTime_err) && empty($docid_err) && empty($ser_err)){
    // Prepare an insert statement
    $sql2 = "INSERT INTO appointments (docid, pId, appDate, appTime, service1, service2, service3, App_status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
       
    if($stmt = $mysqli->prepare($sql2)){
        $stmt->bind_param("sssssss", $param_docid, $param_pid, $param_appDate, $param_appTime, $param_service1, $param_service2, $param_service3);
          
        // Set parameters
        $param_pid = $_SESSION['id'];
        $param_docid = $docid;
        $param_appDate = $appDate;
        $param_appTime = $appTime; 
        $param_medtech = $medtech;
        if ($medtech == 1) {

          $param_service1 = ($_POST['services1'] != "Select Service") ? $_POST['services1'] : null;
          $param_service2 = ($_POST['services2'] != "Select Service") ? $_POST['services2'] : null;
          $param_service3 = ($_POST['services3'] != "Select Service") ? $_POST['services3'] : null;
      } else {

          $param_service1 = null;
          $param_service2 = null;
          $param_service3 = null;
      }

        if($stmt->execute()){
            echo "<script type='text/javascript'>alert('Sucessfully scheduled your appointment!');</script>";
            echo "<script type='text/javascript'>location.href = 'welcomepatient.php';</script>";
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}
  
// var_dump($ser_err);

}

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
  <link href="assets/css/header.css" rel="stylesheet">
  <link href="assets/css/patientmain.css" rel="stylesheet">
  <link href="assets/css/patient.css" rel="stylesheet">
  <!-- TIMER FUNCTION -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

<?php include 'patientheader.php';?>
  <br><br><br>
  <div class="d-flex justify-content-center ">

</div>
<div class="container py-10 h-100 mb-5 mt-5">
<div class="row d-flex justify-content-center align-items-center h-100">
<div class="col col-xl-8">
<div class="card" style="border-radius: 1rem; background-color: white;">
  <div class="row gx-">
    <div class="col-md-10 col-lg-12 d-flex align-items-center">
      <div class="card-body p-4 p-lg-5 text-black">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="appointment">
          <div class="d-flex align-items-center mb-3 pb-1 mr">
            <span class="h2 fw-bold mb-0">Book your appointment</span>
          </div>

          <div class="form-outline mb-4">
            <label class="form-label" for="gender">Select Services</label>
            <select name="docid" id ="docid" class="form-control form-control-lg <?php echo (!empty($docid_err)) ? 'is-invalid' : ''; ?>">
<option disabled selected>Select Services</option>
<?php
$medtech = array();
$sql = "SELECT  doctor.docid, doctor.docname, specialties.id,specialties.sname, doctor.medtech
FROM doctor
JOIN specialties ON doctor.specialties = specialties.id WHERE delete_status IS NULL OR delete_status = 0";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
$docid = $row['docid'];
$sname = $row['sname'];
$docname = $row['docname'];
$medtech[$docid] = $row['medtech'];
echo "<option value='" . (int)$docid . "'>$sname - $docname</option>";

}
?>
</select>

<input type='hidden' name='medtech' id='medtech' value=''>
            <span class="invalid-feedback"><?php echo $docid_err; ?></span>
          </div>
          <div class="form-outline mb-4" id="services" style="display:none">
            <div class="row">
              <div class="col-sm-4" id="services1Div">
                <select class="form-control form-control-lg <?php echo (!empty($ser_err)) ? 'is-invalid' : ''; ?>" name="services1" id="services1">
                  <option selected>Select Service</option>
                  <option value="Blood Chemistry">Blood Chemistry</option>
                  <option value="Enzymes">Enzymes</option>
                  <option value="Urine Test">Urine Test</option>
                  <option value="Serology">Serology</option>
                  <option value="Thyroid Function Test">Thyroid Function Test</option>
                  <option value="Tumor Markers">Tumor Markers</option>
                  <option value="Parasitology">Parasitology</option>
                  <option value="Hiv Test">HIV Test</option>
                  <option value="Hepatitis Test">Hepatitis Test</option>
                  <option value="Bacteriology">Bacteriology</option>
                  <option value="ECG">ECG (Electrocardiogram)</option>
                  <option value="Echo">2-D Echo (Plain)</option>
                </select>
                <span class="invalid-feedback"><?php echo $ser_err; ?></span>
                <div class ="mt-2"id="addbutton">
              <button type="button" class="btn btn-success" onclick="showNextSelect()" disabled>Add another service</button>
            </div>
              </div>
              <div class="col-sm-4" id="services2Div" style="display: none;">
                <select class="form-control form-control-lg" name="services2" id="services2">
                  <option selected>Select Service</option>
                  <option value="Blood Chemistry">Blood Chemistry</option>
                  <option value="Enzymes">Enzymes</option>
                  <option value="Urine Test">Urine Test</option>
                  <option value="Serology">Serology</option>
                  <option value="Thyroid Function Test">Thyroid Function Test</option>
                  <option value="Tumor Markers">Tumor Markers</option>
                  <option value="Parasitology">Parasitology</option>
                  <option value="Hiv Test">HIV Test</option>
                  <option value="Hepatitis Test">Hepatitis Test</option>
                  <option value="Bacteriology">Bacteriology</option>
                  <option value="ECG">ECG (Electrocardiogram)</option>
                  <option value="2-D Echo">2-D Echo (Plain)</option>
                </select>
                <button type="button" class="btn btn-danger mt-2" onclick="removeService('services2Div')">Delete</button>
              </div>
              <div class="col-sm-4" id="services3Div" style="display: none;">
                <select class="form-control form-control-lg" name="services3" id="services3">
                  <option selected>Select Service</option>
                  <option value="Blood Chemistry">Blood Chemistry</option>
                  <option value="Enzymes">Enzymes</option>
                  <option value="Urine Test">Urine Test</option>
                  <option value="Serology">Serology</option>
                  <option value="Thyroid Function Test">Thyroid Function Test</option>
                  <option value="Tumor Markers">Tumor Markers</option>
                  <option value="Parasitology">Parasitology</option>
                  <option value="Hiv Test">HIV Test</option>
                  <option value="Hepatitis Test">Hepatitis Test</option>
                  <option value="Bacteriology">Bacteriology</option>
                  <option value="ECG">ECG (Electrocardiogram)</option>
                  <option value="2-D Echo">2-D Echo (Plain)</option>
                </select>
                <button type="button" class="btn btn-danger mt-2" onclick="removeService('services3Div')">Delete</button>
              </div>
            </div>
 
          </div>
          
          <div class="form-outline mb-4">
          <label class="form-label" for="form2Example27">Appointment Date</label>
            <input type="date" id="appDate"  name="appDate" onkeydown="return false" onfocus="blur()" min="<?php echo date("Y-m-d"); ?>" class="form-control form-control-lg <?php echo (!empty($appDate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $appDate; ?>">
            
            <span class="invalid-feedback"><?php echo $appDate_err; ?></span>
          </div>
          <div class="form-outline mb-4">

          <div class="form-outline mb-">
            <label class="form-label" for="appTime">Select Time</label>
            <select name="appTime" id="appTime" class="form-control form-control-lg <?php echo (!empty($appTime_err)) ? 'is-invalid' : ''; ?>">
              <option disabled selected>Select Time</option>
              <option value="1">8:00AM - 9:00AM</option>
              <option value="2">9:00AM - 10:00AM</option>
              <option value="3">10:00AM - 11:00AM</option>
              <option value="4">11:00AM - 12:00PM</option>
              <option value="5">1:00PM - 2:00PM</option>
              <option value="6">2:00PM - 3:00PM</option>
              <option value="7">3:00PM - 4:00PM</option>
              <option value="8">4:00PM - 5:00PM</option>
              <option value="9">5:00PM - 6:00PM</option>
            </select>      
          <span class="invalid-feedback"><?php echo $appTime_err; ?></span>  
                   
          
          </div>
          <div class="pt-1 mt-5">
            
            <input type="submit" class="btn btn-dark btn-lg" value="Submit"><br><br>
            <p class="mb-0 pb-lg-2" style="color: #393f81;"><a href="Loginemp.php"></a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>

  <!-- Modals -->
  <!-- Modal -->
  <div class="modaltext">
<div class="modal fade" id="userdetail" tabindex="-1" aria-labelledby="userdetail" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="userdetail">Account Details</h1>
      </div>
      <div class="modal-body">
      <h5>Name</H5><h3 ><?php echo htmlspecialchars($_SESSION["pname"]); ?></h3>
      <h5 class ="mt-3">Email</H5><h3 ><?php echo htmlspecialchars($_SESSION["email"]); ?></h3>
      <h5 class ="mt-3">Cellphone Number</H5><h3 ><?php echo htmlspecialchars($_SESSION["pcellphone"]); ?></h3>
      <h5 class ="mt-3">Gender</H5><h3 ><?php echo htmlspecialchars($_SESSION["pGender"]); ?></h3>
      <h5 class ="mt-3">Birthday</H5><h3 ><?php echo htmlspecialchars($_SESSION["pBirthday"]); ?></h3>
      <h5 class ="mt-3">Address</H5><h3 ><?php echo htmlspecialchars($_SESSION["pAddress"]); ?></h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
      </div>
</body>
<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<script>
  
          function removeService(serviceId) {
            document.getElementById(serviceId).style.display = 'none';
            checkEnableAddButton();
            checkEnableRemoveButtons();
          }

          function checkEnableAddButton() {
            var services1 = document.getElementById('services1Div').style.display !== 'none';
            var services2 = document.getElementById('services2Div').style.display !== 'none';
            var services3 = document.getElementById('services3Div').style.display !== 'none';
            document.getElementById('addbutton').firstElementChild.disabled = services1 && services2 && services3;
          }

          function checkEnableRemoveButtons() {
            var visibleServiceCount = 0;
            var serviceDivs = ['services1Div', 'services2Div', 'services3Div'];

            serviceDivs.forEach(function(serviceId) {
              if (document.getElementById(serviceId).style.display !== 'none') {
                visibleServiceCount++;
              }
            });

            serviceDivs.forEach(function(serviceId) {
              var removeButton = document.querySelector('#' + serviceId + ' .remove-btn');
              if (visibleServiceCount <= 1) {
                removeButton.disabled = true;
              } else {
                removeButton.disabled = false;
              }
            });
          }




//asdasd//
var medtechValues = <?php echo $medtech_js; ?>;
document.getElementById('docid').addEventListener('change', function() {
        var selectedDocId = this.value;
        var medtechValue = <?php echo json_encode($medtech); ?>;


        document.getElementById('medtech').value = medtechValue[selectedDocId];

        // Show or hide the services div based on the medtech value
        var servicesDiv = document.getElementById('services');
        if (medtechValue[selectedDocId] == 1) {
            servicesDiv.style.display = 'block';
        } else {
            servicesDiv.style.display = 'none';
        }
    });



$(document).ready(function(){
    $('select').change(function(){
        var selectedValues = [];
        $('select').each(function(){
            var selected = $(this).val();
            if(selected != 'Select Service'){
                selectedValues.push(selected);
            }
        });
        $('select').each(function(){
            var currentSelect = $(this);
            $('option', this).each(function(){
                var optionValue = $(this).val();
                if(optionValue != 'Select Service' && $.inArray(optionValue, selectedValues) !== -1){
                    if(currentSelect.val() != optionValue){
                        currentSelect.find('option[value="'+optionValue+'"]').prop('disabled', true);
                    }
                } else {
                    currentSelect.find('option[value="'+optionValue+'"]').prop('disabled', false);
                }
            });
        });
    });
});
document.getElementById('services1').addEventListener('change', checkServiceSelection);
document.getElementById('services2').addEventListener('change', checkServiceSelection);

function checkServiceSelection() {
    var addButton = document.getElementById('addbutton').querySelector('button');
    if (this.value !== 'Select Service') {
        addButton.disabled = false;
    } else {
        addButton.disabled = true;
    }
}


function showNextSelect() {
    var services2Div = document.getElementById('services2Div');
    var services3Div = document.getElementById('services3Div');
    var addButton = document.getElementById('addbutton').querySelector('button');

    if (services2Div.style.display === 'none') {
        services2Div.style.display = 'block';
        addButton.disabled = true; // Disable button until next selection
    } else if (services3Div.style.display === 'none') {
        services3Div.style.display = 'block';
        addButton.disabled = true; // Hide button after the third select
    }
}
  </script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>