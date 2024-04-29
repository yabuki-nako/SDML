<?php
// Include config file
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
}
require_once "config.php";

// Define variables and initialize with empty values
$email = $password  = $pname = $pCellphone = $pGender = $pbirthday = $paddress ="";
$email_err = $password_err = $pname_err = $pCellphone_err = $pGender_err = $pbirthday_err = $paddress_err ="";
$otp_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Invalid email format.";
    } else{
        // Prepare a select statement
        $sql = "SELECT pid FROM patient_detail WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate name
    if(empty(trim($_POST["pname"]))){
      $pname_err = "Please enter a name.";     
  } else{
      $pname = trim($_POST["pname"]);
  }
      // Validate Cellphone
      if(empty(trim($_POST["pCellphone"]))){
        $pCellphone_err = "Please enter Cellphone Number.";     
    } elseif(!preg_match('/^[0-9]+$/', trim($_POST["pCellphone"]))){
      $pCellphone_err = "Cellphone can only contain numbers.";
  } else{
        $pCellphone = trim($_POST["pCellphone"]);
    }
    // Validate gender
    if(empty($_POST["pGender"])){
      $pGender_err = "Please select gender.";     
  } else{
      $pGender = trim($_POST["pGender"]);
  }

      // Validate Birthday
      if(empty($_POST["pbirthday"])){
        $pbirthday_err = "Please select birthday.";     
    } else{
        $pbirthday = trim($_POST["pbirthday"]);
    }

      // Validate address
      if(empty($_POST["paddress"])){
        $paddress_err = "Please Enter address.";     
    } else{
        $paddress = trim($_POST["paddress"]);
    }

// Function to generate OTP
function generateOTP($length = 6) {
  $characters = '0123456789';
  $otp = '';
  $max = strlen($characters) - 1;
  for ($i = 0; $i < $length; $i++) {
      $otp .= $characters[random_int(0, $max)];
  }
  return $otp;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate email
  if(empty(trim($_POST["email"]))){
      $email_err = "Please enter an email.";
  } elseif(!preg_match('/^[a-zA-Z0-9_@.]+$/', trim($_POST["email"]))){
      $email_err = "Email can only contain letters, numbers, and underscores.";
  } else{
      $email = trim($_POST["email"]);
  }

  // Generate and send OTP
  $otp = generateOTP();
  // Store the OTP in session for verification
  $_SESSION['otp'] = $otp;
  // Send the OTP to the user's email (Replace this with your actual email sending logic)
  $to = $email;
  $subject = "OTP for Email Verification";
  $message = "Your OTP for email verification is: $otp";
  $headers = "From: your@example.com" . "\r\n" .
             "CC: another@example.com";
  mail($to, $subject, $message, $headers);

  // Display the OTP verification modal
  echo '<script type="text/javascript">
          $(document).ready(function(){
            $("#otpModal").modal("show");
          });
        </script>';
}

// Verify OTP when the OTP form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
  $user_otp = $_POST['otp'];
  if($user_otp === $_SESSION['otp']) {
      // OTP verified successfully, proceed with account creation
      // Your account creation logic here
  } else {
      $otp_err = "Invalid OTP, please try again.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Makiling Clinic</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link rel = "icon" href = 
"assets/img/icon.png" 
        type = "image/x-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Impact
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/impact-bootstrap-business-website-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->


  <header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>Makiling Clinic<span></span></h1>
      </a>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li class="dropdown"><a href="#"><span>Account</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="Login.php">Log in</a></li>     
              <li><a href="signup.php">Signup</a></li>
            </ul>
          </li>
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header><!-- End Header -->
  <!-- End Header -->

  <main id="main">
  <section class="vh-110 mt-5" style="background-color: #fbeec1">
  <div class="container py-10 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; background-color: rgba(255, 255, 255, 0.8);">
          <div class="row gx-5">
            <div class="col-md-10 col-lg-10 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="patientlog">
                  <div class="d-flex align-items-center mb-3 pb-1 mr">
                    <span class="h1 fw-bold mb-0">Create account</span>
                  </div>
                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"></h5>

                  <div class="form-outline mb-4">
                    <label>Name</label>
                    <input type="text" id="pname" name="pname" class="form-control form-control-lg <?php echo (!empty($pname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pname; ?>">
                    <span class="invalid-feedback"><?php echo $pname_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label>Email</label>
                    <input type="text" id="email" name="email" class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                  </div>
                  
                  <div class="form-outline mb-4">
                    <label>Cellphone Number</label>
                    <input type="text" id="pCellphone" name="pCellphone" class="form-control form-control-lg <?php echo (!empty($pCellphone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pCellphone; ?>">
                    <span class="invalid-feedback"><?php echo $pCellphone_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label>Gender</label>
                    <select name="pGender" id="pGender" class="form-control form-control-lg <?php echo (!empty($pGender_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pGender; ?>">
                      <option disabled selected>Select Gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $pGender_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label>Birthday</label>
                    <input type="date" id="pbirthday"  name="pbirthday" onkeydown="return false" onfocus="blur() "  max="<?php echo date("Y-m-d"); ?>" class="form-control form-control-lg <?php echo (!empty($pbirthday_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pbirthday; ?>">
                    <span class="invalid-feedback"><?php echo $pbirthday_err; ?></span>
                  </div>
                  
                  <div class="form-outline mb-4">
                    <label>Address</label>
                    <input type="text" id="paddress" name ="paddress" class="form-control form-control-lg <?php echo (!empty($paddress_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $paddress; ?>">
                    <span class="invalid-feedback"><?php echo $paddress_err; ?></span>
                  </div>
                  
                  <div class="pt-1 mb-4">
                    <input type="submit" class="btn btn-dark btn-lg" value="Send OTP">
                    <p class="text-danger"><?php echo $otp_err; ?></p>
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

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group">
            <label for="otp">OTP:</label>
            <input type="text" class="form-control" id="otp" name="otp">
          </div>
          <input type="submit" class="btn btn-primary" value="Verify OTP">
        </form>
      </div>
    </div>
  </div>
</div>

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
  <div class="container">
    <div class="row gy-4">
    </div>
  </div>
</footer>

<a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
</body>
</html>
