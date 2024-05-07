    <?php
    $activePage = basename($_SERVER['PHP_SELF']);
    ?>

    <header id="header mb-5" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between ">
        <img src ="assets/img/logoName.png" width="250" height="60" >

    <nav id="navbar" class="navbar">
        <ul>  
            <li <?php if($activePage == 'all_history.php') echo 'class="active"'; ?>><a href="all_history.php">View All Appointment</a></li>
            <li <?php if($activePage == 'all_patient.php') echo 'class="active"'; ?>><a href="all_patient.php">View Patient Account</a></li>
            <li class="dropdown">
                <a href="#">
                    <span>Manage Doctors</span>
                    <i class="bi bi-chevron-down dropdown-indicator"></i>
                </a>
                <ul>
                    <li <?php if($activePage == 'add_doctors.php') echo 'class="active"'; ?>><a href="add_doctors.php">Add Doctors</a></li>
                    <li <?php if($activePage == 'update_doctors.php') echo 'class="active"'; ?>><a href="update_doctors.php">Update Doctor account</a></li>
                    <li <?php if($activePage == 'delete_doctors.php') echo 'class="active"'; ?>><a href="delete_doctors.php">Delete Doctor account</a></li>
                    <li <?php if($activePage == 'all_doctors.php') echo 'class="active"'; ?>><a href="all_doctors.php">View Doctors</a></li>
                </ul>
            </li>
            <li <?php if($activePage == 'adminreports.php') echo 'class="active"'; ?>><a href="adminreports.php">Overall report</a></li>
            <li class="dropdown">
                <a href="#">
                    <span>Profile</span>
                    <i class="bi bi-chevron-down dropdown-indicator"></i>
                </a>
                <ul>
                    <li <?php if($activePage == 'resetpassadmin.php') echo 'class="active"'; ?>><a href="resetpassadmin.php">Reset Password</a></li>
                    <li <?php if($activePage == 'logout.php') echo 'class="active"'; ?>><a href="logout.php">Log Out</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
    <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>



    </header>