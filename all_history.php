<?php
// Initialize the session
session_start();
require_once "config.php";
// time and date
$today = date("F j, Y ");
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true){
    header("location: admin_login.php");
    exit;
}
$pId1 = $_SESSION['id'];
$sql = "	SELECT appointments.appointment_ID, doctor.docname, specialties.sname, patient_detail.pId, patient_detail.pname,
appointments.appDate, appointments.appTime, docsched.time_schedule, appointments.App_status FROM appointments INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id INNER JOIN patient_detail ON appointments.pId = patient_detail.pId INNER JOIN docsched ON appointments.appTime = docsched.appTime";

$result = $mysqli->query($sql);
$rowCount = mysqli_num_rows($result)

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


  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/patient.css" rel="stylesheet">
  <link href="assets/css/header.css" rel="stylesheet">

  
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
<?php include 'adminheader.php';?>

<div class ="mb-5 mt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; background-color: white;">
        <div class="card-body p-4 p-lg-12">
        <h3>List of All Appointment</h3>
        <h4>Total Appointment - <?php echo $rowCount;?></h4>
          <hr class="app"></hr>
          <table class="table table-striped table-default ">
    <thead class="table-dark">
            <th>Appointment ID </th>
            <th>Doctor name</th>
            <th>Department</th>
            <th>Patient Name</th>
            <th>Patient ID</th>
            <th>Date</th>
            <th>Time </th>
            <th>Status </th>

            </div>
        </tr>
    </thead>

</tbody>
<?php
$pId = $_SESSION['id'];
// Loop through the result set and generate table rows
$sql1 = "	SELECT appointments.appointment_ID, doctor.docname, specialties.sname, patient_detail.pId, patient_detail.pname,
appointments.appDate, appointments.appTime, docsched.time_schedule, appointments.App_status FROM appointments INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id INNER JOIN patient_detail ON appointments.pId = patient_detail.pId INNER JOIN docsched ON appointments.appTime = docsched.appTime ORDER BY appointments.appointment_ID desc";
$result1 = $mysqli->query($sql1); 

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['appointment_ID'] . "</td>";
        echo "<td>" . $row['docname'] . "</td>";
        echo "<td>" . $row['sname'] . "</td>";
        echo "<td>" . $row['pname'] . "</td>";
        echo "<td>" . $row['pId'] . "</td>";
        echo "<td>" . $row['appDate'] . "</td>";
        echo "<td>" . $row['time_schedule'] . "</td>";
        if ($row['App_status'] === 'Cancelled') {
          echo "<td><span style='color: #f85a40;'>" . $row['App_status'] . "</span></td>";
      } else if  ($row['App_status'] ==='Done'){
          echo "<td><span style='color: #008374;'>".$row['App_status']."</span></td>";
      }else {
          echo "<td>" . $row['App_status'] . "</td>";
      }
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No data available</td></tr>";
}

?>

        
                  </div>
         
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modals -->
  <!-- Modal patient detail-->
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


<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>