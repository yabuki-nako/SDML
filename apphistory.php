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
$sql = "SELECT appointments.appointment_ID, doctor.docname, specialties.sname, patient_detail.pId, patient_detail.pname,
appointments.appDate, appointments.appTime, docsched.time_schedule, appointments.App_status FROM appointments INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id INNER JOIN patient_detail ON appointments.pId = patient_detail.pId INNER JOIN docsched ON appointments.appTime = docsched.appTime
where patient_detail.pId = $pId1";
$result = $mysqli->query($sql);
$rowCount = mysqli_num_rows($result)

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
  <!-- <link href="assets/css/patientmain.css" rel="stylesheet"> -->
 
  
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

<?php include 'patientheader.php';?>
<section class="vh-110">
<br><br>
<div class ="mb-5 mt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10 mt-5">
        <div class="card" style="border-radius: 1rem; background-color: white;">
        <div class="card-body p-4 p-lg-12">
        <span class="h2 fw-bold mb-0">Appointment History (<?php echo $rowCount;?>)</span>
          <hr class="app"></hr>
          <div class="table-responsive">
          <table class="table table-striped table-default ">
    <thead class="table-dark">
            <th>Appointment ID </th>
            <th>Doctor name</th>
            <th>Department</th>
            <th>Date</th>
            <th>Time </th>
            <th>Status </th>
            <th>Health Records</th>
            <th>Documents</th>
            </div>
        </tr>
    </thead>

</tbody>
<?php
$pId = $_SESSION['id'];
$limit = 15; // Number of rows per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Get current page from URL, default is 1
$offset = ($page - 1) * $limit; // Calculate offset
$totalResult = $mysqli->query("SELECT COUNT(*) as count FROM appointments INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id INNER JOIN patient_detail ON appointments.pId = patient_detail.pId INNER JOIN docsched ON appointments.appTime = docsched.appTime
where patient_detail.pId = $pId");
$totalRow = $totalResult->fetch_assoc();
$totalRows = $totalRow['count'];
$totalPages = ceil($totalRows / $limit); 

// Calculate total pages
// Loop through the result set and generate table rows
$sql1 = "	SELECT appointments.appointment_ID, doctor.docname, specialties.sname, patient_detail.pId, patient_detail.pname,
appointments.appDate, appointments.appTime, docsched.time_schedule, appointments.App_status FROM appointments INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id INNER JOIN patient_detail ON appointments.pId = patient_detail.pId INNER JOIN docsched ON appointments.appTime = docsched.appTime
where patient_detail.pId = $pId ORDER BY appointments.appointment_ID desc
LIMIT $limit OFFSET $offset";
$result1 = $mysqli->query($sql1);

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['appointment_ID'] . "</td>";
        echo "<td>" . $row['docname'] . "</td>";
        echo "<td>" . $row['sname'] . "</td>";
        echo "<td>" . $row['appDate'] . "</td>";
        echo "<td>" . $row['time_schedule'] . "</td>";
        // echo "<td>" . $row['App_status'] . "</td>";
        if ($row['App_status'] == 'Cancelled') {
          echo "<td><span style='color: #f85a40;'>" . $row['App_status'] . "</span></td>";
          echo "<td><button type='button'   class='btn btn-success btn-accept' onclick=\"openModal1('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#viewehr' disabled >View records</button>";        
          echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal2('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#fileupload' disabled>View Documents</button>";        
        } elseif ($row['App_status'] ==='Accepted' || ($row['App_status'] ==='Pending'))  {
          echo "<td>" . $row['App_status'] ."</td>";
          echo "<td><button type='button'   class='btn btn-success btn-accept' onclick=\"openModal1('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#viewehr' disabled >View records</button>";        
          echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal2('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#fileupload' disabled>View Documents</button>";        
           
        }elseif ($row['App_status'] ==='Done') {
          echo "<td><span style='color: #008374;'>" . $row['App_status'] . "</span></td>";
          echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal1('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#viewehr'>View records</button>";        
          echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal2('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#fileupload'>View Documents</button>";        
        }else {
          echo "<td>" . $row['App_status'] . "</td>";
          echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal1('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#viewehr'>View records</button>";        
          echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal2('" . $row['appointment_ID'] . "')\" data-toggle='modal' data-target='#fileupload'>View Documents</button>";        
          echo "</tr>";
        }
         
   
    }
} else {
    echo "<tr><td colspan='7'>No data available</td></tr>";
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

  <!-- Modals -->
  <div class="modal fade modal-xl" id="viewehr" tabindex="-1" role="dialog" aria-labelledby="viewehr" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Health Records of <b><?php echo htmlspecialchars($_SESSION["pname"]); ?></b></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


  <div class="modal fade modal-lg" id="fileupload" tabindex="-1" role="dialog" aria-labelledby="fileupload" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileupload">List of uploaded document/s:</h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




<!--ehr modal function-->
<script>
  function openModal1(appointmentID1) {
    $('#app_idModal1').val(appointmentID1); // Set the value of Appointment ID textbox
    $.ajax({
      type: 'POST',
      url: 'patientfetchehr.php',
      data: { appointmentID: appointmentID1 },
      success: function(response) {
        $('#viewehr').modal('show'); // Open the modal
        $('#viewehr .modal-body').html(response); // Update the modal body with the response from the server
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
  }
</script>
<script>
  function openModal2(appointmentID1) {
    $('#app_idModal1').val(appointmentID1); // Set the value of Appointment ID textbox
    $.ajax({
      type: 'POST',
      url: 'patientfetchfiles.php',
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
</section>
</body>
<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>