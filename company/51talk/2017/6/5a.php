<?php

/* ΢��PHP�ű����ܼ������㷨 */

function get_filetree($path) {

    $tree = array();

    foreach (glob($path . '/*') as $single) {

        if (is_dir($single)) {

            $tree = array_merge($tree, get_filetree($single));
        } else {

            $tree[] = $single;
        }
    }

    return $tree;
}

function eval_decode($File) {
    $Lines = file($File);

    if (preg_match("/eval\(base64_decode\(\'(.*?)\'\)/", $Lines[1], $baseDecode)) {
        $var = base64_decode($baseDecode[1]);
        eval($var);
    }
    $Content;
    if (preg_match("/O0O0000O0\('.*'\)/", $Lines[1], $S)) {

        $Content = str_replace("O0O0000O0('", "", $S[0]);

        $Content = str_replace("')", "", $Content);

        $Content = base64_decode($Content);
    } else {

        return "file not encode!";
    }
    $Key;

    if (preg_match("/\),'.*',/", $Content, $K)) {

        $Key = str_replace("),'", "", $K[0]);

        $Key = str_replace("',", "", $Key);
    } else {

        return "not decode key!";
    }

    $Length;

    if (preg_match("/,\d*\),/", $Content, $K)) {

        $Length = str_replace("),", "", $K[0]);

        $Length = str_replace(",", "", $Length);
    } else {

        return "not decode base64 string!";
    }

    $Secret = substr($Lines[2], $Length);
    $Decode = "<?php";
    $str = strtr($Secret, $Key, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/');

    $Decode .= base64_decode($str);
    $Decode .= "?>";

    //�滻��������
    if (preg_match_all('/\$GLOBALS\[\'OOO0000O0\'\]\(\'(.+?)\'\)/is', $Decode, $Ks)) {
        foreach ($Ks[0] as $i => $k) {
            $s = "'" . base64_decode($Ks[1][$i]) . "'";
            $Decode = str_replace($k, $s, $Decode);
        }
    }

    //�����滻
    if (preg_match_all('/\$GLOBALS\[\'(III\w+)\'\]/is', $Decode, $is)) {
        foreach ($is[0] as $i => $s) {
            $Decode = str_replace($s, $$is[1][$i], $Decode);
        }
    }

    file_put_contents($File, $Decode);

    return "file decode success!";
}

//Ҫ���ܵ��ļ�·��,�ļ���λ��

$filelist = get_filetree("G:/tmp/desktop/2017/t2");

foreach ($filelist as $value) {
    echo $value . " :\t\t" . eval_decode($value) . "\n\r<br>";
}
?>