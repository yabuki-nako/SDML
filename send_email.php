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
$docname = $_POST['docname'];
$appDate = $_POST['appDate'];
$Time_schedule = $_POST['Time_schedule'];

// Create a PHPMailer instance
$mail = new PHPMailer();
$mail->SMTPDebug = 2; // Enable debugging
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
$mail->Subject = 'Appointment Confnirmation';
// $mail->Body = "Appointment ID: $appointmentID\nPatient Name: $pname\nAppointment Date: $appDate\nTime Schedule: $Time_schedule";
$mail->Body = "We are pleased to confirm your appointment at St. Dominic Medical Laboratory and Drug Testing Center located at 146 Manila S Rd, Calamba, 4027 Laguna.

Appointment Details:

Date: $appDate
Time: $Time_schedule
Doctor: Dr. $docname

Please arrive 10 minutes prior to your scheduled time. 

If you need to reschedule or have any questions regarding your appointment, feel free to contact us at 0918 935 3547 or email us at saintdominiclaboratory@gmail.com

We look forward to seeing you.
<img src='cid:footer' alt='St. Dominic Medical Laboratory'>";
$mail->AddEmbeddedImage('assets\img\emailPic.jpg','footer', 'Picture');





// Send email
if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>