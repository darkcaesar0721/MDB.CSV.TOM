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

    $mail->setFrom('darkcaesar0721@gmail.com'); // This mail-id will be same as your gmail-id
    $mail->addAddress('morrispeter0311@gmail.com');      // Enter your reciever email-id

    $mail->isHTML(true);                       // Set email format to HTML
    
    $folder_name = $_REQUEST['folder_name'];
    $mail->Subject = $folder_name;

    if ($_REQUEST['action'] === 'shai1') {
        $folder_path = $_REQUEST['path'];
        $file_name = '00_ALL_' . $folder_name . '_CA Window Door.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name, $file_name);
    }
    if ($_REQUEST['action'] === 'palm1') {
        $folder_path = $_REQUEST['path'];
        $file_name = $folder_name . '_PALM.xls';
        $mail->AddAttachment($folder_path . '\\' . $file_name, $file_name);
    }
    
    $mail->Body = " ";

    $mail->send();
    echo "Mail has been sent successfully!";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>