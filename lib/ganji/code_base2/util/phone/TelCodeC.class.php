<?php 

class TelCodeC {
    static function GetChecksum($phone,$level,$style){
        $check_sum_base = 1000;
        $check_sum_len = 3;
        $phone_num = 0;

        $phone_len = strlen($phone);
        for($i = 0;$i < $phone_len;$i++)
        {
            $phone_num = ($phone_num + ord($phone[$i]))% $check_sum_base;
        }

        $style_len = strlen($style);
        $style_num = 0;
        for($i= 0; $i < $style_len; $i++)
        {
            $style_num = ($style_num + ord($style[$i]))% $check_sum_base;        
        }

        $check = ($phone_num + $level + $style_num) % $check_sum_base;
        $check_sum = strval($check);     
        if (strlen($check_sum) < $check_sum_len)
        {
            $check_sum = str_pad($check_sum,$check_sum_len,"0");
        }
        return $check_sum;
    }

    static function leftRotate($str) {
        $str= strrev($str);
        $cut_pos = strlen($str)-5;
        $left = substr($str,0,$cut_pos);
        $right = substr($str,$cut_pos);     
        return strrev($left).strrev($right);
    }
    static function Encode($phone, $style='-1', $level=-1, $domain="", $cate="") {
        $phone = (string) $phone;
        $check_sum = self::GetChecksum($phone,$level,$style);        
        $strs = "0=$phone=$level=$style=$domain=$cate=$check_sum";
        return '?c=' . self::Encrypt($strs);        
    }

    static function Encrypt($str) {
        $key= '1 2@2%#%';        
        $td = mcrypt_module_open(MCRYPT_DES,'','ncfb',''); //使用MCRYPT_DES算法,CFB模式
        mcrypt_generic_init($td, $key, $key);       //初始处理
        $encrypted = mcrypt_generic($td, $str);   
        mcrypt_generic_deinit($td);       //结束
        mcrypt_module_close($td);
        $base64= base64_encode($encrypted);
        $res = str_replace(array('+','/','='),array('-','.','_'),$base64);
        return self::leftRotate($res);
    }
}