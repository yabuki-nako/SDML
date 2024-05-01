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
$sql1 = "SELECT appointments.appointment_ID, patient_detail.pname,
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
    echo "<td><button type='button' class='btn btn-success btn-accept' onclick=\"openModal('" . $row['appointment_ID'] . "', '" . $row['pname'] . "', '".$row['App_status']."')\" data-toggle='modal' data-target='#exampleModal'>Electronic Health Records</button>";
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
        <form>
          <div class="form-group">
            <label for="app_idModal" class="col-form-label">Appointment ID</label>
            <input type="text" class="form-control" id="app_idModal" readonly>
          </div>
          <div class="form-group">
            <label for="patient_nameModal" class="col-form-label">Patient Name</label>
            <input type="text" class="form-control" id="patient_nameModal" readonly>
          </div>
          <div class="form-group">
            <label for="appointmentstatus_Modal" class="col-form-label">Appointment Status</label>
            <input type="text" class="form-control" id="appointmentstatus_Modal" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- modal script-->
<script>
  function openModal(appointmentID, patientName, appointmentstatus_Modal) {
    $('#app_idModal').val(appointmentID); // Set the value of Appointment ID textbox
    $('#patient_nameModal').val(patientName); // Set the value of Patient Name textbox
    $('#appointmentstatus_Modal').val(appointmentstatus_Modal );
    $('#exampleModal').modal('show'); // Open the modal
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