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
$action = isset($_POST['action']) ? $_POST['action'] : null;
switch ($action) {
  case 'disable':
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Validate and sanitize the selected docid
      if (isset($_POST["specialty"])) {
          $selectedDocid = $_POST["specialty"];
          // Perform further validation if needed
          $selectedDocid = intval($selectedDocid);
    
          // Check if the doctor has pending appointments
          $appointmentCheckSql = "SELECT COUNT(*) as pending_appointments FROM appointments WHERE docid = ? AND (App_Status = 'pending' or App_Status = 'Accepted')";
          $checkStmt = $mysqli->prepare($appointmentCheckSql);
          $checkStmt->bind_param("i", $selectedDocid);
          
          if ($checkStmt->execute()) {
              $result = $checkStmt->get_result();
              $row = $result->fetch_assoc();
              
              if ($row['pending_appointments'] > 0) {
                  $delete_err = "The doctor has pending appointments and cannot be deleted.";
              } else {
                  // Delete the row based on the selected docid
                  $sql = "UPDATE doctor SET delete_status = 1 WHERE docid = ?";
                  $stmt = $mysqli->prepare($sql);
                  $stmt->bind_param("i", $selectedDocid);
    
                  try {
                      if ($stmt->execute()) {
                          $delete_err = "Doctor account disabled";
                      } else {
                          $delete_err = "Doctor account not disabled";
                      }
                  } catch (Exception $e) {
                      // Handle the exception and display an error message
                      $delete_err = "Error occurred: " . $e->getMessage();
                  }
              }
          } else {
              $delete_err = "Error checking appointments.";
          }
      } else {
          $delete_err = "Please select a Doctor.";
      }
    }
      break;

  case 'activate1':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Validate and sanitize the selected docid
          if (isset($_POST["activatedoc"])) {
            $selectedDocid = $_POST["activatedoc"];
            // Perform further validation if needed
            $selectedDocid = intval($selectedDocid);
      
            // Delete the row based on the selected docid
            $sql = "UPDATE doctor SET delete_status = 0 WHERE docid = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $selectedDocid);
      
            try {
                if ($stmt->execute()) {
                    $delete_err = "Doctor Account re-activated";
                } else {
                    $delete_err = "Doctor Account not re-activated";
                }
            } catch (Exception $e) {
                // Handle the exception and display an error message
                $delete_err = "Encounter error";
            }
        } else {
            $delete_err = "Please select a Doctor.";
        }
      }
  
      break;

  default:
      //action not found
      break;
}
// Check if the form is submitted

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
  <!-- TIMER FUNCTION -->


</head>
<body>
<?php 
        if(!empty($delete_err)){
          echo '<script>alert("'. $delete_err.'")</script>';
        }        
        ?>


<?php include 'adminheader.php';?>
<main id="main">
  <section class="vh-110 mt-5">
  <div class="container py-10 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; ">
          <div class="row gx-5">
            <div class="col-md-10 col-lg-10 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type='hidden' name='action' value='disable'>
                  <div class="d-flex align-items-center mb-3 pb-1 mr">
                    <span class="h1 fw-bold mb-0">Disable Doctor Account</span>
                  </div>
                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"></h5>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="gender">Select Doctor account that will be disable</label><br>
                    <select name="specialty" class="form-control form-control-lg">
  <option disabled selected>Select Doctor</option>
  <?php
            $sql1 = "select * from doctor WHERE delete_status IS NULL OR delete_status = 0;";
            $result1 = $mysqli->query($sql1);
while ($row = $result1->fetch_assoc()) {
    $docid = $row['docid'];
    $docname = $row['docname'];
    echo "<option value='$docid'>$docid - $docname</option>";
  } 
            ?>
          </select>
          <span class="invalid-feedback"><?php echo $specialties_err; ?></span>

                  </div>    

                  <div class="pt-1 mb-4">
                  <input type="submit" class="btn btn-danger btn-danger" value="Disable"><br><br>
                    <button type='button' class='btn btn-success btn-success' data-bs-toggle="modal" data-bs-target="#activate">Re-Activate doctor account here</button><br><br>

                  </div>
                </form> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade modal-lg" id="activate" tabindex="-1" role="dialog" aria-labelledby="activate" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="activate">Select Doctor account</h5>
      </div>
      <div class="modal-body">

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <input type='hidden' name='action' value='activate1'>
      <select name="activatedoc" class="form-control form-control-lg">
  <option disabled selected>Select Doctor</option>
  <?php
            $sql2 = "select * from doctor WHERE delete_status =1";
            $result1 = $mysqli->query($sql2);
while ($row = $result1->fetch_assoc()) {
    $docid1 = $row['docid'];
    $docname1 = $row['docname'];
    echo "<option value='$docid1'>$docid1 - $docname1</option>";
  } 
            ?>
          </select>
      
      </div>
      <div class="modal-footer">
      <input type="submit" class="btn btn-dark btn-dark" value="Activate"><br><br>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>