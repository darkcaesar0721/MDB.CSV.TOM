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
    if ($_REQUEST['action'] === 'shai2') {
        $folder_path = $_REQUEST['path'];
        $file_name1 = '01_ALL_' . $folder_name . '_KitchenBathDecksRenovate.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name1, $file_name1);

        $file_name2 = '02_LA_' . $folder_name . '.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name2, $file_name2);

        $file_name3 = '03_SD_' . $folder_name . '.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name3, $file_name3);

        $file_name4 = '04_WA_' . $folder_name . '.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name4, $file_name4);

        $file_name5 = '05_BAY_' . $folder_name . ' South.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name5, $file_name5);

        $file_name6 = '06_BAY_' . $folder_name . ' North.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name6, $file_name6);

        $file_name7 = '07_OR_' . $folder_name . '.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name7, $file_name7);

        $file_name8 = '08_TX_Austin_' . $folder_name . '.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name8, $file_name8);

        $file_name9 = '09_TX_Houston_' . $folder_name . '.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name9, $file_name9);

        $file_name10 = '10_TX_Dallas_' . $folder_name . '.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name10, $file_name10);
    }
    if ($_REQUEST['action'] === 'palm1') {
        $folder_path = $_REQUEST['path'];
        $file_name = $folder_name . '_PALM.xls';
        $mail->AddAttachment($folder_path . '\\' . $file_name, $file_name);
    }
    
    $mail->Body = " ";

    $mail->send();
    echo json_encode(array("status" => "success"));
} catch (Exception $e) {
    echo json_encode(array("status" => "error"));
}
?>