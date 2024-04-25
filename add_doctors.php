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
$docemail = $docpassword  = $docname = $doctel = $specialties= "";
$docemail_err = $docpassword_err = $docname_err = $doctel_err = $specialties_err ="";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate email
    if(empty(trim($_POST["docemail"]))){
        $docemail_err = "Please enter a email.";
    } elseif(!preg_match('/^[a-zA-Z0-9_@.]+$/', trim($_POST["docemail"]))){
        $docemail_err = "email can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT docid FROM doctor WHERE docemail = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["docemail"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $docemail_err = "This email is already taken.";
                } else{
                    $docemail = trim($_POST["docemail"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate password
    if(empty(trim($_POST["docpassword"]))){
        $docpassword_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["docpassword"])) < 6){
        $docpassword_err = "Password must have atleast 6 characters.";
    } else{
        $docpassword = trim($_POST["docpassword"]);
    }
    
    // Validate name
    if(empty(trim($_POST["docname"]))){
      $docname_err = "Please enter a name.";     
  } else{
      $docname = trim($_POST["docname"]);
  }
      // Validate Cellphone
      if(empty(trim($_POST["doctel"]))){
        $doctel_err = "Please enter Cellphone Number.";     
    } elseif(!preg_match('/^[0-9]+$/', trim($_POST["doctel"]))){
      $doctel_err = "Cellphone can only contain numbers.";
  } else{
        $doctel = trim($_POST["doctel"]);
    }
    // Validate specialties
    if(empty($_POST["specialties"])){
      $specialties_err = "Please select department.";     
  } else{
      $specialties = trim($_POST["specialties"]);
  }

    // Check input errors before inserting in database
    if(empty($docemail_err) && empty($docpassword_err) && empty($docname_err) && empty($doctel_err) && empty($specialties_err)){
        $specialties = intval($specialties);
        // Prepare an insert statement
        $sql = "INSERT INTO doctor (docname, docemail, docpassword, doctel,specialties) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss",$param_docname, $param_docemail, $param_docpassword, $param_doctel, $param_specialties);
            
            // Set parameters
            $param_docname = $docname;
            $param_docemail = $docemail;
            $param_doctel = $doctel;
            $param_specialties = $specialties;
            $param_docpassword = password_hash($docpassword, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
            
                header("location: add_doctors.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection

}


$sql = "SELECT * from specialties;";
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

 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">


 
  <link href="assets/css/patient.css" rel="stylesheet">



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
                    <span class="h1 fw-bold mb-0">Add Doctor</span>
                  </div>
                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"></h5>

                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Name</label>
                    <input type="text" id="docname" name="docname" class="form-control form-control-lg <?php echo (!empty($docname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $docname; ?>">
                    <span class="invalid-feedback"><?php echo $docname_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Email</label>
                  <input type="text" id="docemail" name="docemail" class="form-control form-control-lg <?php echo (!empty($docemail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $docemail; ?>">
                    <span class="invalid-feedback"><?php echo $docemail_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label>Password</label>
                    <input type="password" name="docpassword" class="form-control form-control-lg <?php echo (!empty($docpassword_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $docpassword; ?>">
                    <span class="invalid-feedback"><?php echo $docpassword_err; ?></span>
                  </div>
                  
                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Cellphone Number</label>
                    <input type="text" id="doctel" name="doctel" class="form-control form-control-lg <?php echo (!empty($doctel_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $doctel; ?>">
                    <span class="invalid-feedback"><?php echo $doctel_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="gender">Department</label><br>
         <select name="specialties" class="form-control form-control-lg <?php echo (!empty($specialties_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $specialties; ?>">
            <option disabled selected>Select department</option>
            <?php
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $sname = $row['sname'];
    echo "<option value='$id'>$sname</option>";
  } 
            ?>
          </select>
          <span class="invalid-feedback"><?php echo $specialties_err; ?></span>

                  </div>
                  <div class="pt-1 mb-4">
                    <input type="submit" class="btn btn-dark btn-lg" value="Submit"><br><br>
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



<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/js/main.js"></script>

</body>

</html>