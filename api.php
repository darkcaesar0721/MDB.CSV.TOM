<?php

$mdb_path = $_REQUEST['mdb_path'];
$csv_path = $_REQUEST['csv_path'];
$csv_previous_path = $_REQUEST['csv_previous_path'];
$xls_path = $_REQUEST['xls_path'];
$xls_path = $_REQUEST['xls_path'];
$xls_previous_path = $_REQUEST['xls_previous_path'];

$folder = $_REQUEST['folder'];

$file_type = $_REQUEST['file_type'];
$time = $_REQUEST['time'];
$date = $_REQUEST['date'];
$date_str = $_REQUEST['date_str'];

$accdatabase = $mdb_path;

$csv_previous_info = array();

try {
    # OPEN BOTH DATABASE CONNECTIONS
    $db = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)}; DBq=$accdatabase;Uid=;Pwd=;");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($file_type !== 'xls') {
        //get previous csv download information
        if (file_exists($csv_previous_path)) {
            $files = glob($csv_previous_path . "\\" . "*.csv");

            foreach($files as $index => $file) {
                if (($handle = fopen($file, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 4096, ",")) !== FALSE) {
                        if ($data[5] !== 'Phone') {
                            $csv_previous_info[$index] = $data[5];
                            break;
                        }
                    }
                    fclose($handle);
                } else {
                    echo json_encode(array('status' => 'warning', 'description' => "Can't open CSV previous download file"));
                    exit;
                }
            }
        } else {
            echo json_encode(array('status' => 'error', 'description' => 'CSV previous download file path wrong'));
            exit;
        }

        $folder_path = $csv_path . "\\" . $folder . "\\";

        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
        }

        $csv_query_to_csv = array(
            '003a27_00a_Alit_CA Windows Doors  ------------------------  >>' => "00_ALL_" . $date_str . " " . $time . "_CA Window Door shaisak@yahoo.com.csv",

        );

        foreach ($csv_query_to_csv as $query => $file_name) {
            $sth = $db->prepare("select * from [$query]");
            $sth->execute();

            $fp = fopen($folder_path . "\\" . $file_name, 'w');
            fputcsv($fp, array('Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Job Group'));
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                if ($row['Phone'] === $csv_previous_info[0]) break;

                fputcsv($fp, array($row['Name'], $row['Address'], $row['City'], $row['State'], $row['Zip'], $row['Phone'], $row['Job Group']));
            }
            fclose($fp);
        }
    } else {

    }
}

catch(PDOException $e) {
    echo json_encode(array('status' => 'error', 'description' => 'mdb file path wrong'));
    exit;
}
