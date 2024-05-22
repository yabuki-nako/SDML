<?php
require_once "assets/PHPMailer/src/PHPMailer.php";
require_once "assets/PHPMailer/src/SMTP.php";
require_once "assets/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Collect data sent via POST
$appointmentID = $_POST['appointmentID'];
$pname = $_POST['pname'];
$docname = $_POST['docname'];
$email = $_POST['email'];
$appDate = $_POST['appDate'];
$sname = $_POST['sname'];
$service1 = $_POST['service1'];
$service2 = $_POST['service2'];
$service3 = $_POST['service3'];
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
$mail->Body = "We regret to inform you that due to unforeseen circumstances, your upcoming appointment has been cancelled. We apologize 
for any inconvenience this may cause and are committed to rescheduling your appointment at a convenient time for you.

Appointment Details:

Date: $appDate
Time: $Time_schedule
";

if($service1 == null && $service2 == null && $service3 == null) {
$mail->Body .= "Services Scheduled: 
$sname
Our team will reach out to assist you scheduling a new appointment. 
If you prefer, you can contact us directly at 0918 935 3547 
or email at us saintdominiclaboratory@gmail.com to discuss alternative dates and times.

We apologize for any inconvenience and appreciate your understanding.

Thank you for your patience and cooperation.";
} else {
$mail->Body .= "Services Scheduled: 
$service1 
$service2 
$service3

Our team will reach out to assist you scheduling a new appointment. 
If you prefer, you can contact us directly at 0918 935 3547 
or email at us saintdominiclaboratory@gmail.com to discuss alternative dates and times.

We apologize for any inconvenience and appreciate your understanding.

Thank you for your patience and cooperation.";
}

$mail->AddEmbeddedImage('assets\img\emailPic.jpg','footer', 'Picture');

// Send email
if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>