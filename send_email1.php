<?php
require_once "assets/PHPMailer/src/PHPMailer.php";
require_once "assets/PHPMailer/src/SMTP.php";
require_once "assets/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Collect data sent via POST
$appointmentID = $_POST['appointmentID'];
$pname = $_POST['pname'];
$email = $_POST['email'];
$appDate = $_POST['appDate'];
$Time_schedule = $_POST['Time_schedule'];

// Create a PHPMailer instance
$mail = new PHPMailer();
$mail->SMTPDebug = 0; // Enable debugging
$mail->Debugoutput = 'html'; 
// Configure SMTP
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // Your SMTP host
$mail->SMTPAuth = true;
$mail->Username = 'Saint.Dominic4027@gmail.com'; // SMTP username
$mail->Password = 'nteu yvvf bben sfed'; // SMTP password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Set email parameters
$mail->setFrom('Saint.Dominic4027@gmail.com', 'Clinic Test'); // Sender's email and name
$mail->addAddress($email, $pname); 
$mail->Subject = 'Cancelled Appointment';
$mail->Body = " CANCELLLEEEDDDDDD Appointment ID: $appointmentID\nPatient Name: $pname\nAppointment Date: $appDate\nTime Schedule: $Time_schedule";

// Send email
if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>