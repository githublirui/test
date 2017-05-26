<?php
$a = array(
    'image' => array("jpeg", "gif", "png"),
    'audio' => array("amr"),
);
$s = array_values($a);
var_dump($s);
die;
define('NOW_FILE_PATH', dirname(__FILE__));
$file = NOW_FILE_PATH . '/userid.csv';
$fileHandle = fopen($file, 'r+');
$userRecords = array();
$i = 0;
while (!feof($fileHandle)) {
    $userRecords = trim(fgets($fileHandle));
    if ($i != 0) {
        $userRecordsArr = explode(",", $userRecords);
        $userRecordstrs = implode("\n", $userRecordsArr);
        file_put_contents(NOW_FILE_PATH . '/userid', $userRecordstrs . "\n", FILE_APPEND);
        echo $i . "\n";
    }
    $i++;
}
fclose($fileHandle);
