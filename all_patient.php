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
$pId1 = $_SESSION['id'];
$sql = "SELECT * from patient_detail";

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

<header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <div class="headercont">

        <h1>Makiling Clinic</h1>
  </div>
  <nav id="navbar" class="navbar">
        <ul>  
          <li><a href="all_history.php">View All Appointment</a></li>
          <li><a href="all_patient.php">View Patient Account</a></li>
          <li class="dropdown"><a href="#"><span>Manage Doctors</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>

              <li><a href="add_doctors.php">Add Doctors</a></li>
              <li><a href="update_doctors.php">Update Doctor account</a></li>      
              <li><a href="delete_doctors.php">Delete Doctor account</a></li>    
              <li><a href="all_doctors.php">View Doctors</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Profile</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>

              <li><a href="resetpassadmin.php">Reset Password</a></li>    
              <li><a href="logout.php">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </nav>



</header>
<div class ="mb-5 mt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; background-color: white;">
        <div class="card-body p-4 p-lg-12">
        <h3>List of All Patient</h3>
        <h4>Total patient - <?php echo $rowCount;?></h4>
          <hr class="app"></hr>
          <table class="table table-striped table-default ">
    <thead class="table-dark">
            <th>ID </th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact Details</th>
            <th>Gender</th>
            <th>Birthday</th>
            <th>Address</th>
            </div>
        </tr>
    </thead>

</tbody>
<?php
$pId = $_SESSION['id'];
// Loop through the result set and generate table rows
$sql1 = "	SELECT *from patient_detail";
$result1 = $mysqli->query($sql1); 

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['pId'] . "</td>";
        echo "<td>" . $row['pname'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['pCellphone'] . "</td>";
        echo "<td>" . $row['pGender'] . "</td>";
        echo "<td>" .date("F j, Y", strtotime($row['pBirthday'])). "</td>";
        echo "<td>" . $row['paddress'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data available</td></tr>";
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
</body>
<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>