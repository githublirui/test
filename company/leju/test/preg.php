<?php

//测试
//$a = "<ul>
//        <li>你好</li>
//        <li>我是</li>
//        <li>李睿</li>
//        </ul>";
//$pattern2 = "/<ul>\s+(<li(?:.*)>(.*?)<\/li>)\s+<\/ul>/is";
//preg_match_all($pattern2, $a, $matches2);
//var_dump($matches2);
//die;

$str = 'industry adflakfl industries industry';
//$reg = '/industry|industries/i';
//$reg = '/industr(?:y|ies)/i'; //?: 匹配 pattern 但不获取匹配结果
//preg_match_all($reg, $str, $res);
//echo'<pre>';
//print_r($res);
//echo'</pre>';

$str = 'Windows 3.1 Windows 2000 Windows xp';
$reg = '/Windows (?!95|98|NT|2000|xp)/i';
preg_match_all($reg, $str, $res);
echo'<pre>';
print_r($res);
echo'</pre>';
//
//$str = 'aaaa12';
//$reg = '/^(?=[a-z])[a-z0-9]+$/i';
//preg_match_all($reg, $str, $res);
//echo'<pre>';
//print_r($res);
//echo'</pre>';
//
//$str = 'aaaa12';
//$reg = '/(?![a-z])[a-z0-9]+$/i';
//preg_match_all($reg, $str, $res);
//echo'<pre>';
//print_r($res);
//echo'</pre>';