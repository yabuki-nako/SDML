<?php
    $activePage = basename($_SERVER['PHP_SELF']);
?>
<header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <div class="headercont">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <img src ="assets/img/logoName.png" width="250" height="60" >
  </div>
  <nav id="navbar" class="navbar">
        <ul>  
          <!-- <li ><a href="welcomedoctor.php">Home</a></li> -->
          <li <?php if($activePage == 'Doctor_Appointment_List.php') echo 'class="active"'; ?>><a href="Doctor_Appointment_List.php">Home</a></li>
          <li <?php if($activePage == 'Doctor_Patient_List.php') echo 'class="active"'; ?>><a href="Doctor_Patient_List.php">Patient List</a></li>
          <li <?php if($activePage == 'medicalhistory.php') echo 'class="active"'; ?>><a href="medicalhistory.php">Appointment History</a></li>
          <li class="dropdown"><a href="#"><span>Profile</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li ><a data-bs-toggle="modal" data-bs-target="#userdetail">View Account details</a></li> 

              <li <?php if($activePage == 'docpassreset.php') echo 'class="active"'; ?>><a href="docpassreset.php">Reset Password</a></li>    
              <li <?php if($activePage == 'logout.php') echo 'class="active"'; ?>><a href="logout.php">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </nav>

 
      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>


</header>
<!-- <nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown link
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav> -->