<?php

$export_name = 'email';
set_time_limit(0);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . $export_name . ".csv\"");

$data[] = array('姓名', 'First Name', '电子邮件');

foreach ($valid_emails as $valid_email) {
    $data[] = array($valid_email[0], '', $valid_email[1]);
}

$tmpfile = tempnam(sys_get_temp_dir(), '');

if (!$tmpfile) {
    exit;
}

Utils::toCsv($tmpfile, $data);

echo file_get_contents($tmpfile);
unlink($tmpfile);

exit;
?>