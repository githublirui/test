<?php
class String
{
	/**
	 * 截字(utf8)
	 * @param $sourceString 被截取的字符串
	 * @param $length		截取的长度
	 * @param $offset		开始截取的位置，从0开始
	 * @return string		截取得到的字符串
	 */
	public static function trword($sourceString, $maxLength, $postFix='...')
	{
	    if (empty($sourceString)) {
            return '';
        }
		if( self::strlen_utf8($sourceString)>$maxLength )
		{
			return self::substr_utf8($sourceString, $maxLength, 0) . $postFix;
		}
		return $sourceString;
	}

    /**
     * @brief 该函数按照长度截取字符串，包括上后缀，例如输入'abcdef',截取长度是5，则返回'ab...'
     * @param $sourceString
     * @param $maxLength
     * @param string $postFix
     * @return string
     */
    public static function sub_str($sourceString, $maxLength, $postFix='...')
    {
        if (empty($sourceString)) {
            return '';
        }
        $fixLen = self::strlen_utf8($postFix);
        if($maxLength-$fixLen <= 0) {
            return self::trword($sourceString, $maxLength);
        }
        if( self::strlen_utf8($sourceString) > $maxLength )
        {
            return self::substr_utf8($sourceString, $maxLength-$fixLen, 0) . $postFix;
        }
        return $sourceString;
    }

	/**
	 * 截取utf-8编码的字符串
	 * @param $sourceString 被截取的字符串
	 * @param $length		截取的长度
	 * @param $offset		开始截取的位置，从0开始
	 * @return string		截取得到的字符串
	 */
	public static function substr_utf8($sourceString, $length, $offset = 0)
	{
	    if (empty($sourceString)) {
            return '';
        }
    	return mb_substr($sourceString, $offset, $length, 'utf-8');
	}
	
	/**
	 * 按照寬度截取utf-8编码的字符串
	 * @param $sourceString 被截取的字符串
	 * @param $length		截取的长度
	 * @param $offset		开始截取的位置，从0开始
	 * @return string		截取得到的字符串
	 */
	public function substr_utf8_forwidth($sourceString, $length, $offset = 0) {
        $osLen = mb_strlen($sourceString, 'utf8');
        if ($osLen <= $length) {
            return $sourceString;
        }
        $string = mb_substr($sourceString, $offset, $length, 'utf8');
        $sLen = mb_strlen($string, 'utf8');//字符长度
        $bLen = strlen($string);//字节长度
        $sCharCount = (3 * $sLen - $bLen) / 2;//截取后单字节字符个数
        if ($sCharCount > 0) {
            $arr = preg_split('/(?<!^)(?!$)/u', mb_substr($sourceString, $length, $sCharCount, 'utf8')); // 将中英混合字符串分割成数组（UTF8下有效）
        } else {
            return $string;
        }
        
        foreach ($arr as $value) {
            if (ord($value) < 128 && ord($value) > 0) {
                $sCharCount = $sCharCount - 1;
            } else {
                $sCharCount = $sCharCount - 2;
            }
            if ($sCharCount < 0) {
                break;
            }
            $string .= $value;
        }
        return $string;
	}
	
	/**
	 * 查找字符串位置
	 *
	 * @param sring $sourceString 被查找的字符串
	 * @param string $needle 	  查找字符串
	 * @param int $offset   	  开始查找的位置，从0开始
	 * @return 若存在返回字符串位置，否则返回false
	 */
	public static function strpos_utf8($sourceString, $needle, $offset = 0)
	{
	    if (empty($sourceString) || empty($needle)) {
	        return false;
	    }
    	return @mb_strpos($sourceString, $needle, $offset, 'utf-8');
	}
	
	/**
	 * 获得utf-8编码的字符串的长度
	 * @param $sourceString	utf-8编码的字符串
	 * @return int			长度
	 */
	public static function strlen_utf8($sourceString)
	{
	    if (empty($sourceString)) {
            return 0;
        }

		//环境检查不要在runtime中处理  2009-08-03 3:13 PM zjy
		/*
		if(!extension_loaded('mbstring') )
			die('php-mbstring should be install ');
		*/
		//换行符修改为一字节 和JS判断相一致 修改人：刘必坚 修改时间：2009.2.18
		$str = str_replace("\r\n"," ",$sourceString);
		$str = stripslashes($str);
		
	    return mb_strlen($str, 'utf-8');
	}
	
	/**
	 * 检查一个字符串是否以特定字符串开始
	 * @param $sourceString	待检查的字符串
	 * @param $prefix		特定字符串
	 * @return boolean		如果是，返回true，否则返回false
	 */
	public static function beginWith($sourceString, $prefix)
	{
		if($prefix == '' || $sourceString == '')
			return false;

		return (@substr_compare($sourceString, $prefix, 0, strlen($prefix)) === 0);
	}
	
	/**
	 * 检查一个字符串是否以特定字符串结尾
	 * @param $sourceString	待检查的字符串
	 * @param $postfix		特定字符串
	 * @return boolean		如果是，返回true，否则返回false
	 */
	public static function endWith($sourceString, $postfix)
	{
		if($postfix == '' || $sourceString == '')
			return false;

		$size = strlen($postfix);
		return (@substr_compare($sourceString, $postfix, strlen($sourceString) - $size, $size) === 0);
	}
	
	/**
	 * 检查一段utf-8编码的字符串是否为中文
	 * @param $sourceString	被检查的字符串
	 * @return boolean		如果是，返回true，否则返回false
	 */
	public static function isChinese_utf8($sourceString)
	{
		return preg_match('/^[\x7f-\xff]+$/', $sourceString);
	}
	
	/**
	 * 将相差timestamp转为如“1分钟前”，“3天前”等形式
	 *
	 * @param timestamp $ts_diff 当前时间 - 要格式化的timestamp
	 */
	public static function formatTime($ts_diff)
	{
		if ($ts_diff <=0)
		{
			return date('Y-m-d');
		}
		else if ( $ts_diff <= 3600 )
		{
			return max(1, (int)($ts_diff/60)) . '分钟前';
		}
		else if ( $ts_diff <= 86400 )
		{
			return ((int)($ts_diff/3600)) . '小时前';
		}
		else
		{
			return ((int)($ts_diff/86400)) . '天前';
		}
	}
	
	//{{{格式化时间显示，用在二手物品等列表页 formatTime2()
	//$time 当前时间 timestamp
	public static function formatTime2($time, $listing_status=0, $pageType='detail')
	{
		if ( $pageType == 'detail' )
			return date("m-d H:i", $time);
		
		$ts_diff = time() - $time;

		if ($ts_diff <=0 || $listing_status==9 )
		{
			return date('m-d');
		}
		else if ( $ts_diff <= 3600 )
		{
			return max(1, (int)($ts_diff/60)) . '分钟前';
		}
		else if ( $ts_diff <= 86400 )
		{
			return ((int)($ts_diff/3600)) . '小时前';
		}
		else
		{
			return date("m-d", $time);
		}
	}
    /** 格式化时间显示，用在房产1/3/5前台列表 formatTimeHousing()
     * @param int $time 当前时间 timestamp
     */
	public static function formatTimeHousing($time, $listing_status=0, $pageType='detail', $post_type = null)
	{
		if ( $pageType == 'detail' )
			return date("m-d H:i", $time);
		
		$ts_diff = time() - $time;

        //{{{ add for 自主置顶
        //if( $ts_diff <= 0 ){
            if( $listing_status >= 9 ){
                return date('m-d');
            }
        //}
        return String::formatTime2($time,$listing_status,$pageType);
	}
	
	/**
	 * 获取unix时间戳秒数
	 *
	 * @return float
	 */
	public static function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}
	
	/**
     * 格式化为安全字符串(用于FCK编辑器提交后的内容清理中)
     *
     * @var string $str 字符串
     * @return string 返回完全的字符串
     */
	public function formatSafeStr($str)
    {
    	include_once dirname(__FILE__) . "/RichText.class.php";

		$richText = new RichText();
		return $richText->filter($str);
    }
    /** 将数字星期转换成字符串星期 weekNum2String($num)
     * @param int
     * @return string
     */
    public static function weekNum2String($num){
        switch($num){
            case 1:
                return '星期一';
            case 2:
                return '星期二';
            case 3:
                return '星期三';
            case 4:
                return '星期四';
            case 5:
                return '星期五';
            case 6:
                return '星期六';
            case 7:
                return '星期日';
            default:
                return '未知';
        }
    }
    /** 进行防注入检查 _gpc($g)
     * @param  string $g   要检查的变量
     * @return string
     */
    public static function gpc($g){
        $gpc = get_magic_quotes_gpc();
        if (!$gpc) {
            $g = addslashes($g);
        }
        return trim($g);
    }
    /** 生成数字,大小写字母组成的任意位数的字符串 random($min_len,$max_len,$t)
     * @param  $min_len  字符串最小长度
     * @param  $max_len    字符串最大长度
     * @param  $type       生成的字符串类别
     *                     0为全部小写和数字的组合,1为全部大写和数字的组合,3为全部数字的组合,4为大小写字母的组合
     * @return string
     */
    public static function random($min_len=9,$max_len=9,$type=3){
        $str_len = mt_rand($min_len,$max_len);
        $ps = "";
        while(strlen($ps) < $str_len){
            $r = array(
                    mt_rand(49,57),//1-9的ASCII码
                    mt_rand(65,90),//A-Z的ASCII码
                    mt_rand(97,122)//a-z的ASCII码
                    );
            if($type == 3) $tmp = chr($r[mt_rand(0,0)]);
            else $tmp = chr($r[mt_rand(0,2)]);
            if($type == 0)
                $tmp = strtolower($tmp);
            else
                $tmp = $type==1?strtoupper($tmp):$tmp;
            $ps .= $tmp;
        }
        return trim($ps);
    }

    /**
    * @author Chunsheng Wang
    * @param string $String the string to cut.
    * @param int $Length the length of returned string.
    * @param booble $Append whether append "...": false|true
    * @return string the cutted string.
    */
    public static function sysSubStr($String,$Length,$Append = false)
    {
        $StringLast = array();
        if (strlen($String) <= $Length )
        {
            return $String;
        }
        else
        {
            $I = 0;
            while ($I < $Length){
                $StringTMP = substr($String,$I,1);
                if ( ord($StringTMP) >=224 ){
                    $StringTMP = substr($String,$I,3);
                    $I = $I + 3;
                }elseif( ord($StringTMP) >=192 ){
                    $StringTMP = substr($String,$I,2);
                    $I = $I + 2;
                }
                else{
                    $I = $I + 1;
                }
                $StringLast[] = $StringTMP;
            }
            $StringLast = implode("",$StringLast);
            if($Append){
                $StringLast .= "...";
            }
            return $StringLast;
        }
    }

    /**
     * @author jiangyuan1@mail.ganji.com
     * @function 根据字符实际排版长度截取字符串，汉字等占2个长度，ascii码占一个长度
     * @param $String原始字符串
     * @param $Length 截取长度
     * @param $Append 附加字符串
     */
    public static function cutStr($String,$Length,$Append = '') {
        $i = 0;
        $count = 0;
        $len = strlen ($String);

        while ($i < $len && $count < $Length ) {
            $chr = ord ($String[$i]);
            $i++;
            if($i >= $len) break;

            if($chr & 0x80) {   //如果不是ascii码,二进制中前面连续1的个数表示字符占用字节数
                $count=$count+2;
                $chr <<= 1;
                while ($chr & 0x80) {
                    $i++;
                    $chr <<= 1;
                }
            } else {
                $count++;
            }
        }
        $cutStr = substr($String,0,$i);
        if($len > strlen($cutStr)) {
            if('' === $Append ) {
                $Append = '...';
            }
            $cutStr .= $Append;
        }
        return $cutStr;
    }

    /**
     * @author jiangyuan1@mail.ganji.com
     * @function 计算字符串长度，以ascii为单位，ascii为一个长度，汉字等其他字符一律为2个长度
     * @param $str原始字符串
     * @return $strLen返回字符串长度
     */
    public static function getStringLength( $str) {
        $strLen = 0;
        $len = strlen($str);     //实际占用的字节数
        $i = 0;
        while($i < $len) {
            $chr = ord ($str[$i]);
            if($chr & 0x80) {    //非ascii字符
                $strLen += 2;
                $chr <<= 1;
                while ($chr & 0x80) {
                    $i++;
                    $chr <<= 1;
                }
                $i++;
            }else {            //ascii字符
                $strLen++;
                $i++;
            }
        }
        return $strLen;
    }
    
    /**
     * php 5.4.0 json_encode()增加常量JSON_UNESCAPED_UNICODE，
     * 在此版本之前需要模拟此功能
     * @author wangchuanzheng@ganji.com
     * @param type $variable 需要encode的变量，可以是string|int|array...
     * @param type $unescapeUnicode
     * @return type
     */
    public static function jsonEncode($variable, $unescapeUnicode = true) {
        if (!$unescapeUnicode) {
            return json_encode($variable);
        }
        
        //urlencode中文部分
        return urldecode(json_encode(self::_urlencodeValue($variable)));
    }
    
    /**
     * 对数组value部分url编码
     * @param type $variable 数组变量
     * @return type
     */
    private static function _urlencodeValue($variable) {
        if (!is_array($variable) || empty($variable)) {
            return $variable;
        }
        
        foreach ($variable as &$value) {
            if (is_array($value)) {
                $value = self::_urlencodeValue($value);
            } elseif (is_string($value)) {
                $value = urlencode($value);
            }
        }
        
        return $variable;
    }
}
