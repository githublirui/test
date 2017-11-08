<?php

$file = 'G:/GoogleDrive/test/company/51talk/2017/8/decode/site.php';  //要破解的文件


$fp = fopen($file, 'r');
$str = fread($fp, filesize($file));
fclose($fp);

//copy($file, '0_' . $file);

$n = 1;
while ($n < 10) {
    $code = strdecode($str);
   
    if ($n == 1) {
        $code = str_replace("__FILE__", "'0_$file'", $code);
    }
//    var_dump($code);
//    die;
    $replace = '$decode' . $n . '=trim';
    
//    if (strpos($code, 'eval(') > 0) {
//        $code = str_replace('eval(', $replace . '(', $code);
//    } else {
//        preg_match("/@//$(.*)/(//$(.*),(.*)/(/isU", $code, $res);
//        $code = str_replace($res[3], "'$replace", $code);
//    }

//    $code = preg_replace('///$(.*)=false;(.*?)/(/);/', '', $code); //上一版本
//    $code = preg_replace('//|/|@//$(.*?)/(/);/', '|| print("ok");', $code);

    $code = destr($code);
    var_dump($code);
    die;
    $tmp_file = 'detmp' . $n . '.php';
    file_put_contents($tmp_file, $code);
    include($tmp_file);

    $val = 'decode' . $n;
    $str = $$val;

    unlink($tmp_file);

    if (strpos($str, ';?>') === 0) {
        $decode = $str;
        break;
    }

    $str = "<?php/r/n" . $str;
    $n++;
}


$decode = preg_replace("/^(.*)exit/('Access Denied'/); /", "<?php/r/n", $decode);
$del = strrchr($decode, 'unset');
$decode = str_replace($del, "/r/n?>", $decode);
file_put_contents($file . '.de.php', $decode);
unlink('0_' . $file);
echo 'done';

////////////
function val_replace($code, $val, $deval) {
    $code = str_replace('$' . $val . ',', '$' . $deval . ',', $code);
    $code = str_replace('$' . $val . ';', '$' . $deval . ';', $code);
    $code = str_replace('$' . $val . '=', '$' . $deval . '=', $code);
    $code = str_replace('$' . $val . '(', '$' . $deval . '(', $code);
    $code = str_replace('$' . $val . ')', '$' . $deval . ')', $code);
    $code = str_replace('$' . $val . '.', '$' . $deval . '.', $code);
    $code = str_replace('$' . $val . '/', '$' . $deval . '/', $code);
    $code = str_replace('$' . $val . '>', '$' . $deval . '>', $code);
    $code = str_replace('$' . $val . '<', '$' . $deval . '<', $code);
    $code = str_replace('$' . $val . '^', '$' . $deval . '^', $code);
    $code = str_replace('$' . $val . '||', '$' . $deval . '||', $code);
    $code = str_replace('($' . $val . ' ', '($' . $deval . ' ', $code);
    return $code;
}

function fmt_code($code) {
    global $vals, $funs;
    preg_match_all("///$[0-9a-zA-Z/[/]']+(,|;)/iesU", $code, $res);
    foreach ($res[0] as $v) {
        $val = str_replace(array('$', ',', ';'), '', $v);
        $deval = destr($val, 1);
        $vals[$val] = $deval;
        $code = val_replace($code, $val, $deval);
    }

    preg_match_all("///$[0-9a-zA-Z/[/]']+=/iesU", $code, $res);
    foreach ($res[0] as $v) {
        $val = str_replace(array('$', '='), '', $v);
        $deval = destr($val, 1);
        $vals[$val] = $deval;
        $code = val_replace($code, $val, $deval);
    }

    preg_match_all("/function/s[0-9a-zA-Z/[/]]+/(/iesU", $code, $res);
    foreach ($res[0] as $v) {
        $val = str_replace(array('function ', '('), '', $v);
        $deval = destr($val, 1);
        $funs[$val] = $deval;
        $code = str_replace('function ' . $val . '(', 'function ' . $deval . '(', $code);
        $code = str_replace('=' . $val . '(', '=' . $deval . '(', $code);
        $code = str_replace('return ' . $val . '(', 'return ' . $deval . '(', $code);
    }
    return $code;
}

function strdecode($str) {
    $len = strlen($str);
    $newstr = '';
    for ($i = 0; $i < $len; $i++) {
        $n = ord($str[$i]);
        $newstr .= decode($n);
    }
    return $newstr;
}

function decode($dec) {
    if (($dec > 126 || $dec < 32) && $dec <> 13 && $dec <> 10) {
        return '[' . $dec . ']';
    } else {
        return chr($dec);
    }
}

function destr($str, $val = 0) {
    $k = 0;
    $num = '';
    $n = strlen($str);
    $code = '';
    for ($i = 0; $i < $n; $i++) {
        if ($str[$i] == '[' && ($str[$i + 1] == 1 || $str[$i + 1] == 2)) {
            $k = 1;
        } elseif ($str[$i] == ']' && $k == 1) {
            $num = intval($num);
            if ($val == 1) {
                $num = 97 + fmod($num, 25);
            }
            $code .= chr($num);
            $k = 0;
            $num = null;
        } else {
            if ($k == 1) {
                $num .= $str[$i];
            } else {
                $code .= $str[$i];
            }
        }
    }
    return $code;
}
?>