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
    $mail->Username   = $_REQUEST['sender'];   // Enter your gmail-id
    $mail->Password   = $_REQUEST['password'];     // Enter your gmail app password that you generated
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom($_REQUEST['sender']); // This mail-id will be same as your gmail-id
    $mail->addAddress($_REQUEST['receiver']);      // Enter your reciever email-id

    $mail->isHTML(true);                       // Set email format to HTML
    
    $folder_name = $_REQUEST['folder_name'];
    $mail->Subject = $folder_name;

    if ($_REQUEST['action'] === 'shai1') {
        $folder_path = $_REQUEST['path'];
        $file_name = '00_ALL_' . $folder_name . '_CA Window Door.csv';
        $mail->AddAttachment($folder_path . '\\' . $file_name, $file_name);
        $mail->Body = " ";
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

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

        date_default_timezone_set('America/Los_Angeles');
        
        $count_path = $_REQUEST['count_path'];

        $a_counts = [
            0 => [
                'c' => '0 Shai - W D, CA',
                'k' => 'CA W, D',
                'i' => -1,
                'v' => 0,
            ],
            1 => [
                'c' => '1 Shai KBD',
                'k' => 'KBDR',
                'i' => -1,
                'v' => 0,
            ],
            2 => [
                'c' => '2 ALIT Shai LA',
                'k' => 'LA',
                'i' => -1,
                'v' => 0,
            ],
            3 => [
                'c' => '3 ALIT Shai SD',
                'k' => 'SD',
                'i' => -1,
                'v' => 0,
            ],
            4 => [
                'c' => '4 ALIT Shai WA',
                'k' => 'WA',
                'i' => -1,
                'v' => 0,
            ],
            5 => [
                'c' => '5 ALIT Shai BAY South',
                'k' => 'BAY S.',
                'i' => -1,
                'v' => 0,
            ],
            6 => [
                'c' => '6 ALIT Shai BAY Noth',
                'k' => 'BAY N.',
                'i' => -1,
                'v' => 0,
            ],
            7 => [
                'c' => '7 ALIT Shai OR',
                'k' => 'OR',
                'i' => -1,
                'v' => 0,
            ],
            8 => [
                'c' => '8 ALIT Shai TX',
                'k' => 'AUS',
                'i' => -1,
                'v' => 0,
            ],
            9 => [
                'c' => ' 9 ALIT Shai TX HOU',
                'k' => 'HOU',
                'i' => -1,
                'v' => 0,
            ],
            10 => [
                'c' => '10 ALIT Shai TX  DAL',
                'k' => 'DAL',
                'i' => -1,
                'v' => 0,
            ]
        ];


        $spreadsheet = $reader->load($count_path);
        $d = $spreadsheet->getSheet(0)->toArray();

        foreach($d as $r) {
            foreach($r as $i => $c) {
                foreach($a_counts as $j => $a_c) {
                    if ($a_c['c'] === $c) {
                        $a_counts[$j]['i'] = $i;
                    }
                }
            }
        }
        
        $sp = explode(' ', $folder_name);
        $date = substr($sp[0], 0, 2) . '/' . substr($sp[0], 2, 2) . '/' . substr($sp[0], 4, 4);
        $date_info = getDate(strtotime($date));
        $time = ($sp[1] == '8AM' ? '8am' : '2pm');
        
        if ($date_info['wday'] == 4) {
            $cur_index = -1;
            $cur_row = [];
            foreach ($d as $i => $row) {
                if (strtotime($row[0]) == strtotime($date) && strpos($row[1], "8am") > 0) {
                    $cur_index = $i; $cur_row = $row;
                }
            }
            if ($cur_index !== -1) {
                foreach($a_counts as $i => $a_c) {
                    $a_counts[$i]['v'] = $cur_row[$a_c['i']];
                }
            }
            if ($time == "2pm") {
                $cur_index = -1;
                $cur_row = [];
                foreach ($d as $i => $row) {
                    if (strtotime($row[0]) == strtotime($date) && strpos($row[1], "2pm") > 0) {
                        $cur_index = $i; $cur_row = $row;
                    }
                }
                if ($cur_index !== -1) {
                    foreach($a_counts as $i => $a_c) {
                        $a_counts[$i]['v'] = $a_counts[$i]['v'] . ' ' . $cur_row[$a_c['i']]; 
                    }
                }
            }
        } else {
            $cur_index = -1;
            $cur_row = [];
            foreach ($d as $i => $row) {
                if (strtotime($row[0]) == strtotime($date)) {
                    $cur_index = $i; $cur_row = $row;
                }
            }

            if ($cur_index !== -1) {
                foreach($a_counts as $i => $a_c) {
                    if ($time == "2pm") {
                        $a_counts[$i]['v'] = $cur_row[$a_c['i']];
                    } else {
                        $sp1 = explode(' ', $cur_row[$a_c['i']]);
                        $a_counts[$i]['v'] = $sp1[0];
                    }
                }
            }
        }

        $mail->Body = "<table border='0' cellpadding='0' cellspacing='0' width='726' style='border-collapse:collapse;width:539pt'>
                            <colgroup><col width='66' span='11' style='width:49pt'>
                            </colgroup>
                            <tbody>
                                <tr height='18' style='height:13.4pt'>
                                    <td height='18' width='66' style='height:13.4pt;width:49pt;font-size:9pt;font-weight:700;font-family:Calibri,sans-serif;text-align:center;border:1pt solid silver;padding-top:1px;padding-right:1px;padding-left:1px;color:windowtext;vertical-align:bottom'>". $a_counts[0]['v'] ."</td>";

        for ($i = 1; $i < 11; $i++) {
            $mail->Body .= "<td width='66' style='border-left:none;width:49pt;font-size:9pt;font-weight:700;font-family:Calibri,sans-serif;text-align:center;border-top:1pt solid silver;border-right:1pt solid silver;border-bottom:1pt solid silver;padding-top:1px;padding-right:1px;padding-left:1px;color:windowtext;vertical-align:bottom'>". $a_counts[$i]['v'] ."</td>";
        }
         
        $mail->Body .= "</tr>";
        $mail->Body .= "<tr height='18' style='height:13.4pt'>
                            <td height='18' width='66' style='height:13.4pt;border-top:none;width:49pt;font-size:9pt;font-weight:700;font-family:Calibri,sans-serif;text-align:center;border-right:1pt solid silver;border-bottom:1pt solid silver;border-left:1pt solid silver;padding-top:1px;padding-right:1px;padding-left:1px;color:windowtext;vertical-align:bottom'>". $a_counts[0]['k'] ."</td>";
        for ($i = 1; $i < 11; $i++) {
            $mail->Body .= "<td width='66' style='border-top:none;border-left:none;width:49pt;font-size:9pt;font-weight:700;font-family:Calibri,sans-serif;text-align:center;border-right:1pt solid silver;border-bottom:1pt solid silver;padding-top:1px;padding-right:1px;padding-left:1px;color:windowtext;vertical-align:bottom'>". $a_counts[$i]['k'] ."</td>";
        }
        $mail->Body .= "</tr></tbody></table>";
    }
    if ($_REQUEST['action'] === 'palm1') {
        $folder_path = $_REQUEST['path'];
        $file_name = $folder_name . '_PALM.xls';
        $mail->AddAttachment($folder_path . '\\' . $file_name, $file_name);
        $mail->Body = " ";
    }

    $mail->send();
    echo json_encode(array("status" => "success"));
} catch (Exception $e) {
    echo json_encode(array("status" => "error"));
}
?>