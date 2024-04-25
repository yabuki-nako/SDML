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

// Define variables and initialize with empty values
$appTime = $appDate = $docid = "";
$appTime_err = $appDate_err = $docid_err = "";




// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

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
  $appTime = intval($_POST["appTime"]);
}

if (empty(trim($_POST["appDate"]))) {
  $appDate_err = "Please select application Date.";
} else {
  $appDate = trim($_POST["appDate"]);
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

      // Execute the prepared statement
      if ($stmt->execute()) {
          // Store the result
          $stmt->store_result();

          if ($stmt->num_rows > 0) {
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
 //Validate gender
  if(empty($_POST["docid"])){
    $docid_err = "Please select doctor.";     
} else{
    $docid = trim($_POST["docid"]);
}

    // Validate Birthday
    if(empty($_POST["appDate"])){
      $appDate_err = "Please select birthday.";     
  } else{
      $appDate = trim($_POST["appDate"]);
  }

  // Check input errors before inserting in database
  if(empty($appDate_err) && empty($appTime_err) && empty($docid_err)){
    $appTime = intval($appTime);
      // Prepare an insert statement
      $sql2 = "INSERT INTO appointments (docid, pId,  appDate, appTime, App_status) VALUES (?, ?, ?, ?, 'Pending')";
       
      if($stmt = $mysqli->prepare($sql2)){
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("ssss",$param_docid, $param_pid, $param_appDate, $param_appTime);
          
          // Set parameters
          
          $param_pid = $_SESSION['id'];
          $param_docid = $docid;
          $param_appDate = $appDate;
          $param_appTime = $appTime;

          // Attempt to execute the prepared statement
          if($stmt->execute()){
              // Redirect to login page
              header("location: welcomepatient.php");
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          $stmt->close();
      }
  }
  


}

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
  <link href="assets/css/patientmain.css" rel="stylesheet">
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
    $('appDate'). datetimepicker({ minDate: new Date() }); 
  </script>

</head>
<body>

<header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
    <div class="headercont">


    <h1>Makiling Clinic</h1>
  </div>
      <nav id="navbar" class="navbar">
        <ul>  
          <li><a href="welcomepatient.php">Home</a></li>
          <li><a href="booking.php">Appointment booking</a></li>
          <li><a href="apphistory.php">Appointment History</a></li>
          <li class="dropdown"><a href="#"><span>Profile</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a data-bs-toggle="modal" data-bs-target="#userdetail">View Account details</a></li> 

              <li><a href="resetpass.php">Reset Password</a></li>    
              <li><a href="logout.php">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </nav><!-- .navbar --><!-- .navbar -->



  </header>
  <br><br><br>
  <div class="d-flex justify-content-center ">

<div class="mt-4 mb-0">
      <h4><b>&nbsp;Today's Date and Time&nbsp;</b>
      <div class="d-flex"> <?php echo $today?>&nbsp;||&nbsp;<div id="time"></h4>
 

 
</div>
</div>
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
            <span class="h1 fw-bold mb-0">Book appointment</span>
          </div>

          <div class="form-outline mb-4">
            <label class="form-label" for="gender">Select Services</label>
            <select name="docid" class="form-control form-control-lg <?php echo (!empty($docid_err)) ? 'is-invalid' : ''; ?>">
<option disabled selected>Select Services</option>
<?php
$sql = "SELECT  doctor.docid, doctor.docname, specialties.id,specialties.sname
FROM doctor
JOIN specialties ON doctor.specialties = specialties.id;";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
$docid = $row['docid'];
$sname = $row['sname'];
$docname = $row['docname'];
echo "<option value='" . (int)$docid . "'>$sname - $docname</option>";

}
?>
</select>
            <span class="invalid-feedback"><?php echo $docid_err; ?></span>
          </div>
          <div class="form-outline mb-4">
          <label class="form-label" for="form2Example27">Appointment Date</label>
            <input type="date" id="appDate"  name="appDate" onkeydown="return false" onfocus="blur()" min="<?php echo date("Y-m-d"); ?>" class="form-control form-control-lg <?php echo (!empty($appDate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $appDate; ?>">
            <span class="invalid-feedback"><?php echo $appDate_err; ?></span>
          </div>
          <div class="form-outline mb-4">
          <!-- <label class="form-label" for="form2Example27">Appointment Time</label>
          <input type="time" id="appTime" name="appTime"  min="09:00" max="18:00" class="form-control form-control-lg"required>
          </div> -->
          <div class="form-outline mb-">
            <label class="form-label" for="appTime">Select Time</label>
            <select name="appTime" id="appTime" class="form-control form-control-lg <?php echo (!empty($appTime_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $appTime; ?>">
            <option disabled selected>Select Time</option>
            <option value="1">8:00 AM - 9:00 AM</option>
            <option value="2">9:00 AM - 10:0 0AM</option>
            <option value="3">10:00 AM - 11:00 AM</option>
            <option value="4">11:00 PM- 12:00 PM</option>
     
            <option value="5">1:00 PM - 2:00PM</option>
            <option value="6">2:00 PM - 3:00PM</option>
            <option value="7">3:00 PM - 4:00PM</option>
            <option value="8">4:00 PM - 5:00PM</option>
            <option value="9">5:00 PM - 6:00PM</option>
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



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>