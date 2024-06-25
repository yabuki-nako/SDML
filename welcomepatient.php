<?php
// Initialize the session
session_start();
require_once "config.php";
// time and date
$today = date("F j, Y ");
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$sql = "SELECT DISTINCT specialties.sname
FROM doctor
JOIN specialties ON doctor.specialties = specialties.id;";
$result = $mysqli->query($sql);

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
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/owlcarousel/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/css/header.css" rel="stylesheet">
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

<?php include 'patientheader.php';?>
<section class="vh-110">
<br><br><br>
    <div class="row d-flex justify-content-center align-items-center h-50" style="color: white;" >

      <div class="col col-xl-10">

        <div class="mt-2 mb-3">
              <h2><b>Today's Date and Time:</b>
              <div class="d-flex"> <?php echo $today?>&nbsp;||&nbsp;<div id="time"></h2>
            </div>

         
        </div>
      </div>
    </div>
 
    <div class="row d-flex justify-content-center align-items-center h-50"  style="color: white;">

      <div class="col col-xl-10">

             
              
      </div>
    </div>

  <div class ="mb-5">
    <div class="row d-flex justify-content-center align-items-center h-100" data-aos="fade-up">>
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem; background-color: white;">
        <div class="card-body p-4 p-lg-12">
        <span class="h2 mb-0">Hi <B><?php echo htmlspecialchars($_SESSION["pname"]); ?></b>, view your upcoming appointment below:</span>
          <hr class="app"></hr>
         
<?php
$pId = $_SESSION['id'];
$pending = "Pending";
$accepted = "accepted";
$sql3 = "SELECT appointments.appointment_ID, doctor.docname, specialties.sname, patient_detail.pId, patient_detail.pname,doctor.medtech,
appointments.appDate, appointments.appTime, docsched.time_schedule, appointments.App_status, appointments.service1, appointments.service2, appointments.service3
FROM appointments
INNER JOIN doctor ON appointments.docid = doctor.docid
INNER JOIN specialties ON doctor.specialties = specialties.id
INNER JOIN patient_detail ON appointments.pId = patient_detail.pId
INNER JOIN docsched ON appointments.appTime = docsched.appTime
WHERE patient_detail.pId = $pId AND (App_status = '$pending' OR App_status = '$accepted') ORDER BY appointments.appDate ASC";
$result3 = $mysqli->query($sql3);

if ($result3->num_rows > 0) {
  while ($row = $result3->fetch_assoc()) {
    echo    ' <div class="infocontainer">';
    echo '<div class="time">'.date("F j, Y", strtotime($row['appDate']))."</div>";
    echo '<div class="time1">'.$row['time_schedule']."</div>";
    echo '<div class="row mt-3">
    <div class="col">
    <h5>Doctor Name: </h5>
    <h4>'.$row["docname"].'</h4>
    </div>
    <div class="col">
    <h5>Appointment ID:</h5>
    <h4>'.$row["appointment_ID"].'</h4>
    </div>
    </div> 
    <div class="row">
    <div class="col">
    <h5>Department: </h5>
    <h4>'.$row["sname"].'</h4> 
    </div>
    <div class="col">
    <h5>Status:</h5>
    <h4>'.$row["App_status"].'</h4> 
    </div>
    <div class="row">
    <div class="col">' ;
    if ($row['medtech'] == 1):
      echo '<h5>Service/s Availed: </h5>';
      echo '<h4>'.$row['service1'];
      if (!empty($row['service2'])) {
          echo ', <br>'.$row['service2'];
      }
      if (!empty($row['service3'])) {
          echo ',<br> '.$row['service3'];
      }
      echo '</h4>';
  endif;

    echo '</div></div></div> </div>';

  }
} else {
  echo "<center><h4>You don't have any Appointment</h4></center>";
}

?>

</div>
          <!-- <div class="card-body p-4 p-lg-12">
                <h3> Your Upcoming Appointment</h3> -->
                <!-- <hr class="app"></hr> -->
                <!-- <div class=infocontainer> -->
                    <!-- <h2>July 15 2023</h2> -->
                    <!-- <h3>8:00PM-10:00PM</h3> -->
                    <!-- <div class="row mt-3">
    <div class="col">
    <h5>Doctor Name: </h5>
    <h4>Apple Green</h4> -->
<!-- 
    </div>
    <div class="col">
    <h5>Appointmend ID:</h5>
    <h4>1</h4>
    </div>
  </div>
  <div class="row">
    <div class="col">
    <h5>Department: </h5>
    <h4>Child Health</h4> 

    </div>
    <div class="col">
    <h5>Status:</h5>
    <h4>Pending</h4> 
    </div>-->
  </div>
                  </div>
         
        </div>
        
      </div>
    </div>
    
  </div>

  <div class="mb-5">
  <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col col-xl-10">
      <div class="card" style="border-radius: 1rem; background-color: #fcfcfc">
        <div class="card-body p-4 p-lg- text-black"  data-aos="fade-up">
          <strong><h3 style="text-align: center">Our Doctors</h3></strong>
          <hr class="app">
          <form action="" method="post" id="drsearch">
          <select name="specialty" class="form-control-lg mb-3"> 
            <option disabled selected>Select department</option>
            <?php
            while ($row = $result->fetch_assoc()) {
              $sname = $row['sname'];
              echo "<option value='$sname'>$sname</option>";
            }
            ?> </select>
          <div class="col-5 mb-3 ml-5">
          <div class="butMargin">
                <input type="submit" name="filter" class="btn btn-dark btn-lg ml-5" value="Search">
          </div>
          </div>
                    </form>
          
          
          <div class="row gy-3 ">
          <?php

          if (isset($_POST['specialty'])) {
            $selectedSpecialty = $_POST['specialty'];
            $sql2 = "SELECT doctor.docid, doctor.docemail, doctor.docname, doctor.doctel, specialties.sname
            FROM doctor
            JOIN specialties ON doctor.specialties = specialties.id
            WHERE sname = '".$selectedSpecialty."' and delete_status IS NULL OR delete_status = 0 ";
            $result2 = $mysqli->query($sql2);
            for ($x = 0; $x < $result2->num_rows; $x++) {
              $row = $result2->fetch_assoc();
              $docname = $row["docname"];
              $docemail = $row["docemail"];
              $doctel = $row["doctel"];
              $sname = $row["sname"];
              echo '<div class="col-3">
                      <div class="doctext">
                          <img src="assets/img/docimg.jpg" class="img-fluid">
                          <h3>'.$docname.'</h3>
                          <h5>Specialty</h5><h4>'.$sname.'</h4>

                        </div>
                      </div>';
            }
          }
          ?>
            </div>
            <!-- row -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  
  <!-- Modals -->
  <!-- Modal patient detail-->
  <div class="modaltext">
<div class="modal fade" id="userdetail" tabindex="-1" aria-labelledby="userdetail" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="userdetail">Account Details</h1>
      </div>
      <div class="modal-body">
      <h5>Name</H5><h3 ><?php echo htmlspecialchars($_SESSION["pname"]); ?></h3>
      <h5 class ="mt-3">Email</H5><h3 ><?php echo htmlspecialchars($_SESSION["email"]); ?></h3>
      <h5 class ="mt-3">Cellphone Number</H5><h3 ><?php echo htmlspecialchars($_SESSION["pcellphone"]); ?></h3>
      <h5 class ="mt-3">Gender</H5><h3 ><?php echo htmlspecialchars($_SESSION["pGender"]); ?></h3>
      <h5 class ="mt-3">Birthday</H5><h3 ><?php echo htmlspecialchars($_SESSION["pBirthday"]); ?></h3>
      <h5 class ="mt-3">Address</H5><h3 ><?php echo htmlspecialchars($_SESSION["pAddress"]); ?></h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
      </div>
      <div class="mb-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col col-xl-10">
    <!-- <div class="card" >   -->
      <!-- ======= Our Services Section ======= -->
<!-- ======= Our Services Section ======= -->
<section id="services" class="services sections-bg" style="border-radius: 20px;">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Our Services</h2>
          <p>Welcome to our clinic services! Our clinic is dedicated to providing comprehensive healthcare to individuals of all ages and backgrounds. We offer a wide range of services designed to promote your well-being and address your healthcare needs. Whether you require routine medical care, preventive services, specialized treatments, or diagnostic procedures, our team of skilled healthcare professionals is here to assist you.</p>
        </div>

        <div class="row gy-4" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-4 col-md-6">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="bi bi-clipboard2-pulse"></i>
              </div>
              <h3>Laboratory Tesing </h3>
              <p>Provides dependable and professional testing solutions for both individuals and organizations. Our facilities are equipped with the latest technology and staffed with experienced professionals committed to providing accurate and timely results. From routine screenings to specialized tests, we prioritize precision and confidentiality to ensure the best service possible.</p>
              
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-heart-pulse"></i>
              </div>
              <h3>X-Ray</h3>
              <p>
              Provides high-quality diagnostic imaging solutions designed to meet the needs of both patients and healthcare professionals. Our modern facilities feature advanced technology and experienced staff dedicated to delivering accurate and timely results.</p>
              
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-bandaid"></i>
              </div>
              <h3>Drug Testing</h3>
              <p>A reliable and professional drug screening solution for both individuals and organizations. Our facilities are equipped with the latest technology and staffed with experienced professionals committed to providing accurate and timely results. From pre-employment screenings to random drug testing, we prioritize precision and confidentiality to ensure the best service possible.</p>
              
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-lungs"></i>
              </div>
              <h3>Annual Physical Examination</h3>
              <p>Offers comprehensive and professional health check-ups for individuals and organizations. Our facilities are equipped with the latest technology and staffed with experienced professionals committed to providing accurate and timely results. From routine check-ups to specialized tests, we prioritize precision and confidentiality to ensure the best service possible.</p>
              
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-prescription"></i>
              </div>
              <h3>ECG</h3>
              <p>A professional and reliable heart health assessment. Our facilities are equipped with the latest technology and staffed with experienced professionals committed to providing accurate and timely results. From routine ECGs to specialized cardiac tests, we prioritize precision and patient care to ensure the best service possible.</p>
              
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-chat-square-text"></i>
              </div>
              <h3>Blood Chemistry</h3>
              <p>Provides a professional and comprehensive analysis of key blood matters. Our facilities are equipped with the latest technology and staffed with experienced professionals committed in giving personalized reports, and provide invaluable insights. Take control of your well-being today.

</p>
              
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>
      
  <br><br>
   <!-- ======= Frequently Asked Questions Section ======= -->
  <section id="faq" class="faq">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4">

          <div class="col-lg-4">
            <div class="content px-xl-5">
              <h3>Frequently Asked <strong>Questions</strong></h3>
            </div>
          </div>

          <div class="col-lg-8">

            <div class="accordion accordion-flush" id="faqlist" data-aos="fade-up" data-aos-delay="100">

              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-1">
                    <span class="num">1.</span>
                    How do I schedule an appointment?
                  </button>
                </h3>
                <div id="faq-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                  <div class="accordion-body">
                  Scheduling an appointment is easy. You can either call our office at 09123456789 or use our online appointment booking system <a href="login.php">click here</a>. 
                  </div>
                </div>
              </div><!-- # Faq item-->

              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-2">
                    <span class="num">2.</span>
                    Do you offer same-day appointments for urgent medical needs?
                  </button>
                </h3>
                <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                  <div class="accordion-body">
                  Yes, we accommodate urgent medical needs by offering same-day appointments whenever possible. Please call our office as early as possible to check for availability, and our staff will do their best to schedule you in promptly.
                </div>
                </div>
              </div><!-- # Faq item-->

              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-3">
                    <span class="num">3.</span>
                    What payment methods do you accept?
                  </button>
                </h3>
                <div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                  <div class="accordion-body">
                  We accept various payment methods, including cash, credit/debit cards, and E-Wallet Payment (Gcash/Maya).Please inquire with our billing department for more information
                </div>
                </div>
              </div><!-- # Faq item-->

              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-4">
                    <span class="num">4.</span>
                    How can I access my medical records?                  
                  </button>
                </h3>
                <div id="faq-content-4" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                  <div class="accordion-body">
                  You can access your medical records through our secure patient portal. Simply log in to view your medication history, and visit summaries. If you encounter any issues or need assistance accessing your records, our staff will be happy to help you.
                </div>
                </div>
              </div><!-- # Faq item-->

              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-5">
                    <span class="num">5.</span>
                    What safety measures are in place at your clinic?
                  </button>
                </h3>
                <div id="faq-content-5" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                  <div class="accordion-body">
                  Your safety is our top priority. We have implemented strict cleaning and disinfection protocols throughout our clinic. All staff members wear face masks, and we enforce social distancing in waiting areas. 
                </div>
                </div>
              </div><!-- # Faq item-->

            </div>

          </div>
        </div>

      </div>
    </section><!-- End Frequently Asked Questions Section -->
  </div>
</div>
</div>
</div> 
        </section>
</body>
<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/owlcarousel/owl.carousel.js"></script>
<script src="assets/owlcarousel/owl.carousel.min.js"></script>


<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>