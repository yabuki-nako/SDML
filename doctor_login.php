<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcomepatient.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Validate the email format
        // if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Prepare a select statement
            $sql = "SELECT doctor.docid, doctor.docemail,doctor.docpassword,doctor.docname, doctor.doctel, specialties.sname
            FROM doctor
            JOIN specialties ON doctor.specialties = specialties.id WHERE docemail = ?";

            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_docemail);

                // Set parameters
                $param_docemail = $email;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Store result
                    $stmt->store_result();

                    // Check if email exists, if yes then verify password
                    if ($stmt->num_rows == 1) {
                        // Bind result variables
                        $stmt->bind_result($docid, $docemail, $docpassword,  $docname,$doctel, $sname);
                        if ($stmt->fetch()) {
                            if ($docpassword) {
                                // Password is correct, so start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["docid"] = $docid;
                                $_SESSION["docname"] = $docname;
                                $_SESSION["docemail"] = $docemail;

                                $_SESSION["doctel"] = $doctel;

                                $_SESSION["sname"] = $sname;

                                
                                // Redirect user to welcome page
                                header("location: welcomedoctor.php");
                                exit;
                            } else {
                                // Password is not valid, display a generic error message
                                $login_err = "Invalid emaild or password.";
                            }
                        }
                    } else {
                        // Email doesn't exist, display a generic error message
                        $login_err = "Invalid emaidsl or password.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        } else {
            $login_err = "Invalid email format.";
        }
    }


// Close connection
$mysqli->close();

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

  <!-- Favicons -->

  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
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
<?php 
        if(!empty($login_err)){
          echo '<script>alert("'. $login_err.'")</script>';
        }        
        ?>
  <!-- ======= Header ======= -->


  <header id="header" class="header d-flex align-items-center">

  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <img src ="assets/img/logoName.png" width="250" height="60" >
      </a>
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

                  <div class="d-flex align-items-center mb-3 pb-1 mr">

                    <span class="h2 mb-0" style="letter-spacing: 2px;">Sign into your Doctor Account</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" ></h5>

                  <div class="form-outline mb-4">
                    <input type="email" name ="email" id="email" class="form-control form-control-lg" />
                    <label class="form-label" for="email">Email address</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name ="password" id="password" class="form-control form-control-lg" />
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <div class="pt-1 mb-4">
                        <input type="submit" class="btn btn-dark btn-lg btn-block" value="Login">
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



  </main><!-- End #main -->

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