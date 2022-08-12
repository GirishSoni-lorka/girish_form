<?php 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 
 
require 'PHPMailer/Exception.php'; 
require 'PHPMailer/PHPMailer.php'; 
require 'PHPMailer/SMTP.php'; 
 
$mail = new PHPMailer; 
 
$mail->isSMTP();
$mail->Mailer = "smtp";
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'lorka4141@gmail.com';    
$mail->Password = 'bxealovkcrmvjcru';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
 
$mail->setFrom('lorka4141@gmail.com', 'Girish'); 
$mail->addReplyTo('reply@example.com', 'SenderName'); 
 
$mail->addAddress($_POST['email']); 
 
$mail->isHTML(true); 
 
$mail->Subject = 'Verification Code'; 
 
$bodyContent = '<h1>Verification</h1>'; 
$bodyContent .= '<p>Your verification code is <b>'.$token.'</b></p>'; 
$mail->Body    = $bodyContent; 
 
if(!$mail->send()) { 
    echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
}
