<?php

error_reporting(E_ALL);
$a = false;
var_dump(isset($a[0]));
die;
for ($i = 0; $i < 2000; $i++) {
    sleep(2);
}
die;
include '14.php';
include_once '14.php';

class B {

    public function test() {
        
    }

}

//var_dump($_SERVER);
function myurlencode($str) {
    if (is_array($str)) {
        foreach ($str as $k => $v) {
            $str[$k] = myurlencode($v);
        }
    } else {
        return urlencode($str);
    }
    return $str;
}

$a = '反反复复';
$c = myurlencode($a);
var_dump($c);
die;
?>
