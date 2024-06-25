<?php
$activePage = basename($_SERVER['PHP_SELF']);
?>

<header id="header" class="header d-flex align-items-center" style="background-color: #FFFFFF;">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <div class="headercont">
        <img src ="assets/img/logoName.png" width="250" height="70" >
  </div>
      <nav id="navbar" class="navbar" >
        <ul>  
          <li <?php if($activePage == 'welcomepatient.php') echo 'class="active"'; ?>><a href="welcomepatient.php">Home</a></li>
          <li <?php if($activePage == 'booking.php') echo 'class="active"'; ?>><a href="booking.php">Appointment booking</a></li>
          <li <?php if($activePage == 'apphistory.php') echo 'class="active"'; ?>><a href="apphistory.php">Appointment History</a></li>
          <li class="dropdown"><a href="#"><span>Profile</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a data-bs-toggle="modal" data-bs-target="#userdetail">View Account details</a></li> 

              <li ><a href="resetpass.php">Reset Password</a></li>    
              <li><a href="logout.php">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </nav>


      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
<i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
</header>



