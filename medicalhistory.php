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
$docid = $_SESSION['docid'];
$sql2 = "SELECT appointments.appointment_ID, patient_detail.pname, patient_detail.pID,
appointments.appDate, docsched.Time_schedule, appointments.App_status 
FROM appointments 
INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN patient_detail ON appointments.pId = patient_detail.pId  
INNER JOIN docsched ON appointments.appTime = docsched.appTime where doctor.docid = $docid";
$result2 = $mysqli->query($sql2);
$rowCount = mysqli_num_rows($result2);

$sql = "SELECT DISTINCT specialties.sname
FROM doctor
JOIN specialties ON doctor.specialties = specialties.id;";
$result = $mysqli->query($sql);

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


  <link href="assets/css/header.css" rel="stylesheet">
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
        <b><h4>All Appointments - <?php echo $rowCount;?></b></h4>

          <hr class="app"></hr>
          <div class="table-responsive">
          <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Appointment ID </th>
      <th scope="col">Patient Name</th>
      <th scope="col">Patient ID</th>
      <th scope="col">Appointment Date</th>
      <th scope="col">Schedule</th>
      <th scope="col">Appointment Status</th>
    </tr>
  </thead>
  
  
  
  <?php
$docid = $_SESSION['docid'];
$limit = 15; // Number of rows per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Get current page from URL, default is 1
$offset = ($page - 1) * $limit; 
$totalResult = $mysqli->query("SELECT COUNT(*) as count from appointments 
INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN patient_detail ON appointments.pId = patient_detail.pId  
INNER JOIN docsched ON appointments.appTime = docsched.appTime where doctor.docid = $docid");

$totalRow = $totalResult->fetch_assoc();
$totalRows = $totalRow['count'];
$totalPages = ceil($totalRows / $limit); 

$sql1 = "SELECT appointments.appointment_ID, patient_detail.pname, patient_detail.pID,
appointments.appDate, docsched.Time_schedule, appointments.App_status 
FROM appointments 
INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN patient_detail ON appointments.pId = patient_detail.pId  
INNER JOIN docsched ON appointments.appTime = docsched.appTime where doctor.docid = $docid 
ORDER BY appointments.appointment_ID DESC
LIMIT $limit OFFSET $offset";
$result1 = $mysqli->query($sql1);

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['appointment_ID'] . "</td>";
        echo "<td>" . $row['pname'] . "</td>";
        echo "<td>" . $row['pID'] . "</td>";
        echo "<td>" . $row['appDate'] . "</td>";
        echo "<td>" . $row['Time_schedule'] . "</td>";
        if ($row['App_status'] === 'Done'){
          echo "<td><span style='color: #008374;'>" . $row['App_status'] . "</span></td>";
        }elseif($row['App_status'] ==='Cancelled'){
          echo "<td><span style='color: #f85a40;'>" . $row['App_status'] . "</span></td>";
        }else{
          echo "<td>" . $row['App_status'] . "</td>";
        }
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data available</td></tr>";
}

?>
</table>
<nav>
  <ul class="pagination">
    <?php
    if ($page > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Previous</a></li>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $page ? 'active' : '';
        echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
    if ($page < $totalPages) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next</a></li>';
    }
    ?>
  </ul>
</nav>
</div>

</div>
</div>
</div>
</div>
</div>  
</section>


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
      </div>
</body>
<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>