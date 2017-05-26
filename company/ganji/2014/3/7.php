<?php

//var_dump($_GET);
//DIE;
//$s = array();
//$a = 456;
//if ($s) {
//    $a = 123;
//}
$user_id = '177517387';
$token = 's4UaCE818fAd/IT2s4jX9y3e';
$token = bin2hex($token);
echo $token;
echo "<br/>";
echo UUIDprocess::encryptUUId($user_id);
echo "<br/>";
//echo bin2hex($s1);
//echo UUIDprocess::encryptUUId(50024678);
echo "<br/>";
die;
$time = date('H');
if ($time >= 8 && $time <= 20) {
    $result = 1;
} else {
    $result = 2;
}
//var_dump($result);

$array = array('d' => 'dog', 'cat', 'mouse');
array_map('arrayMapF', $array);

function arrayMapF($v) {
    #$v 是数组的值 
    $v = "animal";
    return $v;
}

//var_dump(array_map('arrayMapF', $array));
var_dump(array_walk($array, 'arrayWalF'));
die;

function arrayWalF($v, $k) {
    return 12312;
//    var_dump($k);
//    var_dump($v);
}

class DES {

    var $key;
    var $iv; //偏移量

    function DES($key, $iv = 0) {
        $this->key = $key;
        if (!$iv) {
            $this->iv = $key; //mcrypt_create_iv ( mcrypt_get_block_size (MCRYPT_DES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM ); //默认以$key 作为 iv
        } else {
            $this->iv = $iv; //;
        }
    }

    function encrypt($str) {
        //加密，返回大写十六进制字符串
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = $this->pkcs5Pad($str, $size);
        //$str = mcrypt_cbc(MCRYPT_DES, $this->key, $str, MCRYPT_ENCRYPT, $this->iv );
        $str = mcrypt_encrypt(MCRYPT_DES, $this->key, $str, MCRYPT_MODE_CBC, $this->iv);
        return strtoupper(bin2hex($str));
    }

    function decrypt($str) {
        //解密
        $strBin = $this->hex2bin(strtolower($str));
        //$str = mcrypt_cbc( MCRYPT_DES, $this->key, $strBin, MCRYPT_DECRYPT, $this->iv );
        $str = mcrypt_decrypt(MCRYPT_DES, $this->key, $strBin, MCRYPT_MODE_CBC, $this->iv);
        $str = $this->pkcs5Unpad($str);
        return $str;
    }

    function hex2bin($hexData) {
        $binData = "";
        for ($i = 0; $i < strlen($hexData); $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }

    function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5Unpad($text) {
        $pad = ord($text {strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, - 1 * $pad);
    }

}

class UUIDprocess {

    const iv = "IfRFPrr+";
    const key = "E5PaC7Iw";

    static public function userIdPad($userId) {
        return str_pad($userId, 12, "a", STR_PAD_RIGHT);
    }

    static public function userIdTrim($str) {
        return intval(rtrim($str, 'a'));
    }

    static public function decryptUUId($userId) {
        $des = new DES(UUIDprocess::key, UUIDprocess::iv);
        $str = $des->decrypt($userId);
        return UUIDprocess::userIdTrim($str);
    }

    static public function encryptUUId($userId) {
        $des = new DES(UUIDprocess::key, UUIDprocess::iv);
        return $des->encrypt(UUIDprocess::userIdPad($userId));
    }

    /**
     * 获得wap的session (wap那边的逻辑)
     * @param <int> $userId yon
     * @return <string>
     */
    public static function getWapSessionId($userId) {
        //$des = new DES(UUIDprocess::key,UUIDprocess::iv);
        $des = new DES("IfRFPrr+", "E5PaC7Iw");
        $time = time();
        $str = $userId . "|" . $time;
        return $des->encrypt($str);
    }

}

//echo UUIDprocess::encryptUUId(0);
//echo UUIDprocess::decryptUUId('574018742D69DE3B331238B5942E39C5');