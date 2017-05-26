<?php
/**
 * 电话号码加密实现方案之二
 * 
 * @author  caifeng@ganji.com
 * @version v5
 * @since   2011-3-10
 * 
 */

/****************************************************************
 *	chenkai 20071212 电话加密解密类
 ****************************************************************
 *	第一位和最后一位的数字之和个位数为多少，根据个数判断编码。
 *	0: mtacbrjoqp
 *	1: pkjhtreaxz
 *	2: mhgrdxspoq
 *	3: fdcthjikns
 *	4: iuhbjklsec
 *	5: loknpdcset
 *	6: tfojnxslih
 *	7: pljngbvdsq
 *	8: ncvgrewqlp
 *	9: gtfjkwmnva
 ******************************************************************
 *	再根据第一个和倒数第二个数字解码：
 *	0:wn
 *	1:ip
 *	2:ol
 *	3:ss
 *	4:pq
 *	5:ko
 *	6:sm
 *	7:ak
 *	8:hj
 *	9:da
 *******************************************************************
 *	只处理电话，不处理区号和分机号，在处理后的电话前加年月。如：0712
 *******************************************************************/

/**
 * @brief 对电话号码进行加密
 * @example 详情页获取电话号码发帖记录的URL
 */
class TelCodeB {

    public function TelCodeB() {
    }

    /**
     * @param string telstr 需要编码的电话号码
     * @return string 编码后的字符串
     * @detail 如果输入字符串中包含"-",则结果是各个用"-"连接的部分分别编码后再用"-"连接的
     */
    public function Encode($telstr) {
        $pos = strpos($telstr, '-');
        if ($pos === false) {
            $encodeStr = self :: bianMa($telstr);
        } else {
            $tmpstr = explode('-', $telstr);
            foreach ($tmpstr as & $val) {
                $val = self :: bianMa($val);
            }
            $encodeStr = implode('-', $tmpstr);
        }

        return $encodeStr;

    }

    /**
     * @param string telstr 需要编码的电话号码
     * @return string 编码后的字符串
     * @detail 如果输入字符串中包含"-",则结果是各个用"-"连接的部分分别编码后再用"-"连接的
     */
    public function Decode($telstr) {

        $pos = strpos($telstr, '-');
        if ($pos === false) {
            $encodeStr = self :: jieMa($telstr);
        } else {
            $tmpstr = explode('-', $telstr);
            foreach ($tmpstr as & $val) {
                $val = self :: jieMa($val);
            }
            $encodeStr = implode('-', $tmpstr);
        }

        return $encodeStr;

    }

    /**
     * @brief 实现具体的解码操作
     * @param string telstr 需要解码的字符串
     * @return string 解码结果
     */
    protected function bianMa($telstr) {
        $last_key = substr($telstr, -1);
        $first_key = substr($telstr, 0, 1);
        $num = ($last_key + $first_key) % 10;
        switch ($num) {
            case 0 :
                $bianma = array (
                    "m",
                    "t",
                    "a",
                    "c",
                    "b",
                    "r",
                    "j",
                    "o",
                    "q",
                    "p"
                );
                $getkey = 0;
                $bianma_key = "wn";
                break;
            case 1 :
                $bianma = array (
                    "p",
                    "k",
                    "j",
                    "h",
                    "t",
                    "r",
                    "e",
                    "a",
                    "x",
                    "z"
                );
                $getkey = 1;
                $bianma_key = "ip";
                break;
            case 2 :
                $bianma = array (
                    "m",
                    "h",
                    "g",
                    "r",
                    "d",
                    "x",
                    "s",
                    "p",
                    "o",
                    "q"
                );
                $getkey = 2;
                $bianma_key = "ol";
                break;
            case 3 :
                $bianma = array (
                    "f",
                    "d",
                    "c",
                    "t",
                    "h",
                    "j",
                    "i",
                    "k",
                    "n",
                    "s"
                );
                $getkey = 3;
                $bianma_key = "ss";
                break;
            case 4 :
                $bianma = array (
                    "i",
                    "u",
                    "h",
                    "b",
                    "j",
                    "k",
                    "l",
                    "s",
                    "e",
                    "c"
                );
                $getkey = 4;
                $bianma_key = "pq";
                break;
            case 5 :
                $bianma = array (
                    "l",
                    "o",
                    "k",
                    "n",
                    "p",
                    "d",
                    "c",
                    "s",
                    "e",
                    "t"
                );
                $getkey = 5;
                $bianma_key = "ko";
                break;
            case 6 :
                $bianma = array (
                    "t",
                    "f",
                    "o",
                    "j",
                    "n",
                    "x",
                    "s",
                    "l",
                    "i",
                    "h"
                );
                $getkey = 6;
                $bianma_key = "sm";
                break;
            case 7 :
                $bianma = array (
                    "p",
                    "l",
                    "j",
                    "n",
                    "g",
                    "b",
                    "v",
                    "d",
                    "s",
                    "q"
                );
                $getkey = 7;
                $bianma_key = "ak";
                break;
            case 8 :
                $bianma = array (
                    "n",
                    "c",
                    "v",
                    "g",
                    "r",
                    "e",
                    "w",
                    "q",
                    "l",
                    "p"
                );
                $getkey = 8;
                $bianma_key = "hj";
                break;
            case 9 :
                $bianma = array (
                    "g",
                    "t",
                    "f",
                    "j",
                    "k",
                    "w",
                    "m",
                    "n",
                    "v",
                    "a"
                );
                $getkey = 9;
                $bianma_key = "da";
                break;
            default :
                $bianma = array ();
                break;
        }
        if (empty ($bianma)) {
            return '';
        }
        $num_arr = array (
            "0",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9"
        );
        $bmtelstr = str_replace($num_arr, $bianma, $telstr);
        $bmtelstr_f = substr($bmtelstr, 0, -1);
        $bmtelstr_e = substr($bmtelstr, -1);
        $bianma_key_f = substr($bianma_key, 0, 1);
        $bianma_key_e = substr($bianma_key, -1);
        $bmtelstr = date("yd") . $bianma_key_f . $bmtelstr_f . $bianma_key_e . $bmtelstr_e;
        return $bmtelstr;

    }

    /**
     * @brief 实现具体的编码操作
     * @param string telstr 需要编码的字符串
     * @return string 编码结果
     */
    protected function jieMa($telstr) {
        $mstr = substr($telstr, 4);
        $jiema_key_f = substr($mstr, 0, 1);
        $jiema_key_e = substr($mstr, -2, 1);
        $jmtelstr_f = substr($mstr, 1, -2);
        $jmtelstr_e = substr($mstr, -1);
        $jmtelstr = $jmtelstr_f . $jmtelstr_e;
        $jiema_key = $jiema_key_f . $jiema_key_e;
        switch ($jiema_key) {
            case "wn" :
                $bianma = array (
                    "m",
                    "t",
                    "a",
                    "c",
                    "b",
                    "r",
                    "j",
                    "o",
                    "q",
                    "p"
                );
                $getkey = 0;
                break;
            case "ip" :
                $bianma = array (
                    "p",
                    "k",
                    "j",
                    "h",
                    "t",
                    "r",
                    "e",
                    "a",
                    "x",
                    "z"
                );
                $getkey = 1;
                break;
            case "ol" :
                $bianma = array (
                    "m",
                    "h",
                    "g",
                    "r",
                    "d",
                    "x",
                    "s",
                    "p",
                    "o",
                    "q"
                );
                $getkey = 2;
                break;
            case "ss" :
                $bianma = array (
                    "f",
                    "d",
                    "c",
                    "t",
                    "h",
                    "j",
                    "i",
                    "k",
                    "n",
                    "s"
                );
                $getkey = 3;
                break;
            case "pq" :
                $bianma = array (
                    "i",
                    "u",
                    "h",
                    "b",
                    "j",
                    "k",
                    "l",
                    "s",
                    "e",
                    "c"
                );
                $getkey = 4;
                break;
            case "ko" :
                $bianma = array (
                    "l",
                    "o",
                    "k",
                    "n",
                    "p",
                    "d",
                    "c",
                    "s",
                    "e",
                    "t"
                );
                $getkey = 5;
                break;
            case "sm" :
                $bianma = array (
                    "t",
                    "f",
                    "o",
                    "j",
                    "n",
                    "x",
                    "s",
                    "l",
                    "i",
                    "h"
                );
                $getkey = 6;
                break;
            case "ak" :
                $bianma = array (
                    "p",
                    "l",
                    "j",
                    "n",
                    "g",
                    "b",
                    "v",
                    "d",
                    "s",
                    "q"
                );
                $getkey = 7;
                break;
            case "hj" :
                $bianma = array (
                    "n",
                    "c",
                    "v",
                    "g",
                    "r",
                    "e",
                    "w",
                    "q",
                    "l",
                    "p"
                );
                $getkey = 8;
                break;
            case "da" :
                $bianma = array (
                    "g",
                    "t",
                    "f",
                    "j",
                    "k",
                    "w",
                    "m",
                    "n",
                    "v",
                    "a"
                );
                $getkey = 9;
                break;
            default :
                $bianma = array ();
                break;
        }
        if (empty ($bianma)) {
            return '';
        }
        $num_arr = array (
            "0",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9"
        );
        $jmtelstr = str_replace($bianma, $num_arr, $jmtelstr);
        return $jmtelstr;

    }
}