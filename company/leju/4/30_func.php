<?php

/**
 * 中文转拼音
 * @param type $_String
 * @param type $_Code
 * @return type
 */
function Pinyin($_String, $_Code = 'gb2312') {
    $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
    $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
            "|-10270|-10262|-10260|-10256|-10254";
    $_TDataKey = explode('|', $_DataKey);
    $_TDataValue = explode('|', $_DataValue);
    $_Data = (PHP_VERSION >= '5.0') ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
    arsort($_Data);
    reset($_Data);
    if ($_Code != 'gb2312')
        $_String = _U2_Utf8_Gb($_String);
    $_Res = '';
    for ($i = 0; $i < strlen($_String); $i++) {
        $_P = ord(substr($_String, $i, 1));
        if ($_P > 160) {
            $_Q = ord(substr($_String, ++$i, 1));
            $_P = $_P * 256 + $_Q - 65536;
        }
        $_Res .= _Pinyin($_P, $_Data);
    }
    return preg_replace("/[^a-z0-9]*/", '', $_Res);
}

function _Pinyin($_Num, $_Data) {
    if ($_Num > 0 && $_Num < 160)
        return chr($_Num);
    elseif ($_Num < -20319 || $_Num > -10247)
        return '';
    else {
        foreach ($_Data as $k => $v) {
            if ($v <= $_Num)
                break;
        }
        return $k;
    }
}

/**
 * UTF8转GBK
 * @param type $_C
 * @return type
 */
function _U2_Utf8_Gb($_C) {
    $_String = '';
    if ($_C < 0x80)
        $_String .= $_C;
    elseif ($_C < 0x800) {
        $_String .= chr(0xC0 | $_C >> 6);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif ($_C < 0x10000) {
        $_String .= chr(0xE0 | $_C >> 12);
        $_String .= chr(0x80 | $_C >> 6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif ($_C < 0x200000) {
        $_String .= chr(0xF0 | $_C >> 18);
        $_String .= chr(0x80 | $_C >> 12 & 0x3F);
        $_String .= chr(0x80 | $_C >> 6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    }
    return iconv('UTF-8', 'GB2312', $_String);
}

function _Array_Combine($_Arr1, $_Arr2) {
    for ($i = 0; $i < count($_Arr1); $i++)
        $_Res[$_Arr1[$i]] = $_Arr2[$i];
    return $_Res;
}

//用法：
//第二个参数留空则为gb1232编码
//echo Pinyin('爱尔兰');

/**
 * TcEncryption 加密函数，可反向
 */
class TcEncryption {

    public function __construct() {
        $tc_encryption_salt_ini = sfconfig::get('sf_root_dir') . '/config/tc_encryption_salt.ini';
        if (!file_exists($tc_encryption_salt_ini)) {
            fopen($tc_encryption_salt_ini, "w+");
        }

        $this->skey = file_get_contents($tc_encryption_salt_ini);
        if (empty($this->skey)) {
            $skey = $this->getRandSkey();
            file_put_contents($tc_encryption_salt_ini, $skey);
            $this->skey = file_get_contents($tc_encryption_salt_ini);
            if (empty($this->skey)) {
                die('tc_encryption_salt.ini error');
            }
        }
    }

    public function getRandSkey() {
        $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        shuffle($arr);
        $str = implode('', $arr);
        $rand_skey = md5(substr($str, 0, rand(1, 40)));
        return $rand_skey;
    }

    public function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function encode($value) {
        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }

    public function decode($value) {
        if (!$value) {
            return false;
        }
        $crypttext = $this->safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

}

/**
 * 
 * 全文搜索，全文索引
 */
class AskSearchManage {

    public function normalizeText($string) {

        $out = $this->wordSegmentation($string);

        $out = preg_replace_callback(
                "/([\\xc0-\\xff][\\x80-\\xbf]*)/", array($this, 'stripForSearchCallback'), $out);

        $sql = "SHOW GLOBAL VARIABLES LIKE 'ft\\_min\\_word\\_len'";

        $connection = Propel::getConnection();
        $connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        $statement = $connection->prepare($sql);
        $statement->execute();
        $minLength_objs = $statement->fetchAll();
        $minLength_obj = $minLength_objs[0];
        if ($minLength_obj && $minLength_obj['Variable_name'] == 'ft_min_word_len') {
            $minLength = intval($minLength_obj['Value']);
        } else {
            $minLength = 0;
        }
        if ($minLength > 1) {
            $n = $minLength - 1;
            $out = preg_replace(
                    "/\b(\w{1,$n})\b/", "$1u800", $out);

            $out = preg_replace(
                    "/(\w)\.(\w|\*)/u", "$1u82e$2", $out);
            return $out;
        }
    }

    public function wordSegmentation($string) {
        $reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
        $string = preg_replace($reg, " $1 ", $string);
        $string = preg_replace('/ +/', ' ', $string);
        return $string;
    }

    protected function stripForSearchCallback($matches) {
        return 'u8' . bin2hex($matches[1]);
    }

    /**
     * Parse the user's query and transform it into an SQL fragment which will 
     * become part of a WHERE clause
     */
    public function parseQuery($filteredText, $field = 'search_words') {
        $lc = "A-Za-z_'.0-9\\x80-\\xFF\\-"; // Minus format chars
        $searchon = '';

# FIXME: This doesn't handle parenthetical expressions.
        $m = array();
        if (preg_match_all('/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/', $filteredText, $m, PREG_SET_ORDER)) {
            foreach ($m as $bits) {
                @list( /* all */, $modifier, $term, $nonQuoted, $wildcard ) = $bits;

                if ($nonQuoted != '') {
                    $term = $nonQuoted;
                    $quote = '';
                } else {
                    $term = str_replace('"', '', $term);
                    $quote = '"';
                }

                if ($searchon !== '')
                    $searchon .= ' ';
                if (($modifier == '')) {
// If we leave this out, boolean op defaults to OR which is rarely helpful.
                    $modifier = '+';
                }

// Some languages such as Serbian store the input form in the search index,
// so we may need to search for matches in multiple writing system variants.
                $convertedVariants = $term;
                if (is_array($convertedVariants)) {
                    $variants = array_unique(array_values($convertedVariants));
                } else {
                    $variants = array($term);
                }

// The low-level search index does some processing on input to work
// around problems with minimum lengths and encoding in MySQL's
// fulltext engine.
// For Chinese this also inserts spaces between adjacent Han characters.
                $strippedVariants = $variants;

// Some languages such as Chinese force all variants to a canonical
// form when stripping to the low-level search index, so to be sure
// let's check our variants list for unique items after stripping.
                $strippedVariants = array_unique($strippedVariants);

                $searchon .= $modifier;
                if (count($strippedVariants) > 1)
                    $searchon .= '(';
                foreach ($strippedVariants as $stripped) {
                    $stripped = $this->normalizeText($stripped);
                    if ($nonQuoted && strpos($stripped, ' ') !== false) {
// Hack for Chinese: we need to toss in quotes for
// multiple-character phrases since normalizeForSearch()
// added spaces between them to make word breaks.
                        $stripped = '"' . trim($stripped) . '"';
                    }
                    $searchon .= "$quote$stripped$quote$wildcard ";
                }
                if (count($strippedVariants) > 1)
                    $searchon .= ')';

// Match individual terms or quoted phrase in result highlighting...
// Note that variants will be introduced in a later stage for highlighting!
            }
        } else {
//Can't understand search query
        }
        $searchon = mysql_real_escape_string($searchon);
        return " MATCH(`ask_question`.$field) AGAINST('$searchon' IN BOOLEAN MODE) ";
    }

//    public function queryMain($filteredText, $field = 'title', $offset, $limit) {
//        $match = $this->parseQuery($filteredText, $field);
//        $result = 'SELECT * FROM `page`, `searchindex` WHERE `page`.id=`searchindex`.page_id  AND' . $match .
//                ' limit ' . $offset . ' , ' . $limit . '';
//        return $result;
//    }
}

//注：
//
//表必须是MyISAM
//
//索引字段必须是Fulltext
//
////用法
//存储的时候直接把存储索引 normalizeText（$index）；
//$ask_search_manage = new AskSearchManage();
//$match_sql = $ask_search_manage->parseQuery($title);
//$my_sql = "SELECT * FROM " . DB_TABLEPRE . "question WHERE " . $match_sql;
//搜索的match 函数parseQuery
//样例sql
//SELECT * FROM ask_question WHERE MATCH(`ask_question`.search_words) AGAINST('+\"u8e6b3a8 u8e5b084 u8e58982\" ' IN BOOLEAN MODE) 



/**
 * 
 * 生成缩略图，image resize max fixed thum三种  
 */

/**
  //裁切图片函数
 * $srcFile - 图形文件
 * $pixel - 尺寸大小，如：400*300
 * $_quality - 图片质量，默认100
 * $cut - 是否裁剪，默认1，当$cut=0的时候，将不进行裁剪
 * $cache - 如果有缓存，是否直接用缓存，默认true
 * 示例："< img src=\"".MiniImg('images/image.jpg','300*180',100,1)."\">"
 * MiniImg("1.jpg","100*200");
 */
class UtilsMiniImg {

    public static function MiniImg($srcFile, $pixel, $target_file, $_quality = 100, $cut = 1, $cache = false) {
        if ($srcFile == ".")
            return;

        $pixelInfo = explode('*', $pixel);
        $pathInfo = pathinfo($srcFile);
        $_cut = intval($cut);
        $searchFileName = preg_replace("/\.([A-Za-z0-9]+)$/isU", "_" . $pixelInfo[0] . "x" . $pixelInfo[1] . "_" . $cut . ".\\1", $pathInfo['basename']);
//$miniFile = $pathInfo['dirname'].'/'.$searchFileName;
        $ShowTime4 = date("Y-m-d H:i:s");
        $Pic_FileName = strtotime($ShowTime4) . rand(1, 10);

        $miniFile = $target_file;  //改成替换原图

        if ($cache and file_exists($miniFile))
            return $miniFile;

        $data = GetImageSize($srcFile);
        $FuncExists = 1;
        switch ($data[2]) {
            case 1:   //gif
                if (function_exists('ImageCreateFromGIF'))
                    $_im = ImageCreateFromGIF($srcFile);
                break;
            case 2:   //jpg
                if (function_exists('imagecreatefromjpeg'))
                    $_im = imagecreatefromjpeg($srcFile);
                break;
            case 3:   //png
                if (function_exists('ImageCreateFromPNG'))
                    $_im = ImageCreateFromPNG($srcFile);
                break;
            case 6:   //bmp，这里需要用到ImageCreateFromBMP
                $_im = ImageCreateFromBMP($srcFile);
                break;
        }
        if (!$_im)
            return $srcFile;
        $sizeInfo['width'] = @imagesx($_im);
        $sizeInfo['height'] = @imagesy($_im);
        if (!$sizeInfo['width'] or ! $sizeInfo['height'])
            return $srcFile;
        if ($sizeInfo['width'] == $pixelInfo[0] && $sizeInfo['height'] == $pixelInfo[1]) {
            return $srcFile;
        } elseif ($sizeInfo['width'] < $pixelInfo[0] && $sizeInfo['height'] < $pixelInfo[1] && $miniMode == '2') {
            return $srcFile;
        } else {
            $resize_ratio = ($pixelInfo[0]) / ($pixelInfo[1]);
            $ratio = ($sizeInfo['width']) / ($sizeInfo['height']);
            if ($cut == 1) {
                $newimg = imagecreatetruecolor($pixelInfo[0], $pixelInfo[1]);
                if ($ratio >= $resize_ratio) {          //高度优先
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], $pixelInfo[1], (($sizeInfo['height']) * $resize_ratio), $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                } else {               //宽度优先
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], $pixelInfo[1], $sizeInfo['width'], (($sizeInfo['width']) / $resize_ratio));
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                }
            } else {                //不裁图
                if ($ratio >= $resize_ratio) {
                    $newimg = imagecreatetruecolor($pixelInfo[0], ($pixelInfo[0]) / $ratio);
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], ($pixelInfo[0]) / $ratio, $sizeInfo['width'], $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                } else {
                    $newimg = imagecreatetruecolor(($pixelInfo[1]) * $ratio, $pixelInfo[1]);
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, ($pixelInfo[1]) * $ratio, $pixelInfo[1], $sizeInfo['width'], $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                }
            }


            ImageDestroy($_im);
            ImageDestroy($newimg);

            if ($_result)
                return $miniFile;
            return $srcFile;
        }
    }

}

//====================


class UtilsImage {

    public static function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90) {

// Get dimensions of existing image
        $image = getimagesize($src_image);

// Check for valid dimensions
        if ($image[0] <= 0 || $image[1] <= 0)
            return false;

// Determine format from MIME-Type
        $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

// Import image
        switch ($image['format']) {
            case 'jpg':
            case 'jpeg':
                $image_data = imagecreatefromjpeg($src_image);
                break;
            case 'png':
                $image_data = imagecreatefrompng($src_image);
                break;
            case 'gif':
                $image_data = imagecreatefromgif($src_image);
                break;
            default:
// Unsupported format
                return false;
                break;
        }

// Verify import
        if ($image_data == false)
            return false;

// Calculate measurements
        if ($image[0] & $image[1]) {
// For landscape images
            $x_offset = ($image[0] - $image[1]) / 2;
            $y_offset = 0;
            $square_size = $image[0] - ($x_offset * 2);
        } else {
// For portrait and square images
            $x_offset = 0;
            $y_offset = ($image[1] - $image[0]) / 2;
            $square_size = $image[1] - ($y_offset * 2);
        }

// Resize and crop
        $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
        if (imagecopyresampled(
                        $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size
                )) {

// Create thumbnail
            switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
                case 'jpg':
                case 'jpeg':
                    return imagejpeg($canvas, $dest_image, $jpg_quality);
                    break;
                case 'png':
                    return imagepng($canvas, $dest_image);
                    break;
                case 'gif':
                    return imagegif($canvas, $dest_image);
                    break;
                default:
// Unsupported format
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }

    public static function resize($img, $thumb_width, $newfilename = null) {
        $max_width = $thumb_width;

//Check if GD extension is loaded
        if (!extension_loaded('gd') && !extension_loaded('gd2')) {
            trigger_error("GD is not loaded", E_USER_WARNING);
            return false;
        }

//Get Image size info
        list($width_orig, $height_orig, $image_type) = getimagesize($img);

        switch ($image_type) {
            case 1: $im = imagecreatefromgif($img);
                break;
            case 2: $im = imagecreatefromjpeg($img);
                break;
            case 3: $im = imagecreatefrompng($img);
                break;
            default: trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }

        /*         * * calculate the aspect ratio ** */
        $aspect_ratio = (float) $height_orig / $width_orig;

        /*         * * calulate the thumbnail width based on the height ** */
        $thumb_height = round($thumb_width * $aspect_ratio);


        while ($thumb_height > $max_width) {
            $thumb_width-=1;
            $thumb_height = round($thumb_width * $aspect_ratio);
        }

        $newImg = imagecreatetruecolor($thumb_width, $thumb_height);

        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($image_type == 1) OR ( $image_type == 3)) {
            imagealphablending($newImg, false);
            imagesavealpha($newImg, false);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $thumb_width, $thumb_height, $transparent);
        }
        imagecopyresampled($newImg, $im, 0, 0, 0, 0, $thumb_width, $thumb_height, $width_orig, $height_orig);

//Generate the file, and rename it to $newfilename
        switch ($image_type) {
            case 1: imagegif($newImg, $newfilename);
                break;
            case 2: imagejpeg($newImg, $newfilename);
                break;
            case 3: imagepng($newImg, $newfilename);
                break;
            default: trigger_error('Failed resize image!', E_USER_WARNING);
                break;
        }

        return $newfilename;
    }

    public static function deleteDirectory($dir) {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir) || is_link($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!UtilsImage::deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!UtilsImage::deleteDirectory($dir . "/" . $item))
                    return false;
            };
        }
        return rmdir($dir);
    }

    public static function smartImageSizeHtml($image_path, $max_size) {
        list($new_width, $new_height) = self::smartImageSize($image_path, $max_size);
        return 'width="' . $new_width . '" height="' . $new_height . '"';
    }

    public static function smartImageSize($image_path, $max_size) {
        if (!file_exists($image_path)) {
            $new_height = $max_size;
            $new_width = $max_size;
        } else {
            $infos = getimagesize($image_path);
            $width = $infos[0];
            $height = $infos[1];
            if ($width > $height) {
                $new_height = $max_size * ($height / $width);
                $new_width = $max_size;
            } else {
                $new_width = $max_size * ($width / $height);
                $new_height = $max_size;
            }
        }
        return array($new_width, $new_height);
    }

    public static function smartImageSizeHtmlNoEnlargePdf($image_path, $max_size) {
        list($new_width, $new_height) = self::smartImageSizeNoEnlarge($image_path, $max_size);
        return 'width="' . $new_width * 0.87 . '" height="' . $new_height * 0.87 . '"';
    }

    public static function smartImageSizeHtmlNoEnlarge($image_path, $max_size) {
        list($new_width, $new_height) = self::smartImageSizeNoEnlarge($image_path, $max_size);
        return 'width="' . $new_width . '" height="' . $new_height . '"';
    }

    public static function smartImageSizeNoEnlarge($image_path, $max_size) {
        if (!file_exists($image_path)) {
            $new_height = $max_size;
            $new_width = $max_size;
        } else {
            $infos = getimagesize($image_path);
            $width = $infos[0];
            $height = $infos[1];
            if ($width < $max_size && $height < $max_size) {
                $new_height = $height;
                $new_width = $width;
            } else {
                if ($width > $height) {
                    $new_height = $max_size * ($height / $width);
                    $new_width = $max_size;
                } else {
                    $new_width = $max_size * ($width / $height);
                    $new_height = $max_size;
                }
            }
        }
        return array($new_width, $new_height);
    }

    /**
     * Get folder hashing path, design for 256*256=65536 folders as containers
     * 
     * @param type $key
     * @return string of 2 levels folder path
     */
    public static function HashingPath($key, $is_url = false) {
        $hash_str = md5($key);
        $level = 2;
        $hash_dir = array();
        for ($i = 0; $i < $level; $i++) {
            $hash_dir[] = substr($hash_str, $i, 2);
        }
        return $is_url ? implode('/', $hash_dir) : implode(DIRECTORY_SEPARATOR, $hash_dir);
    }

    public static function getImageExts() {
        return array(
            'jpg', 'jpeg', 'gif', 'png'
        );
    }

    public static function resizeImageForMax($filename, $max_width, $max_height = '', $newfilename = "", $withSampling = true) {
        if ($newfilename == "")
            $newfilename = $filename;
// Get new sizes
        list($width, $height) = getimagesize($filename);
// -- dont resize if the width of the image is smaller or equal than the new size.
        if ($width <= $max_width)
            $max_width = $width;

        $percent = $max_width / $width;

        $newwidth = $width * $percent;
        if ($max_height == '') {
            $newheight = $height * $percent;
        } else
            $newheight = $max_height;
// Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $ext = strtolower(UtilsFile::getExtension($filename));

        if ($ext == 'jpg' || $ext == 'jpeg')
            $source = imagecreatefromjpeg($filename);
        if ($ext == 'gif')
            $source = imagecreatefromgif($filename);
        if ($ext == 'png')
            $source = imagecreatefrompng($filename);
// Resize
        if ($withSampling)
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        else
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
// Output
        if ($ext == 'jpg' || $ext == 'jpeg')
            return imagejpeg($thumb, $newfilename);
        if ($ext == 'gif')
            return imagegif($thumb, $newfilename);
        if ($ext == 'png')
            return imagepng($thumb, $newfilename);
    }

    public static function resizeImageFixed($filename, $fix_width, $fix_height = '', $newfilename = "", $withSampling = true) {
        if ($newfilename == "")
            $newfilename = $filename;
// Get new sizes
        list($width, $height) = getimagesize($filename);


        if ($fix_height == '') {
            $percent = ($fix_width / $width);
            $newheight = $percent * $height;
            $newwidth = $fix_width;
        } else {
            $percent = ($fix_height / $height);
            $newwidth = $percent * $width;
            $newheight = $fix_height;
        }

// Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $ext = strtolower(UtilsFile::getExtension($filename));

        if ($ext == 'jpg' || $ext == 'jpeg')
            $source = imagecreatefromjpeg($filename);
        if ($ext == 'gif')
            $source = imagecreatefromgif($filename);
        if ($ext == 'png')
            $source = imagecreatefrompng($filename);
// Resize
        if ($withSampling)
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        else
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
// Output
        if ($ext == 'jpg' || $ext == 'jpeg')
            return imagejpeg($thumb, $newfilename);
        if ($ext == 'gif')
            return imagegif($thumb, $newfilename);
        if ($ext == 'png')
            return imagepng($thumb, $newfilename);
    }

}

//UtilsMiniImg::MiniImg('./a.png', '175*175', './a.png');

/**
 * 判断远程图片是否存在
 * @param type $url
 * @return boolean
 */
function check_remote_file_exists($url) {
    $curl = curl_init($url);
    // 不取回数据
    curl_setopt($curl, CURLOPT_NOBODY, true);
    // 发送请求
    $result = curl_exec($curl);
    $found = false;
    // 如果请求没有发送失败
    if ($result !== false) {
        // 再检查http响应码是否为200
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($statusCode == 200) {
            $found = true;
        }
    }
    curl_close($curl);

    return $found;
}
