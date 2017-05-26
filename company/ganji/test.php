<?php

#1小雪，小到中雪，中雪
#2中到大雪，大雪，大到暴雪
#3暴雪，暴雪到大暴雪，大暴雪
#4阵雪，小雨，小到中雨
#5中雨，中到大雨，大雨
#6大到暴雨，暴雨，大暴雨
#7特大暴雨，阵雨，雷阵雨
#8雷阵雨伴有冰雹，冻雨，雨夹雪
#9晴，阴，多云
#10雾，浮尘，扬沙
#11沙尘暴，强沙尘暴

$weather_arr = Array
    (
    'dayWeather' => Array
        (
        '0' => Array
            (
            'temp' => '9℃～-3℃',
            'temp_detail' => Array
                (
                '0' => 9,
                '1' => -3,
            ),
            'weather' => '扬沙',
            'icons' => Array
                (
                '0' => 012,
                '1' => 112,
            ),
            'wind_d' => '微风',
            'wind_force' => ''
        ),
        '1' => Array
            (
            'temp' => '7℃～-3℃',
            'temp_detail' => Array
                (
                '0' => 7,
                '1' => -3
            ),
            'weather' => '沙尘暴',
            'icons' => Array
                (
                '0' => 012,
                '1' => 112
            ),
            'wind_d' => '微风',
            'wind_force' => ''
        ),
        '2' => Array
            (
            'temp' => '9℃～-2℃',
            'temp_detail' => Array
                (
                '0' => 9,
                '1' => -2
            ),
            'weather' => '强沙尘暴',
            'icons' => Array
                (
                '0' => 012,
                '1' => 107
            ),
            'wind_d' => '微风',
            'wind_force' => '',
        ),
        '3' => Array
            (
            'temp' => '12℃～-1℃',
            'temp_detail' => Array
                (
                '0' => 12,
                '1' => -1
            ),
            'weather' => '晴',
            'icons' => Array
                (
                '0' => 012,
                '1' => 112
            ),
            'wind_d' => '微风',
            'wind_force' => '',
        ),
        '4' => Array
            (
            'temp' => '11℃～0℃',
            'temp_detail' => Array
                (
                '0' => 11,
                '1' => 0
            ),
            'weather' => '晴',
            'icons' => Array
                (
                '0' => 012,
                '1' => 112
            ),
            'wind_d' => '微风',
            'wind_force' => '',
        ),
        '5' => Array
            (
            'temp' => '10℃～2℃',
            'temp_detail' => Array
                (
                '0' => 10,
                '1' => 2
            ),
            'weather' => '多云转阴',
            'icons' => Array
                (
                '0' => 007,
                '1' => 119
            ),
            'wind_d' => '微风',
            'wind_force' => '',
        )
    )
);

$arr = json_encode($weather_arr);
print_r($arr);
die;

//echo bin2hex('VWpoljs3IwlL1yOOVWJflsya');die;
//$installId = UUIDprocess::decryptUUId('B9DBCE1A19C9BE360E3BDA5C5A4E7791');
var_dump($installId);
die;
$arr = range(0, 5);

$a = var_export($arr, true);
var_dump($a);
die;
$s = array_slice($arr, -3, 2);
$s = file_get_contents('801_0');
$s = unserialize($s);
print_r($s);
die;
//file_put_contents('tmp.txt', $s);

$category_arr = array(); #分类文件基础结构

$category_arr['version'] = time();
$category_arr3[] = array(
    'id' => '0.1',
    'title' => '热门',
    'mode' => '1',
    'ext' => '',
    'itemList' => array(
        array(
            'id' => '5.1.1',
            'title' => '保洁',
            'imgUrl' => 'http://image.ganjistatic1.com/gjfs05/M03/81/85/wKhzU1IfEwrMIHLrAABeTSSOmcQ245_294-140_8-0.png',
            'jumpType   ' => '2',
            'dataParams' => array(
                'supportFilter' => 1,
                'filterParams' => '{"GetMajorCategoryFilter" : {"categoryId ":"5","majorCategoryScriptIndex":"35"}}',
                'queryParams' => '{"SearchPostsByJson2": {"categoryId":"5","majorCategoryScriptIndex":"35"}}',
                'showType' => 0
            ),
        ),
        array(
            'id' => '5.1.2',
            'title' => '家政月嫂',
            'imgUrl' => 'http://image.ganjistatic1.com/gjfs05/M01/81/86/wKhzVFIfEwr0OBdHAABoGW0oTw8044_294-140_8-0.png',
            'jumpType' => '2',
            'dataParams' => array(
                'supportFilter' => 1,
                'filterParams' => '{"GetMajorCategoryFilter" : {"categoryId ":"5","majorCategoryScriptIndex":"35"}}',
                'queryParams' => '{"SearchPostsByJson2": {"categoryId":"5","majorCategoryScriptIndex":"35"}}',
                'showType' => 0
            ),
        ),
    ),
);

print_r($s);
?>

<?php

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
        return strtoupper(bin2hex(mcrypt_cbc(MCRYPT_DES, $this->key, $str, MCRYPT_ENCRYPT, $this->iv)));
    }

    function decrypt($str) {
        //解密
        $strBin = $this->hex2bin(strtolower($str));
        $str = mcrypt_cbc(MCRYPT_DES, $this->key, $strBin, MCRYPT_DECRYPT, $this->iv);
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
?>
