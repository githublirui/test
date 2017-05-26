<?php

$str = 'grey';
$pattern = '/gr[ea]y/i';

//[a-z0-9] = [0-9a-z] 顺序无关
//[a-z0-9_.?] 起作用的只有-
// ^$代表开始和结束,^在字符组内可以代表排除

$str = 'agrey';
$pattern = '/gr[^y]+/i';

$str = 'From:aaa';
$str = 'subject:aaa';
$pattern = '/^(?:From|Subject|Date)/i';

//$str = 'July Fourth 4th';
//$pattern = '/July/';
// ? 出现一次或者未出现
// + 至少出现一次，可无限次
// * 出现任意次数，可以未出现
//{n,m} 出现次数在n,m之间

$str = 'subject":aaa"';
$pattern = '/"[^"]*"/i';


$str = '$31.33';
$pattern = '/\$[0-9]+(\.[0-9])/i';

$str = '11:33';
$pattern = '/0[0-9]|1[0-2]:[0-5][0-9]/i'; //12小时制
$pattern = '/[01][0-9]|2[0-4]:[0-5][0-9]/i'; //24小时制
//环视匹配,


$str = 'abc,abcd';
$pattern = '/(?=a)/i'; //12小时制
preg_match_all($pattern, $str, $matches);
var_dump($matches);
?>
