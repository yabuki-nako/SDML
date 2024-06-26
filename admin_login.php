<?php
session_start();
// admin login details
// Email: cyrix123@gmail.com 
// Password: 123456
// Check if the user is already logged in, if yes then redirect him to welcome page
// if(isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true){
//     header("location: welcomepatient.php");
//     exit;
// }

// Include config file
require_once "config.php";

$email = $password = "";
$email_err = $password_err = $login_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
    
        $sql = "SELECT admin_id, email, password FROM admin_account WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = $email;
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if email exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $email,$hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["adminloggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            header("location: all_history.php");
                        } else{
                            
                            $login_err = "Incorrect password for admin";
                        }
                    }
                } else{

                    $login_err = "Incorrect email for admin";
                }
            } else{
                echo "Oops! ";
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
"assets/img/sdml.png" 
        type = "image/x-icon">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>St. Dominic Medical Laboratory</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->

  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">



  <link href="assets/css/main.css" rel="stylesheet">

</head>

<body>
<?php 
        if(!empty($login_err)){
          echo '<script>alert("'. $login_err.'")</script>';
        }        
        ?>


<?php
$activePage = basename($_SERVER['PHP_SELF']);
?>

<link href="assets/css/main.css" rel="stylesheet">
<link href="assets/css/admin.css" rel="stylesheet">
<header id="header mb-5" class="header d-flex align-items-center">

<div class="container-fluid container-xl d-flex align-items-center justify-content-between ">
    <img src ="assets/img/logoName.png" width="250" height="60" >
  <nav id="navbar" class="navbar">
    <ul>  
        <li><a href="index.php">Home</a></li>
    </ul>
</nav>



</header>

  <main id="main">
  <section class="vh-100" style="background-color: #3A5A40;">
  <div class="container py-10 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
      <div class="card" style="border-radius: 1rem; background-color: rgba(255, 255, 255, 0.8);">

          <div class="row gx-5">
          <div class="col-md-10 col-lg-10 d-flex align-items-center">
                
              <div class="card-body p-4 p-lg-5 text-black">

              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                  <div class="d-flex align-items-center mb-3 pb-1 mr">

                    <span class="h2 mb-0" style="letter-spacing: 2px;">Sign into your Admin Account</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" ></h5>

                  <div class="form-outline mb-4">
                  <label class="form-label" for="email">Email address</label>
                    <input type="email" name ="email"id="email" class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name ="password" id="password" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
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



  </main>

  <footer id="footer" class="footer">

<div class="container">
  <div class="row gy-4">

  </div>
</div>
</footer>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>


  <script src="assets/js/main.js"></script>

</body>

</html>