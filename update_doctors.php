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

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the input values
    if (empty($_POST["docemail"])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($_POST["docemail"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($_POST["doctel"])) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^[0-9]+$/', $_POST["doctel"])) {
        $errors[] = "Cellphone can only contain numbers.";
    }

    // If there are no validation errors, update the table
    if (empty($errors)) {
        // Retrieve the input values from the form
        $docid = $_POST["docid"];
        $docemail = $_POST["docemail"];
        $doctel = $_POST["doctel"];

        // Perform your database update query here
        $sql = "UPDATE doctor SET docemail = ?, doctel = ? WHERE docid = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $docemail, $doctel, $docid);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Update successful
            echo '<script>alert("Doctor details updated successfully.");</script>';
        } else {
            // Update failed
            echo '<script>alert("Failed to update doctor details.");</script>';
        }
    } elseif (count($errors) > 0) {
        // Validation errors exist
        $error_message = implode('\n', $errors);
        echo "<script>alert('$error_message');</script>";
    }
}

// Close the connection

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
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="assets/css/patient.css" rel="stylesheet">
  <!-- TIMER FUNCTION -->


</head>
<body>
<?php 
        if(!empty($update_err)){
          echo '<script>alert("'. $update_err.'")</script>';
        }        
        ?>
<script> 
function loadSelectedOption() {
  var selectElement = document.getElementsByName("specialty")[0];
  var selectedOption = selectElement.options[selectElement.selectedIndex].text;
  document.getElementById("selectedOptionText").value = selectedOption;
}
</script>

<?php include 'adminheader.php';?>
<script>
  function loadSelectedOption() {
    var selectedOption = document.getElementById("docdetails").value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var data = JSON.parse(this.responseText);
        populateFields(data);
      }
    };
    xmlhttp.open("GET", "get_data.php?option=" + selectedOption, true);
    xmlhttp.send();
  }
  
  function populateFields(data) {
    // Populate the input fields with the data received
    document.getElementById("docid").value = data.docid;
    document.getElementById("selectedOptionText").value = data.docname;
    document.getElementById("docemail").value = data.docemail;
    document.getElementById("doctel").value = data.doctel;
    document.getElementById("specialties").value = data.specialties;
  }
</script>
<main id="main">
  <section class="vh-110 mt-5">
  <div class="container py-10 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row gx-5">
            <div class="col-md-10 col-lg-10 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="patientlog">
                  <div class="d-flex align-items-center mb-3 pb-1 mr">
                    <span class="h1 fw-bold mb-0">Update Doctor</span>
                  </div>
                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"></h5>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="gender">Select Doctor that will be updated</label><br>
                    <select name="docdetails" id="docdetails" class="form-control-lg" onchange="loadSelectedOption()">
  <option disabled selected>Select Doctor</option>
  <?php
            $sql = "SELECT doctor.docid, doctor.docemail, doctor.docname, doctor.docpassword, doctor.doctel, specialties.sname
            FROM doctor
            JOIN specialties ON doctor.specialties = specialties.id;";
            $result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    $docid = $row['docid'];
    $docname = $row['docname'];
    echo "<option value='$docid'>$docid - $docname</option>";
  } 
            ?>
          </select>
          
          <!-- <input type="text" id="selectedOptionText" placeholder="Selected Option" readonly> -->

                  </div>    
                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Doctor ID</label>
                    <input type="text" id="docid" name="docid" readonly="readonly" class="form-control form-control-lg">
                  </div>
                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Name</label>
                    <input type="text" id="selectedOptionText" name="docname" readonly="readonly" class="form-control form-control-lg">
                  </div>

                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Email</label>
                  <input type="text" id="docemail" name="docemail"class="form-control form-control-lg">
                  </div>
                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Cellphone Number</label>
                    <input type="text" id="doctel" name="doctel"  class="form-control form-control-lg">
                  </div>

                  <div class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Department</label>
                    <input type="text" id="specialties" readonly="readonly" name="specialties" class="form-control form-control-lg">

                  </div>

    
                  <div class="pt-1 mb-4">
                    <input type="submit" class="btn btn-dark btn-lg" value="Update"><br><br>
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