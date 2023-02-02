<?php

$mdb_path = $_REQUEST['mdb_path'];
$csv_path = $_REQUEST['csv_path'];
$csv_previous_path = $_REQUEST['csv_previous_path'];
$xls_path = $_REQUEST['xls_path'];
$xls_previous_path = $_REQUEST['xls_previous_path'];

$folder = $_REQUEST['folder'];

$file_type = $_REQUEST['file_type'];
$time = $_REQUEST['time'];
$date = $_REQUEST['date'];
$date_str = $_REQUEST['date_str'];

$accdatabase = $mdb_path;

$a_csv = array(
    0 => array(
        'query' => "003a27_00a_Alit_CA Windows Doors  ------------------------  >>",
        'file' => "00_ALL_" . $date_str . " " . $time . "_CA Window Door shaisak@yahoo.com.csv",
    ),
    1 => array(
        'query' => "003a27_01_Alit_ALL_Kitchen Bathroom Decks",
        'file' => "01_ALL_" . $date_str . " " . $time . "_KitchenBathDecksRenovate.csv",
    ),
    2 => array(
        'query' => "003a27_02_Alit_LA",
        'file' => "02_LA_" . $date_str . " " . $time . ".csv",
    ),
    3 => array(
        'query' => "003a27_03_Alit_SD",
        'file' => "03_SD_" . $date_str . " " . $time . ".csv"
    ),
    4 => array(
        'query' => "003a27_04_Alit_WA",
        'file' => "04_WA_" . $date_str . " " . $time . ".csv"
    ),
    5 => array(
        'query' => "003a27_05_Alit_BAY South",
        'file' => "05_BAY_" . $date_str . " " . $time . " South.csv",

    ),
    6 => array(
        'query' => "003a27_06_Alit_BAY North",
        'file' => "06_BAY_" . $date_str . " " . $time . " North.csv"
    ),
    7 => array(
        'query' => "003a27_07_Alit_OR",
        'file' => "07_OR_" . $date_str . " " . $time . ".csv",
    ),
    8 => array(
        'query' => "003a27_08_Alit_Austin",
        'file' => "08_TX_Austin_" . $date_str . " " . $time . ".csv",
    ),
    9 => array(
        'query' => "003a27_09_Alit_Houston",
        'file' => "09_TX_Houston_" . $date_str . " " . $time . ".csv",
    ),
    10 => array(
        'query' => "003a27_10_Alit_Dallas",
        'file' => "10_TX_Dallas_" . $date_str . " " . $time . ".csv"
    )
);

$a_xls = array(
    0 => array(
        'query' => "003a10_Palm CON WA <<< PALM NEW>>>------------",
        'sheet' => "WA",
    ),
    1 => array(
        'query' => "003a11_Palm CON BAY",
        'sheet' => "BAY",
    ),
    2 => array(
        'query' => "003a12_Palm CON SD",
        'sheet' => "SD",
    ),
    3 => array(
        'query' => "003a13_Palm CON LA",
        'sheet' => "LA"
    ),
    4 => array(
        'query' => "003a13a_Palm CON FL",
        'sheet' => "FL"
    )
);

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
                            $a_csv[$index]['pre_phone'] = $data[5];
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

        foreach ($a_csv as $index => $csv) {
            $query = $csv['query'];
            $sth = $db->prepare("select * from [$query]");
            $sth->execute();

            $fp = fopen($folder_path . "\\" . $csv['file'], 'w');

            if (strpos($csv['file'], "10") === 0 || strpos($csv['file'], "09") === 0 || strpos($csv['file'], "08") === 0 || strpos($csv['file'], "05") === 0) {
                fputcsv($fp, array('Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Job Group1'));
            } else if (strpos($csv['file'], "06") === 0) {
                fputcsv($fp, array('Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Job Group1', 'County1'));
            } else if (strpos($csv['file'], "01") === 0) {
                fputcsv($fp, array('Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Job Group', 'County1'));
            } else {
                fputcsv($fp, array('Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Job Group'));
            }

            $a_csv[$index]['count'] = 0;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                if ($row['Phone'] === $csv['pre_phone']) break;

                if (strpos($csv['file'], "10") === 0 || strpos($csv['file'], "09") === 0 || strpos($csv['file'], "08") === 0 || strpos($csv['file'], "05") === 0) {
                    fputcsv($fp, array($row['Name'], $row['Address'], $row['City'], $row['State'], $row['Zip'], $row['Phone'], $row['Job Group1']));
                } else if (strpos($csv['file'], "06") === 0) {
                    fputcsv($fp, array($row['Name'], $row['Address'], $row['City'], $row['State'], $row['Zip'], $row['Phone'], $row['Job Group1'], $row['County1']));
                } else if (strpos($csv['file'], "01") === 0) {
                    fputcsv($fp, array($row['Name'], $row['Address'], $row['City'], $row['State'], $row['Zip'], $row['Phone'], $row['Job Group'], $row['County1']));
                } else {
                    fputcsv($fp, array($row['Name'], $row['Address'], $row['City'], $row['State'], $row['Zip'], $row['Phone'], $row['Job Group']));
                }

                $a_csv[$index]['count'] = $a_csv[$index]['count'] + 1;
            }
            fclose($fp);
        }

        $csv_previous_path = $csv_path . "\\" . $folder;
        if ($file_type === 'csv') {
            echo json_encode(array('status' => 'success', 'csv_previous_path' => $folder_path, 'xls_previous_path' => $xls_previous_path));
            exit;
        }
    }
    if ($file_type !== 'csv'){
        require 'vendor/autoload.php';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

        if (file_exists($xls_previous_path)) {
            $files = glob($xls_previous_path . "\\" . "*.xls");

            $spreadsheet = $reader->load($files[0]);

            foreach($a_xls as $index => $xls) {
                $d = $spreadsheet->getSheet($index)->toArray();
                $a_xls[$index]['pre_phone'] = $d[1][1];
            }
        } else {
            echo json_encode(array('status' => 'error', 'description' => 'CSV previous download file path wrong'));
            exit;
        }

        $folder_path = $xls_path . "\\" . $folder . "\\";

        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
        }

        $mySpreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        $mySpreadsheet->removeSheetByIndex(0);

        $worksheets = [];
        foreach ($a_xls as $index => $xls) {
            $query = $xls['query'];
            $sth = $db->prepare("select * from [$query]");
            $sth->execute();

            $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, $xls['sheet']);
            $mySpreadsheet->addSheet($worksheet, $index);

            $data = [];
            if ($index === 3)
                array_push($data, ['Date', 'Phone', 'Name', 'Address', 'City', 'State', 'Zip', 'Job Group', 'COUNTY.COUNTY']);
            else
                array_push($data, ['Date', 'Phone', 'Name', 'Address', 'City', 'State', 'Zip', 'Job Group', 'COUNTY']);

            $a_xls[$index]['count'] = 0;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                if ($row['Phone'] == $xls['pre_phone']) break;

                if ($index === 3)
                    array_push($data, [$row['Date'], $row['Phone'], $row['Name'], $row['Address'], $row['City'], $row['State'], $row['Zip'], $row['Job Group'], $row['COUNTY.COUNTY']]);
                else
                    array_push($data, [$row['Date'], $row['Phone'], $row['Name'], $row['Address'], $row['City'], $row['State'], $row['Zip'], $row['Job Group'], $row['COUNTY']]);

                $a_xls[$index]['count'] = $a_xls[$index]['count'] + 1;
            }

            $worksheet->fromArray($data);
            array_push($worksheets, $worksheet);
        }

        foreach ($worksheets as $worksheet)
        {
            foreach ($worksheet->getColumnIterator() as $column)
            {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        $mySpreadsheet->setActiveSheetIndex(0);

        // Save to file.
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($mySpreadsheet);
        $writer->save($folder_path . "\\" . $date_str . " " . $time . '_PALM.xls');

        $xls_previous_path = $xls_path . "\\" . $folder;
        echo json_encode(array('status' => 'success', 'csv_previous_path' => $csv_previous_path, 'xls_previous_path' => $xls_previous_path));
        exit;
    }
}

catch(PDOException $e) {
    echo json_encode(array('status' => 'error', 'description' => 'mdb file path wrong'));
    exit;
}
