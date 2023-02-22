<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$mail = new PHPMailer();
try {
    $mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com;';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'darkcaesar0721@gmail.com';   // Enter your gmail-id
    $mail->Password   = 'ckpbxluawnbtaqjz';     // Enter your gmail app password that you generated
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('darkcaesar0721@gmail.com', 'darkcaesar0721'); // This mail-id will be same as your gmail-id
    $mail->addAddress('blueberrypowerful2@gmail.com', 'darkcaesar');      // Enter your reciever email-id

    $mail->AddAttachment("C:\\test\\about.txt", "test.txt");
    // $mail->AddAttachment("about.txt", "send.txt");
    $mail->isHTML(true);                       // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'AAAAA is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo "Mail has been sent successfully!";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>