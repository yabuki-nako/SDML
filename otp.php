<?php

// Start session if not already started
session_start();



$alert = "Invalid OTP";
$otpsent = "OTP has already been sent. Please check your email or wait before requesting a new one.";
$current_time = time(); // Get current timestamp

// Include PHPMailer
require_once "assets/PHPMailer/src/PHPMailer.php";
require_once "assets/PHPMailer/src/SMTP.php";
require_once "assets/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to check if OTP has been sent within last 1 minutes
function otpSentRecently() {
    if(isset($_SESSION['otp_time'])) {
        return (time() - $_SESSION['otp_time']) <= (15 * 60);
    }
    return false;
}

// Check if OTP has already been sent recently
if(isset($_SESSION['otp']) && otpSentRecently()) {
    // OTP has been sent recently, show message or handle accordingly
    echo "";
} else {
    // Generate OTP
    $otp = mt_rand(100000, 999999);

    // Store OTP and timestamp in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = $current_time;

    // Create PHPMailer instance
    $mail = new PHPMailer();
    $mail->SMTPDebug = 2; // Enable debugging
    $mail->Debugoutput = 'html'; 
    // Configure PHPMailer
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'Saint.Dominic4027@gmail.com'; // SMTP username
    $mail->Password = 'nteu yvvf bben sfed'; // SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('lesterlusung1414@gmail.com', 'Clinic Test');
    $mail->addAddress($_SESSION['email']); // User's email
    $mail->Subject = 'OTP Verification';
    $mail->Body = "Good day! To complete the login process, please use the One Time Password (OTP) below: 

    ".$otp."

    Please note that the OTP is valid for only one login attempt and will expire within 1 mins. 
    Note: NEVER share this code with others, if this is not you please email us at saintdominiclaboratory@gmail.com

    * This is a system generated message. DO NOT REPLY TO THIS EMAIL. *";

    // Send email
    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
}

$otp_err = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["otp"]) || empty(trim($_POST["otp"]))) {
          $otp_err = "Please enter otp."; 
      } else {
          $otp_input = trim($_POST["otp"]);
      }
  if (isset($_SESSION['otp']) && isset($_POST['otp']) && $_POST['otp'] == $_SESSION['otp']) {
      // Check if OTP is expired
      if (isset($_SESSION['otp_time']) && ($current_time - $_SESSION['otp_time']) <= (15 * 60)) { // 1 minutes * 60 seconds
          // OTP is valid, set a flag indicating it's correct
          $_SESSION["loggedin"] = true;

          // Redirect to welcomepatient.php after successful OTP verification
          header("location: welcomepatient.php");
          exit; // Don't forget to exit after redirecting
      } else {
          // OTP is expired
          echo "<script type='text/javascript'>alert('OTP has expired. Please request a new one.');</script>";
      }
  } else {
      // Invalid OTP
      echo "<script type='text/javascript'>alert('Invalid OTP');</script>";
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
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <img src ="assets/img/logoName.png" width="250" height="60" >
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.php">Home</a></li>
          
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header><!-- End Header -->
  <!-- End Header -->
  <main id="main">
  <section class="vh-100" style="background-color: #3A5A40;">
  <div class="container py-10 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
      <div class="card" style="border-radius: 1rem; background-color: rgba(255, 255, 255, 0.8);">

          <div class="row gx-5">
          <!-- <div class="col-md-6 col-lg-5 d-none d-md-block">
                        <img src="assets/img/logmain.png"
                          alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                      </div> -->
          <div class="col-md-10 col-lg-10 d-flex align-items-center">
                
              <div class="card-body p-4 p-lg-5 text-black">

              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                  <div class="d-flex align-items-center mb-0 pb-1 mr">

                    <span class="h2 mb-0" style="letter-spacing: 2px;">Hello <?php  echo htmlspecialchars($_SESSION["pname"]); ?>!<br> Insert your One Time Pin (OTP)</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" ></h5>

                  <div class="form-outline mb-4">
                    <input type="text" name ="otp"id="otp" class="form-control form-control-lg <?php echo (!empty($otp_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $otp_err; ?></span>
                  </div>
                  <div class="pt-1 mb-2">
                        <input type="submit" class="btn btn-dark btn-lg btn-block" value="Verify OTP">
                 
                    </div>
                </form>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h5 class="fw-normal mb-3 pb-3" ></h5>
    <input type="submit" id="resendBtn" class="btn btn-dark btn-lg btn-block mb-2" value="Resend OTP <?php //echo isset($_SESSION['otp_time']) ? formatRemainingTime($_SESSION['otp_time']) : ''; ?>">
    <span class ="h6" id="timer"></span> 
  </form>
<?php
function formatRemainingTime($otp_time) {
    $remaining_time = ($otp_time + (15 * 60)) - time();
    $minutes = floor($remaining_time / 60);
    $seconds = $remaining_time % 60;
    return '(' . $minutes . 'm ' . $seconds . 's)';
}
?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</main><!-- End #main -->

  <!-- ======= Footer ======= -->
<?php include "footer.php";?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

<script>
    // Check if the OTP is expired
    var otpExpired = <?php echo (isset($_SESSION['otp']) && isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time']) > (15 * 60)) ? 'true' : 'false'; ?>;
    var remainingTime = <?php echo (isset($_SESSION['otp']) && isset($_SESSION['otp_time'])) ? ($_SESSION['otp_time'] + (15 * 60)) - time() : 0; ?>;
    
    // Disable the resend OTP button if OTP is not expired
    if (!otpExpired) {
        document.getElementById("resendBtn").disabled = true;
    } else {
        // Display timer
        displayTimer(remainingTime);
    }

    // Function to enable the resend OTP button after 1 minutes
    function enableResendButton() {
        document.getElementById("resendBtn").disabled = false;
    }



    // Call the function to enable the resend OTP button after 1 minutes
    setTimeout(enableResendButton, remainingTime * 1000); // Convert remaining time to milliseconds

    function startTimer() {
    var remainingTime = <?php echo (isset($_SESSION['otp']) && isset($_SESSION['otp_time'])) ? ($_SESSION['otp_time'] + (15 * 59)) - time() : 0; ?>;
    var timerElement = document.getElementById("timer");
    var interval = setInterval(function() {
        var minutes = Math.floor(remainingTime / 59);
        var seconds = remainingTime % 59;
        timerElement.innerHTML = "Resend OTP in " + minutes + ":" + seconds + "";
        if (remainingTime <= 0) {
            clearInterval(interval);
            timerElement.innerHTML = ""; // Clear timer when it reaches 0
        } else {
            remainingTime--;
        }
    }, 1000);
}

// Start the timer when the page loads
startTimer();
</script>

</body>
</html>
