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
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the selected docid
    if (isset($_POST["specialty"])) {
      $selectedDocid = $_POST["specialty"];
      // Perform further validation if needed
      $selectedDocid = intval($selectedDocid);

      // Delete the row based on the selected docid
      $sql = "DELETE FROM doctor WHERE docid = ?";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("s", $selectedDocid);

      try {
          if ($stmt->execute()) {
              $delete_err = "Doctor deleted";
          } else {
              $delete_err = "Not deleted";
          }
      } catch (Exception $e) {
          // Handle the exception and display an error message
          $delete_err = "The doctor has an appointment";
      }
  } else {
      $delete_err = "Please select a Doctor.";
  }
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="patientlog">
                  <div class="d-flex align-items-center mb-3 pb-1 mr">
                    <span class="h1 fw-bold mb-0">Delete Doctor Account</span>
                  </div>
                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"></h5>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="gender">Select Doctor that will be deleted</label><br>
                    <select name="specialty" class="form-control form-control-lg" onchange="loadSelectedOption()">
  <option disabled selected>Select Doctor</option>
  <?php
            $sql1 = "select * from doctor;";
            $result1 = $mysqli->query($sql1);
while ($row = $result1->fetch_assoc()) {
    $docid = $row['docid'];
    $docname = $row['docname'];
    echo "<option value='$docid'>$docid - $docname</option>";
  } 
            ?>
          </select>
          <!-- <input type="text" id="selectedOptionText" placeholder="Selected Option" readonly> -->

          <span class="invalid-feedback"><?php echo $specialties_err; ?></span>

                  </div>    

                  <div class="pt-1 mb-4">
                    <input type="submit" class="btn btn-dark btn-lg" value="Delete"><br><br>
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
</section>


<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>