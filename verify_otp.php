<?php
session_start();
$alert = "Invalid OTP";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate OTP
    if (isset($_SESSION['otp']) && isset($_POST['otp']) && $_POST['otp'] == $_SESSION['otp']) {
        // Check if OTP is expired
        if (isset($_SESSION['otp_time']) && ($current_time - $_SESSION['otp_time']) <= (15 * 60)) { // 1 minutes * 60 seconds
            // OTP is valid, set a flag indicating it's correct
            $_SESSION['otp_verified'] = true;

            // Redirect to welcomepatient.php after successful OTP verification
            header("location: welcomepatient.php");
            exit; // Don't forget to exit after redirecting
        } else {
            // OTP is expired
            echo "<script type='text/javascript'>alert('OTP has expired. Please request a new one.');</script>";
        }
    } else {
        // Invalid OTP
        // echo "<script type='text/javascript'>alert('Invalid OTP');</script>";
    }
}
?>