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
$email = $password = $pname = $pcellphone = $pGender = $pBirthday = $pAddress = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT pId, email, password, pname, pCellphone, pGender, pBirthday, pAddress FROM patient_detail WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $email, $hashed_password, $pname, $pcellphone, $pGender, $pBirthday, $pAddress);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["pname"] = $pname;
                            $_SESSION["pcellphone"] = $pcellphone;
                            $_SESSION["pGender"] = $pGender;
                            $_SESSION["pBirthday"] = $pBirthday;
                            $_SESSION["pAddress"] = $pAddress;
                          
                       
                           

                            // Redirect user to welcome page
                            header("location: otp.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
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
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">


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
          <li class="dropdown"><a href="#"><span>Account</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>   
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

                    <span class="h2 mb-0" style="letter-spacing: 2px;">Sign into your account</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" ></h5>

                  <div class="form-outline mb-4">
                    <input type="email" name ="email"id="email" class="form-control form-control-lg" />
                    <label class="form-label" for="email">Email address</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name ="password" id="passowrd" class="form-control form-control-lg" />
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <div class="pt-1 mb-4">
                        <input type="submit" class="btn btn-dark btn-lg btn-block" value="Login">
                    </div>
                  
                  <p class="mb-0 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="signup.php"
                      style="color: #393f81;">Register here</a></p>
                      <p class="mb-0 pb-lg-2" style="color: #393f81;"><a href="admin_login.php"
                      style="color: #393f81;"><strong>Login as Admin</strong></a></p>       
                      <p class="mb-0 pb-lg-2" style="color: #393f81;"><a href="doctor_login.php"
                      style="color: #393f81;"><strong>Login as Doctor</strong></a></p>             
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


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>